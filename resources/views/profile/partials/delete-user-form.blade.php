<h2 class="h4 text-danger">Delete account</h2>
<p class="text-muted">Deleting your account permanently removes your letters, images and personal details. Santa emails already scheduled will not be sent.</p>
<form method="post" action="{{ route('profile.destroy') }}" onsubmit="return confirm('Delete your account and all letters? This cannot be undone.')">@csrf @method('delete')
    <div class="row g-2 align-items-end">
        <div class="col-md-5"><label class="form-label" for="delete_password">Confirm with your password</label>
            <input id="delete_password" name="password" type="password" class="form-control @if($errors->userDeletion->has('password')) is-invalid @endif" placeholder="Password">
            @if($errors->userDeletion->has('password'))<div class="invalid-feedback">{{ $errors->userDeletion->first('password') }}</div>@endif</div>
        <div class="col-md-4"><button class="btn btn-outline-danger rounded-pill">Delete account</button></div>
    </div>
</form>
