@extends('invoices.base')

@section('breadcrumb')
    @parent
    <li class="breadcrumb-item active">Dodaj fakturę</li>
@endsection

@section('content')
    <form class="form-horizontal" role="form" action="{{ route('invoices.store') }}" method="POST">
        <input type="hidden" name="_method" value="PUT">
        {{ csrf_field() }}
        <div class="row">
            <div class="col-sm-6 company">
                <strong>Sprzedawca</strong>
                <?php $company = Auth::user()->companies->first(); ?>
                <div class="company-name-placeholder">
                    {{ $company->name }}
                </div>
                <div class="company-address-placeholder">
                    {{ $company->address }}
                </div>
                <input type="text" id="select-company" placeholder="Zmień firmę">
                <input type="hidden" name="company_id" value="{{ $company->id }}">
            </div>
            <div class="col-sm-6">
                <strong>Nabywca</strong>
                <input type="text" id="select-buyer" placeholder="Wybierz nabywce">
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

        <input type="hidden" name="product-list-src" class="form-control" value="{{ route('product.json.list') }}">
        <input type="hidden" name="company-list-src" class="form-control" value="{{ route('company.json.list') }}">

        <button type="submit" class="btn btn-primary">
            Zapisz fakturę
        </button>
    </form>
@endsection

@section('scripts')
    @parent
    <script>
        var selectProductList;
        var companyListSrc = $('[name=company-list-src]').val();

        $('#select-company').selectize({
            valueField: 'id',
            labelField: 'name',
            searchField: ['name', 'address'],
            options: [],
            create: false,
            render: {
                option: function(item, escape) {
                    return '<div>' +
                                '<span class="name">' + escape(item.name) + '</span>' +
                                '<span class="small block grey-text">' + item.address + '</span>' +
                            '</div>';
                }
            },
            load: function(query, callback) {
                selectLoad(query, callback, companyListSrc);
            },
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
                            '<td><input type="number" value="1" name="amount" class="price-input"></td>' +
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