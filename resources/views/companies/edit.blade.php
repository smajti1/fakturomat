@extends('companies.base')

@section('breadcrumb')
    @parent
    <li class="breadcrumb-item active">Edytuj firmę</li>
@endsection

@section('content')
    <div class="row">
        <div class="col-md-8 col-md-offset-2">
            <form class="form-horizontal" role="form" action="{{ route('company.update') }}" method="POST">
                {{ csrf_field() }}

                <div class="mb-3{{ $errors->has('name') ? ' has-danger' : '' }}">
                    <label for="name">Nazwa firmy</label>
                    <input id="name" type="text" class="form-control form-control-danger" placeholder="Nazwa firmy"
                           name="name" value="{{ old('name', $company->name) }}" required autofocus>
                    <div class="form-control-feedback">{{ $errors->first('name') }}</div>
                </div>

                @include('helpers.google_address', [
                    'address_string' => old('address_string'),
                    'city' => old('city', $company->city),
                    'zip_code' => old('zip_code', $company->zip_code),
                    'street' => old('street', $company->street),
                ])

                <div class="mb-3{{ $errors->has('tax_id_number') ? ' has-danger' : '' }}">
                    <label for="tax_id_number">Nip</label>
                    <input id="tax_id_number" type="text" class="form-control form-control-danger" name="tax_id_number"
                           value="{{ old('tax_id_number', $company->tax_id_number) }}" placeholder="nip">
                    <div class="form-control-feedback">{{ $errors->first('tax_id_number') }}</div>
                </div>
                <div class="mb-3{{ $errors->has('regon') ? ' has-danger' : '' }}">
                    <label for="regon">Regon</label>
                    <input id="regon" type="text" class="form-control form-control-danger" name="regon"
                           value="{{ old('regon', $company->regon) }}" placeholder="Regon">
                    <div class="form-control-feedback">{{ $errors->first('regon') }}</div>
                </div>
                <div class="mb-3{{ $errors->has('email') ? ' has-danger' : '' }}">
                    <label for="email">E-mail firmowy</label>
                    <input id="email" type="text" class="form-control form-control-danger" name="email"
                           value="{{ old('email', $company->email) }}" placeholder="E-mail firmowy">
                    <div class="form-control-feedback">{{ $errors->first('email') }}</div>
                </div>
                <div class="mb-3{{ $errors->has('website') ? ' has-danger' : '' }}">
                    <label for="website">Strona www</label>
                    <input id="website" type="text" class="form-control form-control-danger" name="website"
                           value="{{ old('website', $company->website) }}" placeholder="Strona www">
                    <div class="form-control-feedback">{{ $errors->first('website') }}</div>
                </div>
                <div class="mb-3{{ $errors->has('phone') ? ' has-danger' : '' }}">
                    <label for="phone">Telefon</label>
                    <input id="phone" type="text" class="form-control form-control-danger" name="phone"
                           value="{{ old('phone', $company->phone) }}" placeholder="Telefon">
                    <div class="form-control-feedback">{{ $errors->first('phone') }}</div>
                </div>
                <div class="mb-3{{ $errors->has('bank_name') ? ' has-danger' : '' }}">
                    <label for="bank_name">Nazwa banku</label>
                    <input id="bank_name" type="text" class="form-control form-control-danger" name="bank_name"
                           value="{{ old('bank_name', $company->bank_name) }}" placeholder="Nazwa banku">
                    <div class="form-control-feedback">{{ $errors->first('form-control-danger') }}</div>
                </div>
                <div class="mb-3{{ $errors->has('bank_account') ? ' has-danger' : '' }}">
                    <label for="bank_account">Konto bankowe</label>
                    <input id="bank_account" type="text" class="form-control form-control-danger" name="bank_account"
                           value="{{ old('bank_account', $company->bank_account) }}" placeholder="Konto bankowe">
                    <div class="form-control-feedback">{{ $errors->first('form-control-danger') }}</div>
                </div>

                <button type="submit" class="btn btn-primary">
                    Zapisz zmiany
                </button>
            </form>
        </div>
    </div>
@endsection
