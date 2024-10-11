@php use App\Models\Invoice; @endphp
@extends('invoices.base')

@section('breadcrumb')
    @parent
    <li class="breadcrumb-item active">Edytuj fakturę</li>
@endsection

@section('content')
    <form class="form-horizontal" role="form" action="{{ route('invoices.update', $invoice) }}" method="POST">
        {{ csrf_field() }}

        <div class="{{ $errors->has('number') ? ' has-danger' : '' }}">
            <label for="number">Numer faktury</label>
            <input id="number" name="number" placeholder="Auto" value="{{ $invoice->number }}">
            <div class="form-control-feedback">{{ $errors->first('number') }}</div>
        </div>

        <div class="row">
            <div class="col-sm-6 company">
                <strong>Sprzedawca</strong>
                <div id="company-name-placeholder">
                    {{ $company->name }}
                </div>
                <div id="company-address-placeholder">
                    {{ $company->address }}
                </div>
            </div>
            <div class="col-sm-6">
                <label for="buyer_id">Nabywca</label>
                <br/>
                <select name="buyer_id" id="buyer_id" class="form-select">
                    @foreach($buyers as $buyer)
                        <option value="{{ $buyer->id }}" data-buyer-data="{{ json_encode($buyer->toArray(), JSON_THROW_ON_ERROR) }}">
                            {{ $buyer->name }}
                        </option>
                    @endforeach
                </select>
                <div id="buyer-address-placeholder">
                    {{ $buyers->first()->getAddressString() }}
                </div>
            </div>
        </div>
        @include('invoices.product-list')

        <div>
            <label for="issue_date">Data wystawienia</label>
            <input type="date" name="issue_date" id="issue_date" value="{{ $invoice->issue_date }}">
        </div>

        <div>
            <label for="payment_at">Data płatności</label>
            <input type="date" name="payment_at" id="payment_at" value="{{ $invoice->payment_at }}">
        </div>

        <div>
            <label for="payment">Płatność</label>
            <select name="payment" id="payment">
                <option value="{{ Invoice::PAYMENT_CASH }}" {{ $invoice->payment === Invoice::PAYMENT_CASH ? 'selected' : '' }}>gotówką</option>
                <option value="{{ Invoice::PAYMENT_BANK_TRANSFER }}" {{ $invoice->payment === Invoice::PAYMENT_BANK_TRANSFER ? 'selected' : '' }}>przelewem</option>
            </select>
        </div>

        <div>
            <label for="status">Status</label>
            <select name="status" id="status">
                <option value="{{ Invoice::STATUS_NOT_PAID }}" {{ $invoice->status === Invoice::STATUS_NOT_PAID ? 'selected' : '' }}>nie zapłacona</option>
                <option value="{{ Invoice::STATUS_PAID }}" {{ $invoice->status === Invoice::STATUS_PAID ? 'selected' : '' }}>zapłacona</option>
            </select>
        </div>

        <input type="hidden" name="product-list-src" class="form-control" value="{{ route('product.json.list') }}">

        <button type="submit" class="btn btn-primary">
            Zapisz zmiany
        </button>
    </form>
@endsection

@section('scripts')
    @parent
    <script>
        $('#buyer_id').select2()
            .on("select2:select", function (e) {
                const buyerData = $(e.params.data.element).data('buyerData');
                $('#buyer-address-placeholder').html([buyerData.street, buyerData.city, buyerData.zip_code].join(', '));
            });
    </script>
@endsection