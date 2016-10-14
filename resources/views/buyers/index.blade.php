@extends('buyers.base')

@section('content')
    <h1>Kontrahenci</h1>
    <div>
        <a href="{{ route('buyer.create') }}">Dodaj <i class="fa fa-plus"></i></a>
    </div>
    <table class="table table-bordered table-striped">
        <thead>
        <tr>
            <th>Nazwa</th>
            <th>Adres</th>
            <th>Nip</th>
            <th>Regon</th>
            <th>E-mail</th>
            <th>Strona www</th>
            <th>Telefon</th>
            <th></th>
        </tr>
        </thead>
        <tbody>
        @foreach(Auth::user()->buyers as $buyer)
            <tr>
                <td><a href="{{ route('buyer.edit', compact('buyer')) }}">{{ $buyer->name }}</a></td>
                <td>{{ $buyer->address }}</td>
                <td>{{ $buyer->tax_id_number }}</td>
                <td>{{ $buyer->email }}</td>
                <td>{{ $buyer->www }}</td>
                <td>{{ $buyer->phone }}</td>
                <td>
                    <a href="{{ route('buyer.edit', compact('buyer')) }}" class="inline-block">
                        <i class="fa fa-pencil-square-o"></i>
                    </a>
                    <form action="{{ route('buyer.destroy', compact('buyer')) }}" method="POST" class="inline-block">
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
@endsection
