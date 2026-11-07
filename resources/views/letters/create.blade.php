@extends('layout')
@section('title', 'Write your letter to Santa - Letters2Santa')

@section('content')
<div class="row justify-content-center">
<div class="col-lg-8">
<div class="card p-4 p-md-5" x-data="letterForm()">
    <h1 class="text-christmas-red mb-1">Write to Santa</h1>
    <p class="text-muted">One letter per child. You can add brothers and sisters before paying, so everyone shares one A${{ number_format($price, 2) }}-per-letter checkout.</p>

    @if($afterCutoff)
        <div class="alert alert-warning"><strong>Christmas is almost here!</strong> Letters sent now receive Santa's reply within about an hour instead of on Christmas morning.</div>
    @endif

    @if ($errors->any())
        <div class="alert alert-danger"><ul class="mb-0">@foreach ($errors->all() as $e)<li>{{ $e }}</li>@endforeach</ul></div>
    @endif

    <form method="POST" action="{{ route('letters.store') }}" enctype="multipart/form-data" novalidate>
        @csrf
        <div class="row g-3">
            <div class="col-md-8">
                <label class="form-label fw-semibold" for="child_first_name">Child's first name <span class="text-danger">*</span></label>
                <input type="text" class="form-control @error('child_first_name') is-invalid @enderror" id="child_first_name" name="child_first_name" value="{{ old('child_first_name') }}" maxlength="{{ config('letters.letter.name_max') }}" required autofocus>
                <div class="form-text">First name only. Santa never needs a surname.</div>
            </div>
            <div class="col-md-4">
                <label class="form-label fw-semibold" for="child_age">Age <span class="text-muted">(optional)</span></label>
                <select class="form-select" id="child_age" name="child_age">
                    <option value="">Prefer not to say</option>
                    @for ($a = config('letters.letter.age_min'); $a <= config('letters.letter.age_max'); $a++)
                        <option value="{{ $a }}" @selected(old('child_age') == $a)>{{ $a }}</option>
                    @endfor
                </select>
            </div>
            <div class="col-md-7">
                <label class="form-label fw-semibold" for="town">Town or suburb <span class="text-danger">*</span></label>
                <input type="text" class="form-control @error('town') is-invalid @enderror" id="town" name="town" value="{{ old('town', $lastTown) }}" maxlength="{{ config('letters.letter.town_max') }}" required>
            </div>
            <div class="col-md-5">
                <label class="form-label fw-semibold" for="country_code">Country <span class="text-danger">*</span></label>
                <select class="form-select @error('country_code') is-invalid @enderror" id="country_code" name="country_code" required>
                    @foreach ($countries as $code => $name)
                        <option value="{{ $code }}" @selected(old('country_code', $defaultCountry) === $code)>{{ $name }}</option>
                    @endforeach
                </select>
            </div>

            <div class="col-12">
                <label class="form-label fw-semibold" for="body">The letter <span class="text-muted">(optional if you add a photo)</span></label>
                <textarea class="form-control @error('body') is-invalid @enderror" id="body" name="body" rows="7" maxlength="{{ config('letters.letter.body_max') }}" x-model="body" placeholder="Dear Santa, ...">{{ old('body') }}</textarea>
                <div class="form-text text-end"><span x-text="body.length"></span> / {{ config('letters.letter.body_max') }}</div>
            </div>

            <div class="col-12">
                <label class="form-label fw-semibold">Photo of a drawing or handwritten letter <span class="text-muted">(optional, up to {{ config('letters.letter.max_images') }})</span></label>
                <input type="file" class="form-control @error('images.*') is-invalid @enderror" name="images[]" accept="image/jpeg,image/png,image/webp,image/heic,image/heif,.heic,.heif" multiple @change="preview($event)">
                <div class="form-text">JPG, PNG, HEIC or WebP, up to {{ config('letters.letter.max_image_mb') }} MB each. We remove location data from every photo. Please avoid photos of faces: they are never shown publicly.</div>
                <div class="d-flex gap-2 mt-2" x-show="previews.length" x-cloak>
                    <template x-for="src in previews" :key="src"><img :src="src" class="rounded" style="height:110px;width:110px;object-fit:cover;border:3px solid #fff;box-shadow:0 2px 8px rgba(0,0,0,.2);" alt="Preview"></template>
                </div>
                <div class="text-danger small mt-1" x-show="tooMany" x-cloak>Please choose at most {{ config('letters.letter.max_images') }} images.</div>
            </div>

            <div class="col-12 mt-4">
                <div class="p-3 rounded-3 bg-cream">
                    <div class="fw-semibold mb-2">Sharing (both optional, off by default)</div>
                    <div class="form-check mb-2">
                        <input class="form-check-input" type="checkbox" value="1" id="wall_consent" name="wall_consent" @checked(old('wall_consent'))>
                        <label class="form-check-label" for="wall_consent">Show <strong>first name and town</strong> on the public Letter Wall (e.g. "Emma from Tarneit, Australia").</label>
                    </div>
                    <div class="form-check">
                        <input class="form-check-input" type="checkbox" value="1" id="instagram_consent" name="instagram_consent" @checked(old('instagram_consent'))>
                        <label class="form-check-label" for="instagram_consent">Santa's elves may feature this letter on Instagram (first name and town only, never faces).</label>
                    </div>
                    <div class="form-text">You can withdraw either consent at any time from My Letters.</div>
                </div>
            </div>

            <div class="col-12">
                <div class="form-check">
                    <input class="form-check-input @error('guardian_confirmed') is-invalid @enderror" type="checkbox" value="1" id="guardian_confirmed" name="guardian_confirmed" required @checked(old('guardian_confirmed'))>
                    <label class="form-check-label" for="guardian_confirmed">I am this child's parent or guardian and I agree to the <a href="{{ route('terms') }}" target="_blank">terms</a> and <a href="{{ route('privacy') }}" target="_blank">privacy policy</a>. <span class="text-danger">*</span></label>
                </div>
            </div>

            <div class="col-12 d-flex flex-wrap gap-3 align-items-center mt-3">
                <button type="submit" class="btn btn-christmas"><i class="fas fa-paper-plane me-2"></i>Save letter and continue</button>
                <span class="text-muted">Next: add another child or pay A${{ number_format($price, 2) }} per letter.</span>
            </div>
        </div>
    </form>
</div>
</div>
</div>
@endsection

@push('scripts')
<script>
function letterForm() {
    return {
        body: @json(old('body', '')),
        previews: [],
        tooMany: false,
        preview(e) {
            const files = Array.from(e.target.files || []);
            this.tooMany = files.length > {{ (int) config('letters.letter.max_images') }};
            this.previews = [];
            files.slice(0, {{ (int) config('letters.letter.max_images') }}).forEach(f => {
                if (!f.type.startsWith('image/') || /hei[cf]/i.test(f.type) || /\.hei[cf]$/i.test(f.name)) return; // browsers can't preview HEIC
                const r = new FileReader();
                r.onload = ev => this.previews.push(ev.target.result);
                r.readAsDataURL(f);
            });
        }
    }
}
</script>
@endpush
