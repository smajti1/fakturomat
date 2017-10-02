@extends('products.base')

@section('content')
    <h1>Produkty</h1>
    <div>
        <a href="{{ route('product.create') }}">Dodaj <i class="fa fa-plus"></i></a>
    </div>
    <div class="table-responsive">
        <table class="table table-bordered table-striped">
            <thead>
            <tr>
                <th>Nazwa</th>
                <th>Jednostka miary</th>
                <th>Cena</th>
                <th>Vat</th>
                <th>Cena z vatem</th>
                <th></th>
            </tr>
            </thead>
            <tbody>
            @foreach(Auth::user()->products as $product)
                <tr>
                    <td>{{ $product->name }}</td>
                    <td>{{ $product->measure_unit }}</td>
                    <td>{{ money_pl_format($product->price) }} zł</td>
                    <td>{{ $activeTaxes[$product->tax_percent]['label'] }}</td>
                    <td>{{ money_pl_format($product->formattedPriceWithVat()) }} zł</td>
                    <td>
                        <a href="{{ route('product.edit', compact('product')) }}" class="inline-block">
                            <i class="fa fa-pencil-square-o"></i>
                        </a>
                        <form action="{{ route('product.destroy', compact('product')) }}" method="POST" class="inline-block">
                            {{ csrf_field() }}
                            <input type="hidden" name="_method" value="DELETE">
                            <button type="submit" class="btn-like-link">
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
