<div class="table-responsive{{ $errors->has('product') ? ' has-danger' : '' }}">
    <table id="invoice-product-list" class="table">
        <thead>
        <tr>
            <th>Lp</th>
            <th>Nazwa towaru/usługi</th>
            <th>Jedn.m.</th>
            <th>Ilość</th>
            <th>Cena netto</th>
            <th>VAT</th>
            <th></th>
        </tr>
        </thead>
        <tbody>
        <tr id="product-placeholder">
            <td>1</td>
            <td>
                <input id="select-product" placeholder="Wyszukaj lub dodaj produkt">
            </td>
            <td>
            </td>
            <td></td>
            <td></td>
            <td></td>
            <td class="remove-product"><i class="fa fa-times"></i></td>
        </tr>
        </tbody>
    </table>
    <div class="form-control-feedback">{{ $errors->first('product') }}</div>
    <a href="#" id="add-product">Dodaj produkt <i class="fa fa-plus-square"></i></a>
</div>


@section('scripts')
    @parent
    <script>
        var selectProductList;

        $('#invoice-product-list').on('click', '.remove-product', function () {
            this.parentNode.remove();
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
                        '<span class="small block grey-text">' + item.price + 'zł + ' + item.tax_percent + '%</span>' +
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
                        '<td><input value="' + selectedItem.name + '"></td>' +
                        '<td>' + selectedItem.measure_unit + '</td>' +
                        '<td><input type="number" value="1" name="product[' + selectedItem.id + ']" class="price-input"></td>' +
                        '<td>' + selectedItem.price + ' zł</td>' +
                        '<td>' + selectedItem.tax_percent + '%</td>' +
                        '<td class="remove-product"><i class="fa fa-times"></i></td>' +
                        '</tr>'
                    );

                    $('#product-placeholder').before(product);
                }
            }
        });

        $('#add-product').on('click', function () {
            var product = $(
                '<tr>' +
                '<td>' + $('#invoice-product-list tr').length + '</td>' +
                '<td><input ></td>' +
                '<td></td>' +
                '<td><input type="number" value="1" name="product[]" class="price-input"></td>' +
                '<td></td>' +
                '<td></td>' +
                '<td class="remove-product"><i class="fa fa-times"></i></td>' +
                '</tr>'
            );

            $('#invoice-product-list tbody tr:last-child').after(product);
        });
    </script>
@endsection