@extends('layout')
@section('title', 'Write to Santa at ' . $event->name . ' - Letters2Santa')

@section('content')
<div class="row justify-content-center">
<div class="col-lg-7">
<div class="card p-4 p-md-5" x-data="partyForm()" x-init="detectTz()">
    <div class="text-center mb-3">
        @if($logoUrl)<img src="{{ $logoUrl }}" alt="{{ $event->business->name }}" style="max-height:70px;max-width:220px;" class="mb-2">@endif
        <div class="text-muted small text-uppercase" style="letter-spacing:2px;">{{ $event->name }}</div>
        <h1 class="text-christmas-red mb-1">Write to Santa!</h1>
        <p class="text-muted mb-0">Letters at this party are a gift from <strong>{{ $event->business->name }}</strong>. No payment needed.</p>
    </div>

    @if(! $accepting)
        <div class="alert alert-warning text-center">
            @if($event->status === \App\Enums\EventStatus::Paused)
                <strong>This party's letter box is paused.</strong> Please check back shortly.
            @elseif($event->status === \App\Enums\EventStatus::Closed)
                <strong>This event has finished.</strong>
            @else
                <strong>This party's letters have all been sent!</strong>
            @endif
            <div class="mt-2">You can still send yours for A${{ number_format($letterPrice, 2) }}.</div>
            <a href="{{ $fallbackUrl }}" class="btn btn-christmas mt-3">Write to Santa for A${{ number_format($letterPrice, 2) }}</a>
        </div>
    @else
        @if($afterCutoff)
            <div class="alert alert-warning"><strong>Christmas is almost here!</strong> Letters sent now get Santa's reply within about an hour instead of on Christmas morning.</div>
        @endif

        @if ($errors->any())
            <div class="alert alert-danger"><ul class="mb-0">@foreach ($errors->all() as $e)<li>{{ $e }}</li>@endforeach</ul></div>
        @endif

        <form method="POST" action="{{ route('party.store', $event->slug) }}" enctype="multipart/form-data" novalidate @submit="submitting = true">
            @csrf
            <input type="hidden" name="timezone" x-model="tz">
            <div class="row g-3">
                <div class="col-8">
                    <label class="form-label fw-semibold" for="child_first_name">Child's first name <span class="text-danger">*</span></label>
                    <input type="text" class="form-control form-control-lg @error('child_first_name') is-invalid @enderror" id="child_first_name" name="child_first_name" value="{{ old('child_first_name') }}" maxlength="{{ config('letters.letter.name_max') }}" required autocomplete="off">
                </div>
                <div class="col-4">
                    <label class="form-label fw-semibold" for="child_age">Age</label>
                    <select class="form-select form-select-lg" id="child_age" name="child_age">
                        <option value="">–</option>
                        @for ($a = config('letters.letter.age_min'); $a <= config('letters.letter.age_max'); $a++)
                            <option value="{{ $a }}" @selected(old('child_age') == $a)>{{ $a }}</option>
                        @endfor
                    </select>
                </div>
                <div class="col-7">
                    <label class="form-label fw-semibold" for="town">Town or suburb <span class="text-danger">*</span></label>
                    <input type="text" class="form-control @error('town') is-invalid @enderror" id="town" name="town" value="{{ old('town', $event->town) }}" maxlength="{{ config('letters.letter.town_max') }}" required>
                </div>
                <div class="col-5">
                    <label class="form-label fw-semibold" for="country_code">Country <span class="text-danger">*</span></label>
                    <select class="form-select @error('country_code') is-invalid @enderror" id="country_code" name="country_code" required>
                        @foreach ($countries as $code => $name)
                            <option value="{{ $code }}" @selected(old('country_code', $event->country_code) === $code)>{{ $name }}</option>
                        @endforeach
                    </select>
                </div>

                <div class="col-12">
                    <label class="form-label fw-semibold" for="body">Dear Santa… <span class="text-muted">(optional if you add a photo)</span></label>
                    <textarea class="form-control @error('body') is-invalid @enderror" id="body" name="body" rows="6" maxlength="{{ config('letters.letter.body_max') }}" x-model="body" placeholder="Dear Santa, this year I have been...">{{ old('body') }}</textarea>
                    <div class="form-text text-end"><span x-text="body.length"></span> / {{ config('letters.letter.body_max') }}</div>
                </div>

                <div class="col-12">
                    <label class="form-label fw-semibold">Photo of a drawing or handwritten letter <span class="text-muted">(optional, up to {{ config('letters.letter.max_images') }})</span></label>
                    <input type="file" class="form-control @error('images.*') is-invalid @enderror" name="images[]" accept="image/jpeg,image/png,image/webp,image/heic,image/heif,.heic,.heif" multiple capture="environment" @change="preview($event)">
                    <div class="form-text">Please avoid photos of faces: they are never shown publicly. Location data is removed from every photo.</div>
                    <div class="d-flex gap-2 mt-2" x-show="previews.length" x-cloak>
                        <template x-for="src in previews" :key="src"><img :src="src" class="rounded" style="height:90px;width:90px;object-fit:cover;border:3px solid #fff;box-shadow:0 2px 8px rgba(0,0,0,.2);" alt="Preview"></template>
                    </div>
                </div>

                <div class="col-12 mt-4">
                    <div class="p-3 rounded-3 bg-cream">
                        <div class="fw-semibold mb-2">Parent or guardian</div>
                        <div class="row g-2">
                            <div class="col-md-5">
                                <label class="form-label small" for="parent_name">Your first name <span class="text-muted">(optional)</span></label>
                                <input type="text" class="form-control" id="parent_name" name="parent_name" value="{{ old('parent_name') }}" maxlength="100" autocomplete="given-name">
                            </div>
                            <div class="col-md-7">
                                <label class="form-label small" for="parent_email">Your email <span class="text-danger">*</span></label>
                                <input type="email" class="form-control @error('parent_email') is-invalid @enderror" id="parent_email" name="parent_email" value="{{ old('parent_email') }}" required autocomplete="email" inputmode="email">
                                <div class="form-text">Santa's reply arrives here on Christmas morning. Elf Jingles will confirm the letter arrived. {{ $event->business->name }} never sees your email.</div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-12">
                    <div class="p-3 rounded-3 bg-cream">
                        <div class="fw-semibold mb-2">Sharing (both optional, off by default)</div>
                        <div class="form-check mb-2">
                            <input class="form-check-input" type="checkbox" value="1" id="wall_consent" name="wall_consent" @checked(old('wall_consent'))>
                            <label class="form-check-label" for="wall_consent">Show <strong>first name and town</strong> on the public Letter Wall and this party's live screen (e.g. "Emma from Tarneit").</label>
                        </div>
                        <div class="form-check">
                            <input class="form-check-input" type="checkbox" value="1" id="instagram_consent" name="instagram_consent" @checked(old('instagram_consent'))>
                            <label class="form-check-label" for="instagram_consent">Santa's elves may feature this letter on Instagram (first name and town only, never faces).</label>
                        </div>
                    </div>
                </div>

                <div class="col-12">
                    <div class="form-check">
                        <input class="form-check-input @error('guardian_confirmed') is-invalid @enderror" type="checkbox" value="1" id="guardian_confirmed" name="guardian_confirmed" required @checked(old('guardian_confirmed'))>
                        <label class="form-check-label" for="guardian_confirmed">I am this child's parent or guardian and I agree to the <a href="{{ route('terms') }}" target="_blank">terms</a> and <a href="{{ route('privacy') }}" target="_blank">privacy policy</a>. <span class="text-danger">*</span></label>
                    </div>
                </div>

                @if($turnstileKey)
                    <div class="col-12"><div class="cf-turnstile" data-sitekey="{{ $turnstileKey }}" data-theme="light"></div></div>
                @endif

                <div class="col-12 d-grid mt-3">
                    <button type="submit" class="btn btn-christmas btn-lg" :disabled="submitting">
                        <span x-show="!submitting"><i class="fas fa-paper-plane me-2"></i>Send to the North Pole</span>
                        <span x-show="submitting" x-cloak><span class="spinner-border spinner-border-sm me-2"></span>Sending…</span>
                    </button>
                </div>
            </div>
        </form>
    @endif
</div>
</div>
</div>
@endsection

@push('scripts')
@if($turnstileKey)<script src="https://challenges.cloudflare.com/turnstile/v0/api.js" async defer></script>@endif
<script>
function partyForm() {
    return {
        body: @json(old('body', '')),
        previews: [], submitting: false, tz: '',
        detectTz() { try { this.tz = Intl.DateTimeFormat().resolvedOptions().timeZone || ''; } catch (e) {} },
        preview(e) {
            const files = Array.from(e.target.files || []).slice(0, {{ (int) config('letters.letter.max_images') }});
            this.previews = [];
            files.forEach(f => {
                if (!f.type.startsWith('image/') || /hei[cf]/i.test(f.type) || /\.hei[cf]$/i.test(f.name)) return;
                const r = new FileReader();
                r.onload = ev => this.previews.push(ev.target.result);
                r.readAsDataURL(f);
            });
        }
    }
}
</script>
@endpush
