@extends('products.base')

@section('breadcrumb')
    @parent
    <li class="breadcrumb-item active">Edytuj produkt</li>
@endsection

@section('content')
    <div class="row">
        <div class="col-md-8 col-md-offset-2">
            <form class="form-horizontal" role="form" action="{{ route('product.update', compact('product')) }}" method="POST">
                {{ csrf_field() }}

                <div class="form-group{{ $errors->has('name') ? ' has-danger' : '' }}">
                    <label for="name" class="sr-only">Nazwa produktu</label>
                    <input id="name" type="text" class="form-control form-control-danger" placeholder="Nazwa produktu" name="name" value="{{ old('name', $product->name) }}" required autofocus>
                    <div class="form-control-feedback">{{ $errors->first('name') }}</div>
                </div>

                <div class="form-group{{ $errors->has('measure_unit') ? ' has-danger' : '' }}">
                    <label for="measure_unit" class="sr-only">Jednostka miary</label>
                    <input id="measure_unit" type="text" class="form-control form-control-danger" placeholder="Jednostka miary" name="measure_unit" value="{{ old('measure_unit', $product->measure_unit) }}">
                    <div class="form-control-feedback">{{ $errors->first('measure_unit') }}</div>
                </div>

                <div class="form-group{{ $errors->has('price') ? ' has-danger' : '' }}">
                    <label for="price" class="sr-only">Cena</label>
                    <input id="price" type="text" class="form-control form-control-danger" placeholder="Cena" name="price" value="{{ old('price', $product->price) }}" required>
                    <div class="form-control-feedback">{{ $errors->first('price') }}</div>
                </div>

                <div class="form-group{{ $errors->has('vat') ? ' has-danger' : '' }}">
                    <label for="vat" class="sr-only">Vat</label>
                    <input id="vat" type="text" class="form-control form-control-danger" placeholder="Vat" name="vat" value="{{ old('vat', $product->vat) }}" required>
                    <div class="form-control-feedback">{{ $errors->first('vat') }}</div>
                </div>

                <button type="submit" class="btn btn-primary">
                    Zapisz zmiany
                </button>
            </form>
        </div>
    </div>
@endsection
