@extends('companies.base')

@section('content')
    <h1>Twoje Firmy</h1>
    <div>
        <a href="{{ route('company.create') }}">Dodaj <i class="fa fa-plus"></i></a>
    </div>
    <div class="table-responsive">
        <table class="table table-bordered table-striped">
            <thead>
            <tr>
                <th>Nazwa</th>
                <th>Adres</th>
                <th>Nip</th>
                <th class="hidden-xs-down">Regon</th>
                <th class="hidden-xs-down">Email</th>
                <th class="hidden-sm-down">Strona www</th>
                <th class="hidden-xs-down">Telefon</th>
                <th class="hidden-md-down">Konto bankowe</th>
                <th></th>
            </tr>
            </thead>
            <tbody>
            @foreach($companies as $company)
                <tr>
                    <td>{{ $company->name }}</td>
                    <td>{{ $company->address }}</td>
                    <td>{{ $company->tax_id_number }}</td>
                    <td class="hidden-xs-down">{{ $company->regon }}</td>
                    <td class="hidden-xs-down">{{ $company->email }}</td>
                    <td class="hidden-sm-down">{{ $company->website }}</td>
                    <td class="hidden-xs-down">{{ $company->phone }}</td>
                    <td class="hidden-md-down">{{ $company->bank_account }}</td>
                    <td>
                        <a href="{{ route('company.edit', compact('company')) }}" class="inline-block">
                            <i class="fa fa-pencil-square-o"></i>
                        </a>
                        <form action="{{ route('company.destroy', compact('company')) }}" method="POST" class="inline-block">
                            {{ csrf_field() }}
                            <input type="hidden" name="_method" value="DELETE">
                            <button type="submit" class="btn-like-link">
                                <i class="fa fa-trash fa-color-hover" aria-hidden="true"></i>
                            </button>
                        </form>
                    </td>
                </tr>
            @endforeach
            </tbody>
        </table>
    </div>
@endsection
