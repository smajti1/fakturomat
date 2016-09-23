@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row">
        <div class="col-md-8 col-md-offset-2">
            <h1>Rejestracja</h1>
            <form class="form-inline" role="form" method="POST" action="{{ url('/register') }}">
                {{ csrf_field() }}

                <div class="form-group{{ $errors->has('company_name') ? ' has-danger' : '' }}">
                    <label for="company_name" class="sr-only">Nazwa firmy</label>
                    <input id="company_name" type="text" class="form-control form-control-danger" placeholder="Nazwa firmy" name="company_name" value="{{ old('company_name') }}" required autofocus>
                    <div class="form-control-feedback">{{ $errors->first('company_name') }}</div>
                </div>
                
                <div>
                    <a data-show-hide="company_data" aria-hidden="true">
                        Dodaj dane firmy <i class="fa fa-chevron-down"></i>
                    </a>
                </div>

                <div id="company_data" class="invisible">
                    <div class="form-group{{ $errors->has('street') ? ' has-danger' : '' }}">
                        <label for="street" class="sr-only">Ulica</label>
                        <input id="street" type="text" class="form-control" name="street" value="{{ old('street') }}">
                        @include('form_helpers.error', ['name' => 'street'])
                    </div>
                    <div class="form-group{{ $errors->has('city') ? ' has-danger' : '' }}">
                        <label for="city" class="sr-only">Miasto</label>
                        <input id="city" type="text" class="form-control" name="city" value="{{ old('city') }}">
                        @include('form_helpers.error', ['name' => 'city'])
                    </div>
                    <div class="form-group{{ $errors->has('post_code') ? ' has-danger' : '' }}">
                        <label for="post_code" class="sr-only">Kod pocztowy</label>
                        <input id="post_code" type="text" class="form-control" name="post_code" value="{{ old('post_code') }}">
                        @include('form_helpers.error', ['name' => 'post_code'])
                    </div>
                    <div class="form-group{{ $errors->has('nip') ? ' has-danger' : '' }}">
                        <label for="nip" class="sr-only">Nip</label>
                        <div class="col-md-6">
                            <input id="nip" type="text" class="form-control" name="nip" value="{{ old('nip') }}">
                            @include('form_helpers.error', ['name' => 'nip'])
                        </div>
                    </div>
                    <div class="form-group{{ $errors->has('regon') ? ' has-danger' : '' }}">
                        <label for="regon" class="sr-only">Regon</label>
                        <div class="col-md-6">
                            <input id="regon" type="text" class="form-control" name="regon" value="{{ old('regon') }}">
                            @include('form_helpers.error', ['name' => 'regon'])
                        </div>
                    </div>
                    <div class="form-group{{ $errors->has('company_email') ? ' has-danger' : '' }}">
                        <label for="company_email" class="sr-only">E-mail firmowy</label>
                        <div class="col-md-6">
                            <input id="company_email" type="text" class="form-control" name="company_email" value="{{ old('company_email') }}">
                            @include('form_helpers.error', ['name' => 'company_email'])
                        </div>
                    </div>
                    <div class="form-group{{ $errors->has('www') ? ' has-danger' : '' }}">
                        <label for="www" class="sr-only">Strona www</label>
                        <div class="col-md-6">
                            <input id="www" type="text" class="form-control" name="www" value="{{ old('www') }}">
                            @include('form_helpers.error', ['name' => 'www'])
                        </div>
                    </div>
                    <div class="form-group{{ $errors->has('phone') ? ' has-danger' : '' }}">
                        <label for="phone" class="sr-only">Telefon</label>
                        <div class="col-md-6">
                            <input id="phone" type="text" class="form-control" name="phone" value="{{ old('phone') }}">
                            @include('form_helpers.error', ['name' => 'phone'])
                        </div>
                    </div>
                    <div class="form-group{{ $errors->has('bank_account_number') ? ' has-danger' : '' }}">
                        <label for="bank_account_number" class="sr-only">Nr konta bankowego</label>
                        <div class="col-md-6">
                            <input id="bank_account_number" type="text" class="form-control" name="bank_account_number" value="{{ old('bank_account_number') }}">
                            @include('form_helpers.error', ['name' => 'bank_account_number'])
                        </div>
                    </div>

                </div>

                <div class="form-group{{ $errors->has('email') ? ' has-danger' : '' }}">
                    <label for="email" class="sr-only">E-Mail</label>

                    <div class="col-md-6">
                        <input id="email" type="email" class="form-control" name="email" value="{{ old('email') }}" required>
                        @include('form_helpers.error', ['name' => 'email'])
                    </div>
                </div>
                <div class="form-group{{ $errors->has('password') ? ' has-danger' : '' }}">
                    <label for="password" class="sr-only">Hasło</label>

                    <div class="col-md-6">
                        <input id="password" type="password" class="form-control" name="password" required>
                        @include('form_helpers.error', ['name' => 'password'])
                    </div>
                </div>

                <div class="form-group{{ $errors->has('password_confirmation') ? ' has-danger' : '' }}">
                    <label for="password-confirm" class="sr-only">Potwierdź Hasło</label>

                    <div class="col-md-6">
                        <input id="password-confirm" type="password" class="form-control" name="password_confirmation" required>
                        @include('form_helpers.error', ['name' => 'password_confirmation'])
                    </div>
                </div>

                <div class="form-group">
                    <div class="col-md-6 col-md-offset-4">
                        <button type="submit" class="btn btn-primary">
                            Utwórz konto
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
