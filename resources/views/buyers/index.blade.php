@extends('buyers.base')

@section('content')
    <h1>Kontrahenci</h1>
    <div>
        <a href="{{ route('buyer.create') }}">Dodaj <i class="fa fa-plus"></i></a>
    </div>
    <div class="table-responsive">
        <table class="table table-bordered table-striped">
            <thead>
            <tr>
                <th>Nazwa</th>
                <th>Adres</th>
                <th>Nip</th>
                <th>Regon</th>
                <th class="d-none d-lg-table-cell d-xl-table-cell">E-mail</th>
                <th class="d-none d-md-table-cell d-lg-table-cell d-xl-table-cell">Strona www</th>
                <th class="d-none d-lg-table-cell d-xl-table-cell">Telefon</th>
                <th></th>
            </tr>
            </thead>
            <tbody>
            @foreach(Auth::user()->buyers as $buyer)
                <tr>
                    <td><a href="{{ route('buyer.edit', compact('buyer')) }}">{{ $buyer->name }}</a></td>
                    <td>
                        @foreach($buyer->getAddress() as $address)
                            <div>{{ $address }}</div>
                        @endforeach
                    </td>
                    <td>{{ $buyer->tax_id_number }}</td>
                    <td>{{ $buyer->regon }}</td>
                    <td class="d-none d-lg-table-cell d-xl-table-cell">{{ $buyer->email }}</td>
                    <td class="d-none d-md-table-cell d-lg-table-cell d-xl-table-cell">{{ $buyer->website }}</td>
                    <td class="d-none d-lg-table-cell d-xl-table-cell">{{ $buyer->phone }}</td>
                    <td>
                        <a href="{{ route('buyer.edit', compact('buyer')) }}" class="inline-block">
                            <i class="fa fa-pencil-square-o"></i>
                        </a>
                        <form action="{{ route('buyer.destroy', compact('buyer')) }}" method="POST" class="inline-block">
                            {{ csrf_field() }}
                            <input type="hidden" name="_method" value="DELETE">
                            <button type="submit" class="btn-like-link" onclick="return confirm('Jesteś pewien, że chcesz usunąć kontrachenta [{{ $buyer->name }}]?');">
                                <i class="fa fa-trash-o color-danger" aria-hidden="true"></i>
                            </button>
                        </form>
                    </td>
                </tr>
            @endforeach
            </tbody>
        </table>
    </div>
@endsection
