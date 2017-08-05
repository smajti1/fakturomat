@extends('products.base')

@section('breadcrumb')
    @parent
    <li class="breadcrumb-item active">Dodaj produkt</li>
@endsection

@section('content')
    <div class="row">
        <div class="col-md-8 col-md-offset-2">
            <form class="form-horizontal" action="{{ route('product.store') }}" method="POST">
                <input type="hidden" name="_method" value="PUT">
                {{ csrf_field() }}

                <div class="form-group{{ $errors->has('name') ? ' has-danger' : '' }}">
                    <label for="name">Nazwa produktu</label>
                    <input id="name" class="form-control form-control-danger" placeholder="Nazwa produktu" name="name" value="{{ old('name') }}" required autofocus>
                    <div class="form-control-feedback">{{ $errors->first('name') }}</div>
                </div>

                <div class="form-group{{ $errors->has('measure_unit') ? ' has-danger' : '' }}">
                    <label for="measure_unit">Jednostka miary</label>
                    <select name="measure_unit" id="measure_unit" class="form-control form-control-danger">
                        @foreach($measureUnits as $key => $unit)
                            <option value="{{ $key }}"{{ old('measure_unit') === $key ? 'selected' : '' }}>{{ $unit }}</option>
                        @endforeach
                    </select>
                    <div class="form-control-feedback">{{ $errors->first('measure_unit') }}</div>
                </div>

                <div class="form-group{{ $errors->has('price') ? ' has-danger' : '' }}">
                    <div class="row">
                        <div class="col-6">
                            <label for="price">Cena netto</label>
                            <input id="price" class="form-control form-control-danger" placeholder="Cena netto" name="price" value="{{ old('price') }}" required>
                            <div class="form-control-feedback">{{ $errors->first('price') }}</div>
                        </div>
                        <div class="col-6">
                            <label for="price_gross">Cena brutto</label>
                            <input id="price_gross" class="form-control form-control-danger" placeholder="Cena brutto" disabled>
                        </div>
                    </div>
                </div>

                <div class="form-group{{ $errors->has('tax_percent') ? ' has-danger' : '' }}">
                    <label for="tax_percent">Vat</label>
                    <select name="tax_percent" id="tax_percent" class="form-control form-control-danger">
                    @foreach($activeTaxes as $tax)
                        <option value="{{ $tax['id'] }}"{{ old('tax_percent') === $tax['id'] ? 'selected' : '' }}>{{ $tax['label'] }}</option>
                    @endforeach
                    </select>
                    <div class="form-control-feedback">{{ $errors->first('tax_percent') }}</div>
                </div>

                <button class="btn btn-primary">
                    Dodaj produkt
                </button>
            </form>
        </div>
    </div>
@endsection

@include('products.scripts')
