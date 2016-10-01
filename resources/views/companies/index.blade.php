@extends('companies.base')

@section('content')
    <h1>Twoje Firmy</h1>
    <table class="table table-bordered table-striped">
        <thead>
        <tr>
            <th>Nazwa</th>
            <th>Adres</th>
            <th>Nip</th>
            <th>Regon</th>
            <th>Email</th>
            <th>Strona www</th>
            <th>Telefon</th>
            <th>Nr konta bankowego</th>
        </tr>
        </thead>
        <tbody>
        @foreach($companies as $company)
            <tr>
                <td>{{ $company->name }}</td>
                <td>{{ $company->address }}</td>
                <td>{{ $company->tax_id_number }}</td>
                <td>{{ $company->regon }}</td>
                <td>{{ $company->email }}</td>
                <td>{{ $company->www }}</td>
                <td>{{ $company->phone }}</td>
                <td>{{ $company->bank_account_number }}</td>
            </tr>
        @endforeach
        </tbody>
    </table>
@endsection
