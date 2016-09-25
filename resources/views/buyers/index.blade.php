@extends('buyers.base')

@section('content')
    <h1>Kontrahenci</h1>
    <table class="table table-bordered table-striped">
        <thead>
        <tr>
            <th>Nazwa</th>
            <th>Adres</th>
            <th></th>
        </tr>
        </thead>
        <tbody>
        @foreach(Auth::user()->buyers as $buyer)
            <tr>
                <td>{{ $buyer->name }}</td>
                <td>{{ $buyer->address }}</td>
                <td>
                    <form action="{{ route('buyer.destroy', compact('buyer')) }}" method="POST">
                        {{ csrf_field() }}
                        <input type="hidden" name="_method" value="DELETE">
                        <button type="submit">
                            <i class="fa fa-trash fa-color-hover" aria-hidden="true"></i>
                        </button>
                    </form>
                </td>
            </tr>
        @endforeach
        </tbody>
    </table>
@endsection
