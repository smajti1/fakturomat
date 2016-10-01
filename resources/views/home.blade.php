@extends('layouts.app')

@section('content')
    <div class="row">
        <div class="col-md-8 col-md-offset-2">
            <div class="panel panel-default">
                <div class="panel-heading">Witaj {{ Auth::user()->email }}</div>

                <div class="panel-body">
                    <div>
                        <h1>Twoje firmy</h1>
                        <ol>
                            @foreach(Auth::user()->companies as $company)
                                <li>{{ $company->name }}</li>
                            @endforeach
                        </ol>
                    </div>
                    <div class="col-md-3">
                        <a href="{{ route('company.index') }}">Lista Firm</a>
                        <a href="{{ route('company.create') }}">Dodaj Firmę</a>
                    </div>

                    <div class="col-md-3">
                        <a href="{{ route('buyer.index') }}">Lista Kontrachentów</a>
                        <a href="{{ route('buyer.create') }}">Dodaj Kontrachenta</a>
                    </div>

                    <div class="col-md-3">
                        <a href="{{ route('invoices.index') }}">Lista Faktur</a>
                        <a href="{{ route('invoices.create') }}">Dodaj Fakturę</a>
                    </div>

                    <div class="col-md-3">
                        <a href="{{ route('product.index') }}">Lista Produktów</a>
                        <a href="{{ route('product.create') }}">Dodaj Produkt</a>
                    </div>

                </div>
            </div>
        </div>
    </div>
@endsection
