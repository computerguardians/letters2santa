<h2 class="h4 text-christmas-green">Change password</h2>
<form method="post" action="{{ route('password.update') }}">@csrf @method('put')
    <div class="row g-3">
        <div class="col-md-4"><label class="form-label" for="update_password_current_password">Current password</label>
            <input id="update_password_current_password" name="current_password" type="password" class="form-control @if($errors->updatePassword->has('current_password')) is-invalid @endif" autocomplete="current-password">
            @if($errors->updatePassword->has('current_password'))<div class="invalid-feedback">{{ $errors->updatePassword->first('current_password') }}</div>@endif</div>
        <div class="col-md-4"><label class="form-label" for="update_password_password">New password</label>
            <input id="update_password_password" name="password" type="password" class="form-control @if($errors->updatePassword->has('password')) is-invalid @endif" autocomplete="new-password">
            @if($errors->updatePassword->has('password'))<div class="invalid-feedback">{{ $errors->updatePassword->first('password') }}</div>@endif</div>
        <div class="col-md-4"><label class="form-label" for="update_password_password_confirmation">Confirm</label>
            <input id="update_password_password_confirmation" name="password_confirmation" type="password" class="form-control" autocomplete="new-password"></div>
    </div>
    <div class="d-flex align-items-center gap-3 mt-3">
        <button class="btn btn-outline-christmas">Update password</button>
        @if (session('status') === 'password-updated')<span class="text-success">Saved.</span>@endif
    </div>
</form>
