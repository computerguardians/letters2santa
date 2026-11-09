<?php

namespace App\Http\Controllers;

use App\Actions\SubmitPartyLetter;
use App\Exceptions\NoCreditsException;
use App\Http\Requests\StorePartyLetterRequest;
use App\Models\Event;
use App\Models\Letter;
use App\Rules\Turnstile;
use App\Services\PartyPackService;
use App\Services\SantaScheduler;
use App\Support\Countries;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\View\View;

class PartyController extends Controller
{
    /** Marketing page for hosts. */
    public function landing(): View
    {
        return view('party.landing', [
            'creditPrice' => config('letters.credit_price_cents') / 100,
            'letterPrice' => config('letters.letter_price_cents') / 100,
            'credits' => config('letters.credits'),
        ]);
    }

    /** Guest form, reached by scanning the poster. */
    public function show(Request $request, string $slug, SantaScheduler $scheduler, PartyPackService $packs): View
    {
        $event = $this->event($slug);

        return view('party.show', [
            'event' => $event,
            'accepting' => $event->isAcceptingLetters(),
            'countries' => Countries::options(),
            'afterCutoff' => $scheduler->isAfterCutoff(),
            'logoUrl' => $packs->logoUrl($event->logo_path ?: $event->business->logo_path),
            'turnstileKey' => Turnstile::enabled() ? config('letters.turnstile.site_key') : null,
            'letterPrice' => config('letters.letter_price_cents') / 100,
            'fallbackUrl' => auth()->check() ? route('letters.create') : route('register'),
        ]);
    }

    public function store(StorePartyLetterRequest $request, string $slug, SubmitPartyLetter $submit): RedirectResponse
    {
        $event = $this->event($slug);
        $files = array_values(array_filter((array) $request->file('images', [])));

        try {
            $letter = $submit->handle($event, $request->validated(), $files, hash('sha256', $request->ip().config('app.key')));
        } catch (NoCreditsException $e) {
            return redirect()->route('party.show', $slug)
                ->with('error', "Sorry, this party's letters have all been sent, but you can still send yours.");
        }

        Cache::forget('party.screen.'.$event->id);

        return redirect()->route('party.sent', [$slug, 'ref' => $letter->uuid]);
    }

    public function sent(string $slug, Request $request): View
    {
        $event = $this->event($slug);
        $letter = Letter::where('uuid', (string) $request->query('ref'))->where('event_id', $event->id)->first();

        return view('party.sent', ['event' => $event, 'letter' => $letter]);
    }

    /** Full-screen live counter for a TV at the party. */
    public function screen(string $slug): View
    {
        $event = $this->event($slug);

        return view('party.screen', [
            'event' => $event,
            'initial' => $this->screenData($event),
            'pollSeconds' => (int) config('letters.wall.poll_seconds'),
        ]);
    }

    public function screenJson(string $slug): JsonResponse
    {
        $event = $this->event($slug);

        return response()->json($this->screenData($event))
            ->header('Cache-Control', 'public, max-age='.(int) config('letters.wall.cache_seconds'));
    }

    /** Counter + recent names. Only approved letters with wall consent show names; all settled letters count. */
    private function screenData(Event $event): array
    {
        return Cache::remember('party.screen.'.$event->id, (int) config('letters.wall.cache_seconds'), function () use ($event) {
            $recent = $event->letters()->publicOnWall()->latest('paid_at')->limit(12)->get()
                ->map(fn (Letter $l) => [
                    'name' => $l->child_first_name,
                    'town' => $l->town,
                    'flag' => $l->countryFlag(),
                    'ago' => $l->paid_at?->diffForHumans(),
                ])->all();

            return [
                'event' => $event->name,
                'count' => $event->letters()->settled()->count(),
                'credits_remaining' => $event->credits_remaining,
                'recent' => $recent,
            ];
        });
    }

    private function event(string $slug): Event
    {
        return Event::where('slug', $slug)->with('business')->firstOrFail();
    }
}
