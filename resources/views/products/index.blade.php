@extends('products.base')

@section('content')
    <h1>Produkty</h1>
    <table class="table table-bordered table-striped">
        <thead>
            <tr>
                <th>Nazwa</th>
                <th>Pkwiu</th>
                <th>Jednostka miary</th>
                <th>Cena</th>
                <th>Vat</th>
                <th></th>
            </tr>
        </thead>
        <tbody>
            @foreach(Auth::user()->products as $product)
                <tr>
                    <td>{{ $product->name }}</td>
                    <td>{{ $product->pkwiu }}</td>
                    <td>{{ $product->measure_unit }}</td>
                    <td>{{ $product->price }}</td>
                    <td>{{ $product->vat }}</td>
                    <td>
                        <form action="{{ route('product.destroy', compact('product')) }}" method="POST">
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