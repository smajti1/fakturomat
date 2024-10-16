@extends('layouts.app')

@section('content')
    <div class="row">
        <div class="col-md-8 col-md-offset-2">
            <h1>Rejestracja</h1>
            <form class="form-horizontal" role="form" method="POST" action="{{ url('/register') }}">
                {{ csrf_field() }}

                <div class="mb-3{{ $errors->has('company_name') ? ' has-danger' : '' }}">
                    <label for="company_name">Nazwa firmy</label>
                    <input id="company_name" type="text" class="form-control form-control-danger"
                           placeholder="Nazwa firmy" name="company_name" value="{{ old('company_name') }}" required
                           autofocus>
                    <div class="form-control-feedback">{{ $errors->first('company_name') }}</div>
                </div>

                <div class="mb-3{{ $errors->has('email') ? ' has-danger' : '' }}">
                    <label for="email">E-mail</label>
                    <input id="email" type="email" class="form-control form-control-danger" name="email"
                           value="{{ old('email') }}" required placeholder="E-mail">
                    <div class="form-control-feedback">{{ $errors->first('email') }}</div>
                </div>
                <div class="mb-3{{ $errors->has('password') ? ' has-danger' : '' }}">
                    <label for="password">Hasło</label>
                    <input id="password" type="password" class="form-control form-control-danger" name="password"
                           required placeholder="Hasło">
                    <div class="form-control-feedback">{{ $errors->first('password') }}</div>
                </div>

                <div class="mb-3{{ $errors->has('password_confirmation') ? ' has-danger' : '' }}">
                    <label for="password-confirm">Potwierdź Hasło</label>
                    <input id="password-confirm" type="password" class="form-control form-control-danger"
                           name="password_confirmation" required placeholder="Potwierdź Hasło">
                    <div class="form-control-feedback">{{ $errors->first('password_confirmation') }}</div>
                </div>

                <div>
                    <a data-show-hide="company_data" aria-hidden="true">
                        Dodaj dane firmy <i class="fa fa-chevron-down"></i>
                    </a>
                </div>

                <div id="company_data" hidden>
                    @include('helpers.google_address')

                    <div class="mb-3{{ $errors->has('tax_id_number') ? ' has-danger' : '' }}">
                        <label for="tax_id_number">Nip</label>
                        <input id="tax_id_number" type="text" class="form-control form-control-danger"
                               name="tax_id_number" value="{{ old('tax_id_number') }}" placeholder="Nip">
                        <div class="form-control-feedback">{{ $errors->first('tax_id_number') }}</div>
                    </div>
                    <div class="mb-3{{ $errors->has('regon') ? ' has-danger' : '' }}">
                        <label for="regon">Regon</label>
                        <input id="regon" type="text" class="form-control form-control-danger" name="regon"
                               value="{{ old('regon') }}" placeholder="Regon">
                        <div class="form-control-feedback">{{ $errors->first('regon') }}</div>
                    </div>
                    <div class="mb-3{{ $errors->has('company_email') ? ' has-danger' : '' }}">
                        <label for="company_email">E-mail firmowy</label>
                        <input id="company_email" type="text" class="form-control form-control-danger"
                               name="company_email" value="{{ old('company_email') }}" placeholder="E-mail firmowy">
                        <div class="form-control-feedback">{{ $errors->first('company_email') }}</div>
                    </div>
                    <div class="mb-3{{ $errors->has('website') ? ' has-danger' : '' }}">
                        <label for="website">Strona www</label>
                        <input id="website" type="text" class="form-control form-control-danger" name="website"
                               value="{{ old('website') }}" placeholder="Strona www">
                        <div class="form-control-feedback">{{ $errors->first('website') }}</div>
                    </div>
                    <div class="mb-3{{ $errors->has('phone') ? ' has-danger' : '' }}">
                        <label for="phone">Telefon</label>
                        <input id="phone" type="text" class="form-control form-control-danger" name="phone"
                               value="{{ old('phone') }}" placeholder="Telefon">
                        <div class="form-control-feedback">{{ $errors->first('phone') }}</div>
                    </div>
                    <div class="mb-3{{ $errors->has('bank_account') ? ' has-danger' : '' }}">
                        <label for="bank_account">Konto bankowe</label>
                        <input id="bank_account" type="text" class="form-control form-control-danger"
                               name="bank_account" value="{{ old('bank_account') }}" placeholder="Konto bankowe">
                        <div class="form-control-feedback">{{ $errors->first('bank_account') }}</div>
                    </div>

                </div>

                <button type="submit" class="btn btn-primary">
                    Utwórz konto
                </button>
            </form>
        </div>
    </div>
@endsection