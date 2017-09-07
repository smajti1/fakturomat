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
        </tbody>
        <tfoot>
        <tr>
            <td></td>
            <td></td>
            <td></td>
            <td>Suma netto:</td>
            <td id="invoice-product-sum"></td>
            <td></td>
            <td></td>
        </tr>
        </tfoot>
    </table>
    <div class="form-control-feedback">{{ $errors->first('product') }}</div>
    <a id="add-product">Dodaj produkt <i class="fa fa-plus-square"></i></a>
</div>

@section('scripts')
    @parent
    <script>
        var selectProductList;
        var selectedProductList = [];
        var productTemplate =
            $("<tr>" +
                "<td class='product_number'>1</td>" +
                "<td class='select-product'>" +
                "<select autocomplete='off' placeholder='Wyszukaj lub dodaj produkt'>" +
                "</select>" +
                "</td>" +
                "<td class='measure_unit'></td>" +
                "<td class='count' onautocomplete='0'></td>" +
                "<td class='price'></td>" +
                "<td class='tax_percent'></td>" +
                "<td class='remove-product'><i class='fa fa-times'></i></td>" +
                "</tr>");
        $('#invoice-product-list').on('click', '.remove-product', function () {
            this.parentNode.remove();
        });

        function selectLoad(query, callback, url) {
            selectProductList = {};
            if (!query.length) return callback();

            $.get(url, {
                searchText: query,
            }).done(function (response) {
                selectProductList = response;
                callback(response);
            });
        }

        var productListSrc = $('[name=product-list-src]').val();

        $('#add-product').on('click', function () {
            var productClone = productTemplate.clone();
            $('#invoice-product-list tbody').append(productClone);
            var product_number = $('#invoice-product-list tbody tr').length;
            productClone.find('.product_number').html(product_number);
            var productCloneSelectize = productClone.find('.select-product > select').selectize({
                valueField: 'id',
                labelField: 'name',
                maxItem: 1,
                create: true,
                render: {
                    option: function (item, escape) {
                        selectedProductList[item.id] = item;
                        var item_html = '<div>' +
                            '<span class="name">' + escape(item.name) + '</span>';
                        if (item.price) {
                            var tax_percent = $.isNumeric(item.tax_percent) ? item.tax_percent + '%' : item.tax_percent;
                            item_html += '<span class="small block grey-text">' + item.price + 'zł + ' + tax_percent + '</span>';
                        }
                        item_html += '</div>';

                        return item_html;
                    },
                    option_create: function (data, escape) {
                        return '<div class="create">Dodaj produkt <strong>' + escape(data.input) + '</strong>&hellip;</div>';
                    },
                },
                load: function (query, callback) {
                    this.clearOptions();
                    selectLoad(query, callback, productListSrc);
                },
                onChange: function (item) {
                    var selectedItem = null;
                    for (var i = 0; i < selectProductList.length; i++) {
                        if (selectProductList[i].id == item) {
                            selectedItem = selectProductList[i];
                        }
                    }
                    if (selectedItem) {
                        var tax_percent = $.isNumeric(selectedItem.tax_percent) ? selectedItem.tax_percent + '%' : selectedItem.tax_percent;
                        productClone.find('.select-product').html(selectedItem.name);
                        productClone.find('.measure_unit').html(selectedItem.measure_unit);
                        productClone.find('.count').html('<input type="number" value="1" min="1" name="product[' + selectedItem.id + ']" class="price-input">');
                        productClone.find('.price').html(selectedItem.price + ' zł');
                        productClone.find('.tax_percent').html(tax_percent);
                    }
                    calculateProductsSum();
                },
                score: function () {
                    return function () {
                        return 1;
                    };
                },
            });
            productCloneSelectize[0].selectize.focus();
            $(window).scrollTop(window.pageYOffset + $('#invoice-product-list tr td').outerHeight() + 20);
        });
        $('#add-product').trigger('click');

        var productsSum = 0;

        function calculateProductsSum() {
            productsSum = 0;
            $('#invoice-product-list tbody .price-input').each(function (index, element) {
                var count = element.value;
                var productId = /[0-9]+/.exec(element.name)[0];
                productsSum += +count * +selectedProductList[productId].price;
            });
            $('#invoice-product-sum').text(parseFloat(productsSum).toFixed(2) + ' zł');
        }
        $('#invoice-product-list').on('input', 'tbody .count', calculateProductsSum);

    </script>
@endsection