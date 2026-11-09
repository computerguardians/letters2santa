<div class="col-md-8"><label class="form-label" for="name">Event name <span class="text-danger">*</span></label>
    <input id="name" class="form-control" name="name" value="{{ old('name', $event?->name) }}" required maxlength="120" placeholder="e.g. Smith & Co Christmas Party">
    <div class="form-text">Printed on the poster and shown to guests.</div></div>
<div class="col-md-4"><label class="form-label" for="event_date">Event date <span class="text-muted">(optional)</span></label>
    <input id="event_date" class="form-control" type="date" name="event_date" value="{{ old('event_date', $event?->event_date?->toDateString()) }}"></div>
<div class="col-md-7"><label class="form-label" for="town">Town or suburb <span class="text-danger">*</span></label>
    <input id="town" class="form-control" name="town" value="{{ old('town', $event?->town) }}" required maxlength="60">
    <div class="form-text">Pre-filled on the guest form so they only type the child's name.</div></div>
<div class="col-md-5"><label class="form-label" for="country_code">Country <span class="text-danger">*</span></label>
    <select id="country_code" class="form-select" name="country_code" required>
        @foreach($countries as $code => $name)<option value="{{ $code }}" @selected(old('country_code', $event?->country_code ?? $defaultCountry ?? 'AU') === $code)>{{ $name }}</option>@endforeach
    </select></div>
<div class="col-12"><label class="form-label" for="logo">Event logo for the poster <span class="text-muted">(optional, uses your business logo otherwise)</span></label>
    <input id="logo" class="form-control" type="file" name="logo" accept="image/png,image/jpeg,image/webp">
    @if($event?->logo_path)<div class="form-check mt-1"><input class="form-check-input" type="checkbox" name="remove_logo" value="1" id="remove_logo"><label class="form-check-label small" for="remove_logo">Remove current logo</label></div>@endif
</div>
