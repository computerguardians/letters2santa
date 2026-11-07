<?php

namespace App\Http\Controllers;

use App\Enums\LetterSource;
use App\Enums\PaymentStatus;
use App\Http\Requests\StoreLetterRequest;
use App\Models\Letter;
use App\Services\ImageService;
use App\Services\SantaScheduler;
use App\Support\Countries;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;

class LetterController extends Controller
{
    public function index(Request $request): View
    {
        $letters = $request->user()->letters()->with('images')->latest()->get();

        return view('letters.index', [
            'letters' => $letters,
            'unpaidCount' => $letters->where('payment_status', PaymentStatus::Pending)->count(),
        ]);
    }

    public function create(Request $request, SantaScheduler $scheduler): View
    {
        return view('letters.create', [
            'countries' => Countries::options(),
            'afterCutoff' => $scheduler->isAfterCutoff(),
            'defaultCountry' => $request->user()->letters()->latest()->value('country_code') ?? 'AU',
            'lastTown' => $request->user()->letters()->latest()->value('town'),
            'price' => config('letters.letter_price_cents') / 100,
        ]);
    }

    public function store(StoreLetterRequest $request, ImageService $images): RedirectResponse
    {
        $user = $request->user();
        $data = $request->validated();

        $letter = DB::transaction(function () use ($user, $data, $request, $images) {
            $letter = Letter::create([
                'user_id' => $user->id,
                'parent_email' => $user->email,
                'parent_name' => $user->name,
                'child_first_name' => $data['child_first_name'],
                'child_age' => $data['child_age'] ?? null,
                'town' => $data['town'],
                'country_code' => $data['country_code'],
                'body' => filled($data['body'] ?? null) ? trim($data['body']) : null,
                'timezone' => $user->timezone ?: config('letters.default_timezone'),
                'source' => LetterSource::Individual,
                'payment_status' => PaymentStatus::Pending,
                'wall_consent' => (bool) ($data['wall_consent'] ?? false),
                'instagram_consent' => (bool) ($data['instagram_consent'] ?? false),
                'guardian_confirmed' => true,
                'consent_at' => now(),
                'ip_hash' => hash('sha256', $request->ip().config('app.key')),
            ]);

            foreach (array_filter((array) $request->file('images', [])) as $file) {
                $images->store($file, $letter);
            }

            return $letter;
        });

        return redirect()->route('checkout.show')
            ->with('status', "{$letter->child_first_name}'s letter is ready. Add another child or continue to payment.");
    }

    public function destroy(Request $request, Letter $letter): RedirectResponse
    {
        abort_unless($letter->user_id === $request->user()->id, 403);

        DB::transaction(function () use ($letter) {
            foreach ($letter->images as $image) {
                $image->deleteFiles();
                $image->delete();
            }
            $letter->forceFill([
                'body' => null,
                'content_purged_at' => now(),
                'wall_consent' => false,
                'instagram_consent' => false,
                'consent_withdrawn_at' => $letter->consent_withdrawn_at ?? now(),
            ])->save();
            $letter->delete();
        });

        return redirect()->route('letters.index')->with('status', 'The letter has been deleted.');
    }

    public function withdrawConsent(Request $request, Letter $letter): RedirectResponse
    {
        abort_unless($letter->user_id === $request->user()->id, 403);

        $letter->forceFill([
            'wall_consent' => false,
            'instagram_consent' => false,
            'consent_withdrawn_at' => now(),
        ])->save();

        return back()->with('status', "Consent withdrawn. {$letter->child_first_name}'s letter will no longer be shown publicly.");
    }
}
