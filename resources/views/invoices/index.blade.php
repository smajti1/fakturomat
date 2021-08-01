@extends('invoices.base')

@section('content')
    <h1>Faktury</h1>
    <div>
        <a href="{{ route('invoices.create') }}">Dodaj <i class="fa fa-plus"></i></a>
    </div>
    <div class="table-responsive">
        <table class="table table-bordered table-striped">
            <thead>
            <tr>
                <th>Nr.</th>
                <th class="d-none d-lg-table-cell d-xl-table-cell">Status</th>
                <th>Nabywca</th>
                <th class="d-none d-md-table-cell d-lg-table-cell d-xl-table-cell">Płatność</th>
                <th>Kwota</th>
                <th class="d-none d-md-table-cell d-lg-table-cell d-xl-table-cell">Ilość produktów</th>
                <th class="d-none d-md-table-cell d-lg-table-cell d-xl-table-cell">Wpłata</th>
                <th>Data wystawienia</th>
                <th></th>
            </tr>
            </thead>
            <tbody>
            @foreach($invoices as $invoice)
                <tr>
                    <td>{{ $invoice->number }}</td>
                    <td class="d-none d-lg-table-cell d-xl-table-cell">{{ $invoice->status }}</td>
                    <td>{{ $invoice->buyer['name'] }}</td>
                    <td class="d-none d-md-table-cell d-lg-table-cell d-xl-table-cell">{{ $invoice->payment }}</td>
                    <td>{{ $invoice->price }} zł</td>
                    <td class="d-none d-md-table-cell d-lg-table-cell d-xl-table-cell">{{ $invoice->invoice_products->count() }}</td>
                    <td class="d-none d-md-table-cell d-lg-table-cell d-xl-table-cell">{{ $invoice->payment_at }}</td>
                    <td>{{ $invoice->issue_date }}</td>
                    <td>
                        <a href="{{ route('invoices.edit', compact('invoice')) }}" class="inline-block">
                            <i class="fa fa-pencil-square-o"></i>
                        </a>

                        <form action="{{ route('invoices.destroy', compact('invoice')) }}" method="POST" class="inline-block">
                            {{ csrf_field() }}
                            <input type="hidden" name="_method" value="DELETE">
                            <button type="submit" class="btn-like-link" onclick="return confirm('Jesteś pewien, że chcesz usunąć fakture nr [{{ $invoice->number }}]?');">
                                <i class="fa fa-trash-o color-danger" aria-hidden="true"></i>
                            </button>
                        </form>

                        <a href="{{ route('api.invoices.to.pdf', compact('invoice') + ['api_token' => \Illuminate\Support\Facades\Auth::user()->api_token]) }}" class="inline-block" target="_blank">
                            <i class="fa fa-file-pdf-o"></i>
                        </a>
                    </td>
                </tr>
            @endforeach
            </tbody>
        </table>
    </div>
@endsection
