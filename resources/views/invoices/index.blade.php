@extends('invoices.base')

@section('content')
    <h1>Faktury</h1>
    <div>
        <a href="{{ route('invoices.create') }}">Dodaj <i class="fa fa-plus"></i></a>
    </div>
    <table class="table table-bordered table-striped">
        <thead>
        <tr>
            <th>Numer</th>
            <th>Status</th>
            <th>Nabywca</th>
            <th>Płatność</th>
            <th>Kwota</th>
            <th>Ilość produktów</th>
            <th>payment_at</th>
            <th>issue_date</th>
            <th></th>
        </tr>
        </thead>
        <tbody>
        @foreach($invoices as $invoice)
            <tr>
                <td>{{ $invoice->number }}</td>
                <td>{{ $invoice->status }}</td>
                <td>{{ $invoice->buyer['name'] }}</td>
                <td>{{ $invoice->payment }}</td>
                <td>{{ $invoice->price }} zł</td>
                <td>{{ $invoice->invoice_products->count() }}</td>
                <td>{{ $invoice->payment_at }}</td>
                <td>{{ $invoice->issue_date }}</td>
                <td>
                    <a href="{{ route('invoices.edit', compact('invoice')) }}" class="inline-block">
                        <i class="fa fa-pencil-square-o"></i>
                    </a>

                    <form action="{{ route('invoices.destroy', compact('invoice')) }}" method="POST" class="inline-block">
                        {{ csrf_field() }}
                        <input type="hidden" name="_method" value="DELETE">
                        <button type="submit" class="btn-like-link">
                            <i class="fa fa-trash fa-color-hover" aria-hidden="true"></i>
                        </button>
                    </form>

                    <a href="{{ route('invoices.to.pdf', compact('invoice')) }}" class="inline-block">
                        <i class="fa fa-print"></i>
                    </a>
                </td>
            </tr>
        @endforeach
        </tbody>
    </table>
@endsection
