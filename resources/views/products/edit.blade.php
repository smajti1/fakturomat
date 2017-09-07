@extends('products.base')

@section('breadcrumb')
    @parent
    <li class="breadcrumb-item active">Edytuj produkt</li>
@endsection

@section('content')
    <div class="row">
        <div class="col-md-8 col-md-offset-2">
            <form class="form-horizontal" action="{{ route('product.update', compact('product')) }}" method="POST">
                {{ csrf_field() }}

                <div class="form-group{{ $errors->has('name') ? ' has-danger' : '' }}">
                    <label for="name">Nazwa produktu</label>
                    <input id="name" class="form-control form-control-danger" placeholder="Nazwa produktu" name="name" value="{{ old('name', $product->name) }}" required autofocus>
                    <div class="form-control-feedback">{{ $errors->first('name') }}</div>
                </div>

                <div class="form-group{{ $errors->has('measure_unit') ? ' has-danger' : '' }}">
                    <label for="measure_unit">Jednostka miary</label>
                    <input id="measure_unit" class="form-control form-control-danger" placeholder="Jednostka miary" name="measure_unit" value="{{ old('measure_unit', $product->measure_unit) }}">
                    <div class="form-control-feedback">{{ $errors->first('measure_unit') }}</div>
                </div>

                <div class="form-group{{ $errors->has('price') ? ' has-danger' : '' }}">
                    <div class="row">
                        <div class="col-6">
                            <label for="price">Cena netto</label>
                            <input id="price" class="form-control form-control-danger" placeholder="Cena netto" name="price" value="{{ old('price', $product->price) }}" required>
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
                            <option value="{{ $tax['id'] }}"{{ old('tax_percent', $product->tax_percent) === $tax['id'] ? 'selected' : '' }}>{{ $tax['label'] }}</option>
                        @endforeach
                    </select>
                    <div class="form-control-feedback">{{ $errors->first('tax_percent') }}</div>
                </div>

                <button class="btn btn-primary">
                    Zapisz zmiany
                </button>
            </form>
        </div>
    </div>
@endsection

@include('products.scripts')