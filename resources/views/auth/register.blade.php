@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row">
        <div class="col-md-8 col-md-offset-2">
            <div class="panel panel-default">
                <div class="panel-heading">Register</div>
                <div class="panel-body">
                    <form class="form-horizontal" role="form" method="POST" action="{{ url('/register') }}">
                        {{ csrf_field() }}

                        {{ dump($errors) }}

                        <div class="form-group{{ $errors->has('company_name') ? ' has-error' : '' }}">
                            <label for="company_name" class="col-md-4 control-label">Nazwa firmy</label>
                            <div class="col-md-6">
                                <input id="company_name" type="text" class="form-control" name="company_name" value="{{ old('company_name') }}" required autofocus>
                                @include('form_helpers.error', ['name' => 'company_name'])
                            </div>
                        </div>

                        <a data-show-hide="company_data">
                            Dodaj dane firmy <i class="glyphicon glyphicon-chevron-down"></i>
                        </a>
                        <div id="company_data" class="hide">
                            <div class="form-group{{ $errors->has('street') ? ' has-error' : '' }}">
                                <label for="street" class="col-md-4 control-label">Ulica</label>
                                <div class="col-md-6">
                                    <input id="street" type="text" class="form-control" name="street" value="{{ old('street') }}">
                                    @include('form_helpers.error', ['name' => 'street'])
                                </div>
                            </div>
                            <div class="form-group{{ $errors->has('city') ? ' has-error' : '' }}">
                                <label for="city" class="col-md-4 control-label">Miasto</label>
                                <div class="col-md-6">
                                    <input id="city" type="text" class="form-control" name="city" value="{{ old('city') }}">
                                    @include('form_helpers.error', ['name' => 'city'])
                                </div>
                            </div>
                            <div class="form-group{{ $errors->has('post_code') ? ' has-error' : '' }}">
                                <label for="post_code" class="col-md-4 control-label">Kod pocztowy</label>
                                <div class="col-md-6">
                                    <input id="post_code" type="text" class="form-control" name="post_code" value="{{ old('post_code') }}">
                                    @include('form_helpers.error', ['name' => 'post_code'])
                                </div>
                            </div>
                            <div class="form-group{{ $errors->has('nip') ? ' has-error' : '' }}">
                                <label for="nip" class="col-md-4 control-label">Nip</label>
                                <div class="col-md-6">
                                    <input id="nip" type="text" class="form-control" name="nip" value="{{ old('nip') }}">
                                    @include('form_helpers.error', ['name' => 'nip'])
                                </div>
                            </div>
                            <div class="form-group{{ $errors->has('regon') ? ' has-error' : '' }}">
                                <label for="regon" class="col-md-4 control-label">Regon</label>
                                <div class="col-md-6">
                                    <input id="regon" type="text" class="form-control" name="regon" value="{{ old('regon') }}">
                                    @include('form_helpers.error', ['name' => 'regon'])
                                </div>
                            </div>
                            <div class="form-group{{ $errors->has('company_email') ? ' has-error' : '' }}">
                                <label for="company_email" class="col-md-4 control-label">E-mail firmowy</label>
                                <div class="col-md-6">
                                    <input id="company_email" type="text" class="form-control" name="company_email" value="{{ old('company_email') }}">
                                    @include('form_helpers.error', ['name' => 'company_email'])
                                </div>
                            </div>
                            <div class="form-group{{ $errors->has('www') ? ' has-error' : '' }}">
                                <label for="www" class="col-md-4 control-label">Strona www</label>
                                <div class="col-md-6">
                                    <input id="www" type="text" class="form-control" name="www" value="{{ old('www') }}">
                                    @include('form_helpers.error', ['name' => 'www'])
                                </div>
                            </div>
                            <div class="form-group{{ $errors->has('phone') ? ' has-error' : '' }}">
                                <label for="phone" class="col-md-4 control-label">Telefon</label>
                                <div class="col-md-6">
                                    <input id="phone" type="text" class="form-control" name="phone" value="{{ old('phone') }}">
                                    @include('form_helpers.error', ['name' => 'phone'])
                                </div>
                            </div>
                            <div class="form-group{{ $errors->has('bank_account_number') ? ' has-error' : '' }}">
                                <label for="bank_account_number" class="col-md-4 control-label">Nr konta bankowego</label>
                                <div class="col-md-6">
                                    <input id="bank_account_number" type="text" class="form-control" name="bank_account_number" value="{{ old('bank_account_number') }}">
                                    @include('form_helpers.error', ['name' => 'bank_account_number'])
                                </div>
                            </div>

                        </div>

                        <div class="form-group{{ $errors->has('email') ? ' has-error' : '' }}">
                            <label for="email" class="col-md-4 control-label">E-Mail</label>

                            <div class="col-md-6">
                                <input id="email" type="email" class="form-control" name="email" value="{{ old('email') }}" required>
                                @include('form_helpers.error', ['name' => 'email'])
                            </div>
                        </div>
                        <div class="form-group{{ $errors->has('password') ? ' has-error' : '' }}">
                            <label for="password" class="col-md-4 control-label">Hasło</label>

                            <div class="col-md-6">
                                <input id="password" type="password" class="form-control" name="password" required>
                                @include('form_helpers.error', ['name' => 'password'])
                            </div>
                        </div>

                        <div class="form-group{{ $errors->has('password_confirmation') ? ' has-error' : '' }}">
                            <label for="password-confirm" class="col-md-4 control-label">Potwierdź Hasło</label>

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
    </div>
</div>
@endsection
