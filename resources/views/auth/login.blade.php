@extends('layouts.app')

@section('content')
    <div class="row">
        <div class="col-md-8 col-md-offset-2">
            <h1>Logowanie</h1>
            <form class="form-horizontal" role="form" method="POST" action="{{ url('/login') }}">
                {{ csrf_field() }}

                <div class="mb-3{{ $errors->has('email') ? ' has-danger' : '' }}">
                    <label for="email">E-mail</label>
                    <input id="email" type="email" class="form-control form-control-danger" name="email"
                           value="{{ old('email') }}" required autofocus placeholder="E-mail">
                    <div class="form-control-feedback">{{ $errors->first('email') }}</div>
                </div>

                <div class="mb-3{{ $errors->has('password') ? ' has-danger' : '' }}">
                    <label for="password">Hasło</label>
                    <input id="password" type="password" class="form-control form-control-danger" name="password"
                           required placeholder="Hasło">
                    <div class="form-control-feedback">{{ $errors->first('password') }}</div>
                </div>

                <div class="mb-3">
                    <div class="col-md-6 col-md-offset-4">
                        <div class="checkbox">
                            <label>
                                <input type="checkbox" name="remember"> Pamiętaj mnie
                            </label>
                        </div>
                    </div>
                </div>

                <div class="mb-3">
                    <div class="col-md-8 col-md-offset-4">
                        <button type="submit" class="btn btn-primary">
                            Zaloguj
                        </button>

                        <a class="btn btn-link" href="{{ url('/password/reset') }}">
                            Przypomnij hasło?
                        </a>
                    </div>
                </div>
            </form>
        </div>
    </div>
@endsection
