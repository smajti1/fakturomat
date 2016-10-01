@extends('companies.base')

@section('content')
    <form class="form-horizontal" role="form" method="POST" action="{{ route('company.store') }}">
        {{ csrf_field() }}
        <input type="hidden" name="_method" value="PUT">

        <div class="form-group{{ $errors->has('company_name') ? ' has-danger' : '' }}">
            <label for="company_name" class="sr-only">Nazwa firmy</label>
            <input id="company_name" type="text" class="form-control form-control-danger" placeholder="Nazwa firmy" name="company_name" value="{{ old('company_name') }}" required autofocus>
            <div class="form-control-feedback">{{ $errors->first('company_name') }}</div>
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
        <div class="form-group{{ $errors->has('company_email') ? ' has-danger' : '' }}">
            <label for="company_email" class="sr-only">E-mail firmowy</label>
            <input id="company_email" type="text" class="form-control form-control-danger" name="company_email" value="{{ old('company_email') }}" placeholder="E-mail firmowy">
            <div class="form-control-feedback">{{ $errors->first('company_email') }}</div>
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
        <div class="form-group{{ $errors->has('bank_account_number') ? ' has-danger' : '' }}">
            <label for="bank_account_number" class="sr-only">Nr konta bankowego</label>
            <input id="bank_account_number" type="text" class="form-control form-control-danger" name="bank_account_number" value="{{ old('bank_account_number') }}" placeholder="Nr konta bankowego">
            <div class="form-control-feedback">{{ $errors->first('bank_account_number') }}</div>
        </div>

        <button type="submit" class="btn btn-primary">
            Utwórz konto
        </button>
    </form>
@endsection