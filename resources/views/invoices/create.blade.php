@extends('invoices.base')

@section('breadcrumb')
    @parent
    <li class="breadcrumb-item active">Dodaj fakturę</li>
@endsection

@section('content')
    <form class="form-horizontal" role="form" action="{{ route('invoices.store') }}" method="POST">
        <input type="hidden" name="_method" value="PUT">
        {{ csrf_field() }}
        
        <div>
            <label for="number">Numer faktury</label>
            <input id="number" name="number" type="text">
        </div>
        
        <div class="row">
            <div class="col-sm-6 company">
                <strong>Sprzedawca</strong>
                <div id="company-name-placeholder">
                    {{ $companies->first()->name }}
                </div>
                <div id="company-address-placeholder">
                    {{ $companies->first()->address }}
                </div>

                <label for="company_id">Zmień firmę</label>
                <select name="company_id" id="company_id">
                    @foreach($companies as $company)
                        <option value="{{ $company->id }}"
                            data-data="{{ json_encode($company->toArray()) }}"
                            {{ $companies->first()->id === $company->id ? 'selected' : '' }}
                        >
                            {{ $company->name }}
                        </option>
                    @endforeach
                </select>
            </div>
            <div class="col-sm-6">
                <strong>Nabywca</strong>
                <div id="buyer-name-placeholder">
                    {{ $buyers->first()->name }}
                </div>
                <div id="buyer-address-placeholder">
                    {{ $buyers->first()->address }}
                </div>

                <label for="buyer_id">Zmień firmę</label>
                <select name="buyer_id" id="buyer_id">
                    @foreach($buyers as $buyer)
                        <option value="{{ $buyer->id }}"
                                data-data="{{ json_encode($buyer->toArray()) }}"
                                {{ $buyers->first()->id === $buyer->id ? 'selected' : '' }}
                        >
                            {{ $buyer->name }}
                        </option>
                    @endforeach
                </select>
            </div>
        </div>
        <table id="invoice-product-list" class="table table-bordered table-responsive">
            <thead>
                <tr>
                    <th>Lp</th>
                    <th>Nazwa towaru/usługi</th>
                    <th>Jedn.m.</th>
                    <th>Ilość</th>
                    <th>Cena netto</th>
                    <th>Kwota netto</th>
                    <th>VAT</th>
                    <th>Kwota VAT</th>
                    <th>Kwota brutto</th>
                </tr>
            </thead>
            <tbody>
                <tr id="product-placeholder">
                    <td></td>
                    <td>
                        <input id="select-product" placeholder="Wyszukaj lub dodaj produkt">
                    </td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                </tr>
            </tbody>
        </table>

        @if($errors->has('product'))
            <span>{{ $errors->first('product') }}</span>
        @endif

        <div>
            <label for="issue_date">Data wystawienia</label>
            <input type="date" name="issue_date" id="issue_date" value="{{ date("Y-m-d") }}">
        </div>

        <div>
            <label for="payment_at">Data płatności</label>
            <input type="date" name="payment_at" id="payment_at" value="{{ date("Y-m-d", strtotime("+1 week")) }}">
        </div>

        <div>
            <label for="payment">Płatność</label>
            <select name="payment" id="payment">
                <option value="{{ \App\Invoice::PAYMENT_CASH }}">gotówką</option>
                <option value="{{ \App\Invoice::PAYMENT_BANK_TRANSFER }}">przelewem</option>
            </select>
        </div>

        <div>
            <label for="status">Status</label>
            <select name="status" id="status">
                <option value="{{ \App\Invoice::STATUS_NOT_PAID }}">nie zapłacona</option>
                <option value="{{ \App\Invoice::STATUS_PAID }}">zapłacona</option>
            </select>
        </div>

        <input type="hidden" name="product-list-src" class="form-control" value="{{ route('product.json.list') }}">

        <button type="submit" class="btn btn-primary">
            Zapisz fakturę
        </button>
    </form>
@endsection

@section('scripts')
    @parent
    <script>
        var selectProductList;

        $('#company_id').selectize({
            onChange : function(item) {
                $('#company-name-placeholder').html(this.options[item].name);
                $('#company-address-placeholder').html(this.options[item].address);
            }
        });

        $('#buyer_id').selectize({
            onChange : function(item) {
                $('#buyer-name-placeholder').html(this.options[item].name);
                $('#buyer-address-placeholder').html(this.options[item].address);
            }
        });

        function selectLoad(query, callback, url) {
            selectProductList = {};
            if (!query.length) return callback();

            $.get(url, {
                searchText: query,
            }).done(function(response) {
                selectProductList = response;
                callback(response);
            });
        }

        var productListSrc = $('[name=product-list-src]').val();
        var $invoiceProductList = $('#invoice-product-list');

        $('#select-product').selectize({
            valueField: 'id',
            labelField: 'name',
            searchField: ['name'],
            options: [],
            create: false,
            render: {
                option: function(item, escape) {
                    return '<div>' +
                                '<span class="name">' + escape(item.name) + '</span>' +
                                '<span class="small block grey-text">' + item.price + 'zł + ' + item.vat + '%</span>' +
                            '</div>';
                }
            },
            load: function(query, callback) {
                selectLoad(query, callback, productListSrc);
            },
            onChange : function(item) {
                var selectedItem = null;
                for (var i=0; i < selectProductList.length; i++) {
                    if (selectProductList[i].id == item) {
                        selectedItem = selectProductList[i];
                    }
                }

                if (selectedItem) {
                    var products = $invoiceProductList.find('tbody tr');
                    var product = $(
                        '<tr>' +
                            '<td>' + products.length + '</td>' +
                            '<td>' + selectedItem.name + '</td>' +
                            '<td>' + selectedItem.measure_unit + '</td>' +
                            '<td><input type="number" value="1" name="product[' + selectedItem.id + ']" class="price-input"></td>' +
                            '<td>' + selectedItem.price + ' zł</td>' +
                            '<td>' + selectedItem.price + ' zł</td>' +
                            '<td>' + selectedItem.vat + '%</td>' +
                            '<td>' + selectedItem.price * (1 + (selectedItem.vat/100)) + ' zł</td>' +
                            '<td>' + selectedItem.price * (1 + (selectedItem.vat/100)) + ' zł</td>' +
                        '</tr>'
                    );

                    $('#product-placeholder').before(product);
                }
            }
        });
    </script>
@endsection