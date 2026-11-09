<?php

namespace App\Http\Controllers\Host;

use App\Http\Controllers\Controller;
use App\Models\Business;
use App\Services\PartyPackService;
use App\Support\Countries;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\View\View;

class DashboardController extends Controller
{
    public function index(Request $request, PartyPackService $packs): View
    {
        $business = $request->user()->business;
        $events = $business->events()->withCount(['letters' => fn ($q) => $q->settled()])->latest()->get();

        return view('host.dashboard', [
            'business' => $business,
            'events' => $events,
            'logoUrl' => $packs->logoUrl($business->logo_path),
            'countries' => Countries::options(),
            'totals' => [
                'letters' => $events->sum('letters_count'),
                'credits_remaining' => $events->where('status', \App\Enums\EventStatus::Active)->sum('credits_remaining'),
            ],
        ]);
    }

    public function updateBusiness(Request $request, PartyPackService $packs): RedirectResponse
    {
        $business = $request->user()->business;

        $data = $request->validate([
            'name' => ['required', 'string', 'max:120'],
            'abn' => ['nullable', 'string', 'max:20'],
            'contact_name' => ['required', 'string', 'max:100'],
            'phone' => ['nullable', 'string', 'max:30'],
            'country_code' => ['required', 'string', 'size:2', Rule::in(Countries::CODES)],
            'logo' => ['nullable', 'file', 'mimes:jpg,jpeg,png,webp', 'max:5120'],
            'remove_logo' => ['nullable', 'boolean'],
        ]);

        if ($request->hasFile('logo')) {
            $packs->deleteLogo($business->logo_path);
            $data['logo_path'] = $packs->storeLogo($request->file('logo'), 'logos/businesses');
        } elseif ($request->boolean('remove_logo')) {
            $packs->deleteLogo($business->logo_path);
            $data['logo_path'] = null;
        }
        unset($data['logo'], $data['remove_logo']);
        $data['abn'] = $data['abn'] ? preg_replace('/\s+/', '', $data['abn']) : null;

        $business->update($data);

        return redirect()->route('host.dashboard')->with('status', 'Business details saved.');
    }
}
