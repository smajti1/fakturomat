@extends('import.base')

@section('content')
    <h1>Import</h1>
    <div>
        <p>Import towarów oraz klientów z programu <span>"Mega faktura v4"</span></p>

        <form class="form-horizontal" action="{{ route('import.from_mega_faktura') }}" method="POST" enctype="multipart/form-data">
            <input type="hidden" name="_method" value="POST">
            {{ csrf_field() }}

            <div class="{{ $errors->has('products') ? ' has-danger' : '' }}">
                <label for="products">Towary csv</label>
                <input class="form-control" id="products" name="products" type="file" accept=".csv">
                <div class="form-control-feedback">{{ $errors->first('products') }}</div>
                <small class="form-text text-muted">towar.csv</small>
            </div>

            <div class="{{ $errors->has('buyers') ? ' has-danger' : '' }}">
                <label for="buyers">Klienci csv</label>
                <input class="form-control" id="buyers" name="buyers" type="file" accept=".csv">
                <div class="form-control-feedback">{{ $errors->first('buyers') }}</div>
                <small class="form-text text-muted">klienci.csv</small>
            </div>

            <button type="submit" class="btn btn-primary">
                Importuj
            </button>
        </form>
    </div>
@endsection
