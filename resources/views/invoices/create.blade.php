@extends('invoices.base')

@section('breadcrumb')
    @parent
    <li class="breadcrumb-item active">Dodaj fakturę</li>
@endsection

@section('content')
    <div class="row">
        <div class="col-md-8 col-md-offset-2">
            <form class="form-horizontal" role="form" action="{{ route('invoices.store') }}" method="POST">
                <input type="hidden" name="_method" value="PUT">
                {{ csrf_field() }}

                <div class="form-group{{ $errors->has('pkwiu') ? ' has-danger' : '' }}">
                    <label for="pkwiu" class="sr-only">Pkwiu</label>
                    <input id="pkwiu" type="text" class="form-control form-control-danger" placeholder="Pkwiu" name="pkwiu" value="{{ old('pkwiu') }}">
                    <div class="form-control-feedback">{{ $errors->first('pkwiu') }}</div>
                </div>

                <div class="form-group{{ $errors->has('measure_unit') ? ' has-danger' : '' }}">
                    <label for="measure_unit" class="sr-only">Jednostka miary</label>
                    <input id="measure_unit" type="text" class="form-control form-control-danger" placeholder="Jednostka miary" name="measure_unit" value="{{ old('measure_unit') }}">
                    <div class="form-control-feedback">{{ $errors->first('measure_unit') }}</div>
                </div>

                <div class="form-group{{ $errors->has('price') ? ' has-danger' : '' }}">
                    <label for="price" class="sr-only">Cena</label>
                    <input id="price" type="text" class="form-control form-control-danger" placeholder="Cena" name="price" value="{{ old('price') }}" required>
                    <div class="form-control-feedback">{{ $errors->first('price') }}</div>
                </div>

                <div class="form-group{{ $errors->has('vat') ? ' has-danger' : '' }}">
                    <label for="vat" class="sr-only">Vat</label>
                    <input id="vat" type="text" class="form-control form-control-danger" placeholder="Vat" name="vat" value="{{ old('vat') }}" required>
                    <div class="form-control-feedback">{{ $errors->first('vat') }}</div>
                </div>

                <button type="submit" class="btn btn-primary">
                    Zapisz fakturę
                </button>
            </form>
        </div>
    </div>
@endsection
