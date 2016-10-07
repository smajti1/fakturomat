@extends('companies.base')

@section('content')
    <form class="form-horizontal" role="form" method="POST" action="{{ route('company.store') }}">
        {{ csrf_field() }}
        <input type="hidden" name="_method" value="PUT">

        <div class="form-group{{ $errors->has('name') ? ' has-danger' : '' }}">
            <label for="name" class="sr-only">Nazwa firmy</label>
            <input id="name" type="text" class="form-control form-control-danger" placeholder="Nazwa firmy" name="name" value="{{ old('name') }}" required autofocus>
            <div class="form-control-feedback">{{ $errors->first('name') }}</div>
        </div>

        <div class="form-group{{ $errors->has('address') ? ' has-danger' : '' }}">
            <label for="address" class="sr-only">Adres</label>
            <input id="address" type="text" class="form-control form-control-danger" name="address" value="{{ old('address') }}" placeholder="Adres">
            <div class="form-control-feedback">{{ $errors->first('address') }}</div>
        </div>
        <div class="form-group{{ $errors->has('tax_id_number') ? ' has-danger' : '' }}">
            <label for="tax_id_number" class="sr-only">nip</label>
            <input id="tax_id_number" type="text" class="form-control form-control-danger" name="tax_id_number" value="{{ old('tax_id_number') }}" placeholder="nip">
            <div class="form-control-feedback">{{ $errors->first('tax_id_number') }}</div>
        </div>
        <div class="form-group{{ $errors->has('regon') ? ' has-danger' : '' }}">
            <label for="regon" class="sr-only">Regon</label>
            <input id="regon" type="text" class="form-control form-control-danger" name="regon" value="{{ old('regon') }}" placeholder="Regon">
            <div class="form-control-feedback">{{ $errors->first('regon') }}</div>
        </div>
        <div class="form-group{{ $errors->has('email') ? ' has-danger' : '' }}">
            <label for="email" class="sr-only">E-mail firmowy</label>
            <input id="email" type="text" class="form-control form-control-danger" name="email" value="{{ old('email') }}" placeholder="E-mail firmowy">
            <div class="form-control-feedback">{{ $errors->first('email') }}</div>
        </div>
        <div class="form-group{{ $errors->has('www') ? ' has-danger' : '' }}">
            <label for="www" class="sr-only">Strona www</label>
            <input id="www" type="text" class="form-control form-control-danger" name="www" value="{{ old('www') }}" placeholder="Strona www">
            <div class="form-control-feedback">{{ $errors->first('www') }}</div>
        </div>
        <div class="form-group{{ $errors->has('phone') ? ' has-danger' : '' }}">
            <label for="phone" class="sr-only">Telefon</label>
            <input id="phone" type="text" class="form-control form-control-danger" name="phone" value="{{ old('phone') }}" placeholder="Telefon">
            <div class="form-control-feedback">{{ $errors->first('phone') }}</div>
        </div>
        <div class="form-group{{ $errors->has('bank_account') ? ' has-danger' : '' }}">
            <label for="bank_account" class="sr-only">Konto bankowe</label>
            <input id="bank_account" type="text" class="form-control form-control-danger" name="bank_account" value="{{ old('bank_account') }}" placeholder="Konto bankowe">
            <div class="form-control-feedback">{{ $errors->first('bank_account') }}</div>
        </div>

        <button type="submit" class="btn btn-primary">
            Utwórz konto
        </button>
    </form>
@endsection