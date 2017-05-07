@extends('buyers.base')

@section('breadcrumb')
    @parent
    <li class="breadcrumb-item active">Dodaj kontrachenta</li>
@endsection

@section('content')
    <div class="row">
        <div class="col-md-8 col-md-offset-2">
            <form class="form-horizontal" role="form" action="{{ route('buyer.store') }}" method="POST">
                <input type="hidden" name="_method" value="PUT">
                {{ csrf_field() }}

                <div class="form-group{{ $errors->has('name') ? ' has-danger' : '' }}">
                    <label for="name">Nazwa kontrahenta</label>
                    <input id="name" type="text" class="form-control form-control-danger" placeholder="Nazwa kontrahenta" name="name" value="{{ old('name') }}" required autofocus>
                    <div class="form-control-feedback">{{ $errors->first('name') }}</div>
                </div>

                @include('helpers.google_address')

                <div class="form-group{{ $errors->has('tax_id_number') ? ' has-danger' : '' }}">
                    <label for="tax_id_number">Nip</label>
                    <input id="tax_id_number" type="text" class="form-control form-control-danger" placeholder="Nip" name="tax_id_number" value="{{ old('tax_id_number') }}">
                    <div class="form-control-feedback">{{ $errors->first('tax_id_number') }}</div>
                </div>

                <div class="form-group{{ $errors->has('regon') ? ' has-danger' : '' }}">
                    <label for="regon">Regon</label>
                    <input id="regon" type="text" class="form-control form-control-danger" placeholder="Regon" name="regon" value="{{ old('regon') }}">
                    <div class="form-control-feedback">{{ $errors->first('regon') }}</div>
                </div>

                <div class="form-group{{ $errors->has('email') ? ' has-danger' : '' }}">
                    <label for="email">E-mail</label>
                    <input id="email" type="text" class="form-control form-control-danger" placeholder="E-mail" name="email" value="{{ old('email') }}">
                    <div class="form-control-feedback">{{ $errors->first('email') }}</div>
                </div>
                
                <div class="form-group{{ $errors->has('website') ? ' has-danger' : '' }}">
                    <label for="website">Strona www</label>
                    <input id="website" type="text" class="form-control form-control-danger" placeholder="Strona www" name="website" value="{{ old('website') }}">
                    <div class="form-control-feedback">{{ $errors->first('website') }}</div>
                </div>

                <div class="form-group{{ $errors->has('phone') ? ' has-danger' : '' }}">
                    <label for="phone">Telefon</label>
                    <input id="phone" type="text" class="form-control form-control-danger" placeholder="Telefon" name="phone" value="{{ old('phone') }}">
                    <div class="form-control-feedback">{{ $errors->first('phone') }}</div>
                </div>
                
                <button type="submit" class="btn btn-primary">
                    Dodaj kontrahenta
                </button>
            </form>
        </div>
    </div>
@endsection
