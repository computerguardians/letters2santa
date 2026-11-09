<?php

namespace App\Http\Controllers\Host;

use App\Enums\EventStatus;
use App\Enums\ModerationStatus;
use App\Http\Controllers\Controller;
use App\Http\Requests\StoreEventRequest;
use App\Models\Event;
use App\Services\PartyPackService;
use App\Support\Countries;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\View\View;
use Symfony\Component\HttpFoundation\StreamedResponse;

class EventController extends Controller
{
    public function create(Request $request): View
    {
        $business = $request->user()->business;

        return view('host.events.create', [
            'countries' => Countries::options(),
            'defaultCountry' => $business->country_code,
            'credits' => config('letters.credits'),
            'creditPrice' => config('letters.credit_price_cents') / 100,
        ]);
    }

    public function store(StoreEventRequest $request, PartyPackService $packs): RedirectResponse
    {
        $business = $request->user()->business;
        $data = $request->validated();

        $event = $business->events()->create([
            'name' => $data['name'],
            'event_date' => $data['event_date'] ?? null,
            'town' => $data['town'],
            'country_code' => $data['country_code'],
            'logo_path' => $request->hasFile('logo') ? $packs->storeLogo($request->file('logo'), 'logos/events') : null,
            'status' => EventStatus::Active,
        ]);

        return redirect()->route('host.events.show', $event)
            ->with('status', 'Event created. Choose how many letter credits you need to activate the QR poster.');
    }

    public function show(Request $request, Event $event, PartyPackService $packs): View
    {
        $this->authorizeEvent($request, $event);

        $letters = $event->letters()->settled()->with('images')->latest()->paginate(50);

        return view('host.events.show', [
            'event' => $event,
            'letters' => $letters,
            'lettersCount' => $event->letters()->settled()->count(),
            'purchases' => $event->creditPurchases()->latest()->get(),
            'credits' => config('letters.credits'),
            'creditPrice' => config('letters.credit_price_cents') / 100,
            'logoUrl' => $packs->logoUrl($event->logo_path ?: $event->business->logo_path),
            'qrSvg' => $packs->qrSvg($event, 260),
            'approved' => ModerationStatus::Approved,
        ]);
    }

    public function edit(Request $request, Event $event, PartyPackService $packs): View
    {
        $this->authorizeEvent($request, $event);

        return view('host.events.edit', [
            'event' => $event,
            'countries' => Countries::options(),
            'logoUrl' => $packs->logoUrl($event->logo_path),
        ]);
    }

    public function update(StoreEventRequest $request, Event $event, PartyPackService $packs): RedirectResponse
    {
        $this->authorizeEvent($request, $event);
        $data = $request->validated();

        if ($request->hasFile('logo')) {
            $packs->deleteLogo($event->logo_path);
            $data['logo_path'] = $packs->storeLogo($request->file('logo'), 'logos/events');
        } elseif ($request->boolean('remove_logo')) {
            $packs->deleteLogo($event->logo_path);
            $data['logo_path'] = null;
        }
        unset($data['logo'], $data['remove_logo']);

        $event->update($data);

        return redirect()->route('host.events.show', $event)->with('status', 'Event updated.');
    }

    /** Pause, resume or close. Closing is final. */
    public function status(Request $request, Event $event): RedirectResponse
    {
        $this->authorizeEvent($request, $event);

        $action = $request->validate(['action' => ['required', 'in:pause,resume,close']])['action'];

        if ($event->status === EventStatus::Closed) {
            return back()->with('error', 'This event is closed and cannot be reopened.');
        }

        $event->update(['status' => match ($action) {
            'pause' => EventStatus::Paused,
            'resume' => EventStatus::Active,
            'close' => EventStatus::Closed,
        }]);

        return back()->with('status', match ($action) {
            'pause' => 'Event paused. Guests will see a "paused" message until you resume it.',
            'resume' => 'Event resumed. Guests can send letters again.',
            'close' => 'Event closed.',
        });
    }

    public function qrSvg(Request $request, Event $event, PartyPackService $packs): Response
    {
        $this->authorizeEvent($request, $event);

        return response($packs->qrSvg($event, 1000), 200, [
            'Content-Type' => 'image/svg+xml',
            'Content-Disposition' => 'attachment; filename="letters2santa-qr-'.$event->slug.'.svg"',
        ]);
    }

    public function qrPng(Request $request, Event $event, PartyPackService $packs): Response
    {
        $this->authorizeEvent($request, $event);

        return response($packs->qrPng($event, 1200), 200, [
            'Content-Type' => 'image/png',
            'Content-Disposition' => 'attachment; filename="letters2santa-qr-'.$event->slug.'.png"',
        ]);
    }

    public function poster(Request $request, Event $event, PartyPackService $packs): Response
    {
        $this->authorizeEvent($request, $event);

        return response($packs->posterPdf($event), 200, [
            'Content-Type' => 'application/pdf',
            'Content-Disposition' => ($request->boolean('download') ? 'attachment' : 'inline').'; filename="'.$packs->posterFilename($event).'"',
        ]);
    }

    /** CSV of letters: first name, age, town, country, time. Never parent emails. */
    public function export(Request $request, Event $event): StreamedResponse
    {
        $this->authorizeEvent($request, $event);

        $filename = 'letters-'.\Illuminate\Support\Str::slug($event->name).'-'.now()->format('Ymd').'.csv';
        $tz = $request->user()->timezone ?: config('letters.default_timezone');

        return response()->streamDownload(function () use ($event, $tz) {
            $out = fopen('php://output', 'w');
            fputcsv($out, ['Reference', 'First name', 'Age', 'Town', 'Country', 'Sent at ('.$tz.')']);
            $event->letters()->settled()->oldest()->chunk(200, function ($letters) use ($out, $tz) {
                foreach ($letters as $l) {
                    fputcsv($out, [
                        $l->reference, $l->child_first_name, $l->child_age, $l->town, $l->countryName(),
                        $l->created_at->timezone($tz)->format('Y-m-d H:i'),
                    ]);
                }
            });
            fclose($out);
        }, $filename, ['Content-Type' => 'text/csv']);
    }

    private function authorizeEvent(Request $request, Event $event): void
    {
        abort_unless($event->business_id === $request->user()->business?->id, 403);
    }
}
