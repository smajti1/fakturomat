@extends('companies.base')

@section('content')
    <h1>Twoje Firmy</h1>
    <table class="table table-bordered table-striped">
        <thead>
        <tr>
            <th>Nazwa</th>
            <th></th>
        </tr>
        </thead>
        <tbody>
        @foreach($companies as $company)
            <tr>
                <td>{{ $company->name }}</td>
                <td>

                </td>
            </tr>
        @endforeach
        </tbody>
    </table>
@endsection
