<?php

namespace App\Http\Controllers\Host;

use App\Enums\UserRole;
use App\Http\Controllers\Controller;
use App\Http\Requests\RegisterBusinessRequest;
use App\Models\Business;
use App\Models\User;
use App\Services\PartyPackService;
use App\Support\Countries;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\View\View;

class BusinessRegistrationController extends Controller
{
    public function create(Request $request): View|RedirectResponse
    {
        if ($request->user()?->business) {
            return redirect()->route('host.dashboard');
        }

        return view('host.register', [
            'countries' => Countries::options(),
            'existingUser' => $request->user(),
        ]);
    }

    public function store(RegisterBusinessRequest $request, PartyPackService $packs): RedirectResponse
    {
        $data = $request->validated();
        $existing = $request->user();

        if ($existing?->business) {
            return redirect()->route('host.dashboard');
        }

        $user = DB::transaction(function () use ($data, $existing, $request, $packs) {
            $user = $existing ?? User::create([
                'name' => $data['contact_name'],
                'email' => $data['email'],
                'password' => Hash::make($data['password']),
                'role' => UserRole::Business,
                'timezone' => $data['timezone'] ?? config('letters.default_timezone'),
                'terms_accepted_at' => now(),
            ]);

            if ($existing) {
                $user->forceFill(['role' => UserRole::Business])->save();
            }

            Business::create([
                'user_id' => $user->id,
                'name' => $data['business_name'],
                'abn' => $data['abn'] ?? null,
                'contact_name' => $data['contact_name'],
                'phone' => $data['phone'] ?? null,
                'country_code' => $data['country_code'],
                'logo_path' => $request->hasFile('logo') ? $packs->storeLogo($request->file('logo'), 'logos/businesses') : null,
            ]);

            return $user;
        });

        if (! $existing) {
            event(new Registered($user));
            Auth::login($user);
        }

        return redirect()->route('host.dashboard')
            ->with('status', 'Welcome aboard! Create your first event to get a QR poster.');
    }
}
