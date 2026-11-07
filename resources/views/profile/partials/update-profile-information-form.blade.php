<h2 class="h4 text-christmas-green">Profile</h2>
<p class="text-muted">Your name, email and the timezone Santa uses for Christmas morning.</p>
<form id="send-verification" method="post" action="{{ route('verification.send') }}">@csrf</form>
<form method="post" action="{{ route('profile.update') }}">@csrf @method('patch')
    <div class="row g-3">
        <div class="col-md-6"><label class="form-label" for="name">Name</label>
            <input id="name" name="name" type="text" class="form-control @error('name') is-invalid @enderror" value="{{ old('name', $user->name) }}" required>
            @error('name')<div class="invalid-feedback">{{ $message }}</div>@enderror</div>
        <div class="col-md-6"><label class="form-label" for="email">Email</label>
            <input id="email" name="email" type="email" class="form-control @error('email') is-invalid @enderror" value="{{ old('email', $user->email) }}" required>
            @error('email')<div class="invalid-feedback">{{ $message }}</div>@enderror
            @if ($user instanceof \Illuminate\Contracts\Auth\MustVerifyEmail && ! $user->hasVerifiedEmail())
                <div class="form-text">Your email is unverified. <button form="send-verification" class="btn btn-link btn-sm p-0 align-baseline">Resend verification email</button></div>
                @if (session('status') === 'verification-link-sent')<div class="text-success small">A new verification link has been sent.</div>@endif
            @endif</div>
        <div class="col-md-6"><label class="form-label" for="timezone">Timezone</label>
            <select id="timezone" name="timezone" class="form-select @error('timezone') is-invalid @enderror">
                @foreach (timezone_identifiers_list() as $zone)<option value="{{ $zone }}" @selected(old('timezone', $user->timezone) === $zone)>{{ str_replace('_', ' ', $zone) }}</option>@endforeach
            </select>
            <div class="form-text">Santa's letter arrives at 7 am on 25 December in this timezone (for letters not yet scheduled).</div></div>
    </div>
    <div class="d-flex align-items-center gap-3 mt-3">
        <button class="btn btn-christmas">Save</button>
        @if (session('status') === 'profile-updated')<span class="text-success">Saved.</span>@endif
    </div>
</form>
