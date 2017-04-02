@extends('layouts.app')

@section('content')
    <div class="row">
        <div class="col-md-8 col-md-offset-2">
            <div class="panel panel-default">
                <h1>Resetuj hasło</h1>

                <div class="panel-body">
                    <form class="form-horizontal" role="form" method="POST" action="{{ url('/password/reset') }}">
                        {{ csrf_field() }}

                        <input type="hidden" name="token" value="{{ $token }}">

                        <div class="form-group{{ $errors->has('email') ? ' has-danger' : '' }}">
                            <label for="email" class="sr-only">E-mail</label>
                            <input id="email" type="text" class="form-control form-control-danger" name="email" value="{{ old('email', $email ?? '') }}" placeholder="E-mail">
                            <div class="form-control-feedback">{{ $errors->first('email') }}</div>
                        </div>

                        <div class="form-group{{ $errors->has('password') ? ' has-danger' : '' }}">
                            <label for="password" class="sr-only">Hasło</label>
                            <input id="password" type="password" class="form-control form-control-danger" name="password" placeholder="Hasło">
                            <div class="form-control-feedback">{{ $errors->first('password') }}</div>
                        </div>

                        <div class="form-group{{ $errors->has('password_confirmation') ? ' has-danger' : '' }}">
                            <label for="password_confirmation" class="sr-only">Potwierdź Hasło</label>
                            <input id="password_confirmation" type="password" class="form-control form-control-danger" name="password_confirmation" placeholder="Potwierdź Hasło">
                            <div class="form-control-feedback">{{ $errors->first('password_confirmation') }}</div>
                        </div>

                        <div class="form-group">
                            <div class="col-md-6 col-md-offset-4">
                                <button type="submit" class="btn btn-primary">
                                    Zapisz
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection
