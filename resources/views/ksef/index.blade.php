@extends('ksef.base')

@section('content')
    <h1>Ksef</h1>

    <div class="mb-3">
        <p>
            <strong>NIP:</strong>
            {{ $company->tax_id_number }}
        </p>

        <p>
            <strong>Ksef token:</strong>
            @if($company->ksefToken !== null)
                <span class="text-success">
                    <i class="fa fa-check"></i> Wgrany
                </span>
            @else
                <span class="text-danger">
                    <i class="fa fa-times"></i> Nie wgrany
                </span>
            @endif
        </p>
    </div>

    <div>
        @if($company->ksefToken !== null)
            <a href="{{ route('ksef.create') }}">Edytuj <i class="fa fa-edit"></i></a>
        @else
            <a href="{{ route('ksef.create') }}">Dodaj <i class="fa fa-plus"></i></a>
        @endif
    </div>
@endsection