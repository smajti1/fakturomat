@if(isset($user))
    <h1>Zmień hasło/email</h1>
@else
    <h1>Załóż konto</h1>
@endif

<div class="form-group{{ $errors->has('email') ? ' has-danger' : '' }}">
    <label for="email" class="sr-only">E-mail</label>
    <input id="email" type="email" class="form-control form-control-danger" name="email" value="{{ old('email', isset($user) ? $user->email : '') }}" required placeholder="E-mail">
    <div class="form-control-feedback">{{ $errors->first('email') }}</div>
</div>

<div class="form-group{{ $errors->has('password') ? ' has-danger' : '' }}">
    <label for="password" class="sr-only">Hasło</label>
    <input id="password" type="password" class="form-control form-control-danger" name="password" {{ isset($user) ? '' : 'required' }} placeholder="Hasło">
    <div class="form-control-feedback">{{ $errors->first('password') }}</div>
    @if(isset($user))
        <span class="text-muted small">pozostaw to pole puste jeśli nie chcesz zmienić hasła</span>
    @endif
</div>

<div class="form-group{{ $errors->has('password_confirmation') ? ' has-danger' : '' }}">
    <label for="password-confirm" class="sr-only">Potwierdź Hasło</label>
    <input id="password-confirm" type="password" class="form-control form-control-danger" name="password_confirmation" {{ isset($user) ? '' : 'required' }} placeholder="Potwierdź Hasło">
    <div class="form-control-feedback">{{ $errors->first('password_confirmation') }}</div>
</div>