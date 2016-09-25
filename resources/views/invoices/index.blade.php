@extends('products.base')

@section('content')
    <h1>Faktury</h1>
    <table class="table table-bordered table-striped">
        <thead>
        <tr>
            <th>Numer</th>
            <th>Status</th>
            <th>Nabywca</th>
            <th>Kwota</th>
            <th></th>
        </tr>
        </thead>
        <tbody>
        @foreach($invoices as $invoice)
            <tr>
                <td>{{ $invoice->number }}</td>
                <td>{{ $invoice->status }}</td>
                <td>{{ $invoice->buyer }}</td>
                <td>{{ $invoice->price }}</td>
                <td>
                    <form action="{{ route('invoice.destroy', compact('invoice')) }}" method="POST">
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
