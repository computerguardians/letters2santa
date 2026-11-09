<?php

namespace App\Http\Controllers;

use App\Enums\EnquiryType;
use App\Http\Requests\StorePartyEnquiryRequest;
use App\Mail\PartyEnquiryNotificationMail;
use App\Mail\PartyEnquiryReceivedMail;
use App\Models\PartyEnquiry;
use App\Rules\Turnstile;
use App\Support\Countries;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use Illuminate\View\View;

/** "Book a demo" / "help me set it up" form for prospective party hosts. */
class PartyEnquiryController extends Controller
{
    public function create(Request $request): View
    {
        $type = EnquiryType::tryFrom((string) $request->query('type')) ?? EnquiryType::Demo;

        return view('party.enquire', [
            'type' => $type,
            'types' => EnquiryType::cases(),
            'countries' => Countries::options(),
            'turnstileKey' => Turnstile::enabled() ? config('letters.turnstile.site_key') : null,
            'creditPrice' => config('letters.credit_price_cents') / 100,
        ]);
    }

    public function store(StorePartyEnquiryRequest $request): RedirectResponse
    {
        $data = collect($request->validated())->except(['website', 'cf-turnstile-response'])->all();
        $data['ip_hash'] = hash('sha256', $request->ip().config('app.key'));

        $enquiry = PartyEnquiry::create($data);

        Mail::to(config('letters.business.support_email'))->queue(new PartyEnquiryNotificationMail($enquiry));
        Mail::to($enquiry->email)->queue(new PartyEnquiryReceivedMail($enquiry));

        return redirect()->route('party.enquire.thanks')->with('enquiry_type', $enquiry->type->value);
    }

    public function thanks(Request $request): View|RedirectResponse
    {
        if (! $request->session()->has('enquiry_type')) {
            return redirect()->route('party.enquire');
        }

        return view('party.enquire-thanks', [
            'type' => EnquiryType::from($request->session()->get('enquiry_type')),
        ]);
    }
}
