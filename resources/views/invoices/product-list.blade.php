<div class="table-responsive{{ $errors->has('product') ? ' has-danger' : '' }}">
    <table id="invoice-product-list" class="table">
        <thead>
        <tr>
            <th>Lp</th>
            <th>Nazwa towaru/usługi</th>
            <th>Jedn.m.</th>
            <th>Ilość</th>
            <th>Cena netto</th>
            <th>Kwota netto</th>
            <th>VAT</th>
            <th>Cena brutto</th>
            <th>Kwota brutto</th>
            <th></th>
        </tr>
        </thead>
        <tbody>
        @isset($invoice)
            @php
                $i = 1
            @endphp
            @foreach($invoice->invoice_products as $invoice_product)
                <tr class="invoice_product">
                    <td class='product_number'>{{ $i++ }}</td>
                    <td class='select-product'>{{ $invoice_product->name }}</td>
                    <td class='measure_unit'>{{ $invoice_product->measure_unit }}</td>
                    <td class='count'>
                        <input type="number" value="{{ $invoice_product->amount }}" min="0.01" name="invoice_products[{{ $invoice_product->id }}]" step="any" class="amount_input">
                    </td>
                    <td class='price'>{{ $invoice_product->price }} zł</td>
                    <td class='amount-price'>{{ money_pl_format($invoice_product->amount*$invoice_product->price) }} zł</td>
                    <td class='tax_percent'>{{ $invoice_product->tax_percent }}</td>
                    <td class='price-with-vat'>{{ money_pl_format($invoice_product->priceWithVat()) }} zł</td>
                    <td class='amount-price-with-vat'>{{ money_pl_format($invoice_product->amount*$invoice_product->priceWithVat()) }} zł</td>
                    <td class='remove-product'><i class='fa fa-trash-o color-danger'></i></td>
                </tr>
            @endforeach
        @endisset
        </tbody>
        <tfoot>
        </tfoot>
    </table>
    <div class="form-control-feedback">{{ $errors->first('product') }}</div>
    <a id="add-product">Dodaj produkt <i class="fa fa-plus-square"></i></a>
</div>

@section('scripts')
    @parent
    <script>
        var selectProductList = [];
        var selectedProductList = [];
        var productTemplate =
            $("<tr class='product search-product'>" +
                "<td class='product_number'>1</td>" +
                "<td class='select-product'>" +
                "<select autocomplete='off' placeholder='Wyszukaj lub dodaj produkt'>" +
                "</select>" +
                "</td>" +
                "<td class='measure_unit'></td>" +
                "<td class='count'></td>" +
                "<td class='price'></td>" +
                "<td class='amount-price'></td>" +
                "<td class='tax_percent'></td>" +
                "<td class='price-with-vat'></td>" +
                "<td class='amount-price-with-vat'></td>" +
                "<td class='remove-product'><i class='fa fa-trash-o color-danger'></i></td>" +
                "</tr>");
        $('#invoice-product-list').on('click', '.remove-product', function () {
            this.parentNode.remove();
            calculateProductsSum();
        });

        function selectLoad(query, callback, url) {
            if (!query.length) return callback();

            $.get(url, {
                searchText: query,
            }).done(function (response) {
                for (let val of response) {
                    selectProductList[val.id] = val;
                }
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
                    const selectedItem = selectProductList[item];

                    if (selectedItem) {
                        const taxPercentText = $.isNumeric(selectedItem.tax_percent) ? selectedItem.tax_percent + '%' : selectedItem.tax_percent;
                        productClone.find('.select-product').html(selectedItem.name);
                        productClone.find('.measure_unit').html(selectedItem.measure_unit);
                        productClone.find('.count').html('<input type="number" value="1" min="0.01" name="product[' + selectedItem.id + ']" step="any" autocomplete="off" class="amount_input">');
                        productClone.find('.price').html(selectedItem.price + ' zł');
                        productClone.find('.tax_percent').html(taxPercentText);
                        productClone.find('.count input').focus();

                        let taxPercent = $.isNumeric(selectedItem.tax_percent) ? parseFloat('1.' + selectedItem.tax_percent) : 1;
                        taxPercent = (selectedItem.price * taxPercent).toFixed(2) + ' zł';
                        productClone.find('.price-with-vat').html(taxPercent);
                        productClone.removeClass('search-product');
                        amountChange(null, { selectedItem, productClone });
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

        let productsSum = 0;
        let productsWithTaxSum = [];
        let productsTaxSum = [];

        function calculateProductsSum() {
            productsSum = 0;
            productsWithTaxSum = [];
            productsTaxSum = [];
            $('#invoice-product-list tbody tr:not(.search-product)').each(function (index, element) {
                const amountInput = $(element).find('.amount_input')[0];
                const amount = parseFloat(amountInput.value);
                const productId = /[0-9]+/.exec(amountInput.name)[0];
                let price;

                if (!isNaN(amount) && element.className == 'product') {
                    price = +selectedProductList[productId].price;
                    productsSum += amount * price;
                } else if (!isNaN(amount) && element.className == 'invoice_product') {
                    price =  /[0-9]+.?[0-9]*/.exec($(element).find('.price').html());
                    productsSum += amount * parseFloat(price);
                }
                const taxPercentText = $(element).find('.tax_percent').html();
                const taxPercentNumber = +taxPercentText.replace('%', '');
                let amountPriceWithTaxes = 0;
                let amountTaxes = 0;
                if (Number.isInteger(taxPercentNumber)) {
                    const taxPercent = parseFloat(taxPercentNumber/100);
                    amountPriceWithTaxes = (price * (1 + taxPercent) * amount).toFixed(2);
                    amountTaxes = (price * taxPercent * amount).toFixed(2);
                } else {
                    amountPriceWithTaxes = (price * amount).toFixed(2);
                }
                if (productsWithTaxSum[taxPercentText]) {
                    productsWithTaxSum[taxPercentText] += +amountPriceWithTaxes;
                } else {
                    productsWithTaxSum[taxPercentText] = +amountPriceWithTaxes;
                }
                if (productsTaxSum[taxPercentText]) {
                    productsTaxSum[taxPercentText] += +amountTaxes;
                } else {
                    productsTaxSum[taxPercentText] = +amountTaxes;
                }

            });
            let amountPriceWithVat = 0;
            let trWithCollectionOfSum = '';
            for (let key of Object.keys(productsWithTaxSum)) {
                amountPriceWithVat += +productsWithTaxSum[key];
                trWithCollectionOfSum +=
                    '<tr>' +
                    '<td></td>' +
                    '<td></td>' +
                    '<td></td>' +
                    '<td></td>' +
                    '<td></td>' +
                    '<td></td>' +
                    '<td>' + key + '</td>' +
                    '<td>' + parseFloat(productsTaxSum[key]).toFixed(2) + ' zł' +'</td>' +
                    '<td>' + parseFloat(productsWithTaxSum[key]).toFixed(2) + ' zł' +'</td>' +
                    '</tr>';
            }
            trWithCollectionOfSum +=
                '<tr>' +
                '<td></td>' +
                '<td></td>' +
                '<td></td>' +
                '<td></td>' +
                '<td>suma netto:</td>' +
                '<td id="invoice-product-sum">' + parseFloat(productsSum).toFixed(2) + ' zł' +'</td>' +
                '<td></td>' +
                '<td>suma brutto</td>' +
                '<td>' + parseFloat(amountPriceWithVat).toFixed(2) + ' zł</td>' +
                '</tr>';
            $('#invoice-product-list tfoot').html(trWithCollectionOfSum);
        }
        calculateProductsSum();
        $('#invoice-product-list').on('input', 'tbody .count', function () {
            calculateProductsSum();
        });
        $('#invoice-product-list').on('input', 'tbody .count input', function () {
            console.log(this, this.name);
            if (this.name.indexOf('invoice_products') !== -1) {
                amountChange(this);
            } else {
                const productId = /[0-9]+/.exec(this.name)[0];
                const selectedItem = selectProductList[productId];
                const productClone = $(this).parents('tr');
                amountChange(null, { selectedItem, productClone });
            }
        });

        function amountChange(invoiceProduct, selectedProduct) {
            let product = null;
            let trElement = null;
            let amount = 0;

            if (typeof selectedProduct === 'undefined') {
                trElement = $(invoiceProduct).parents('tr');
                amount = parseFloat(invoiceProduct.value);
                const price = $(trElement).find('.price').html();
                product = {
                    price: price.replace(/ zł/i, ''),
                    tax_percent: $(trElement).find('.tax_percent').html(),
                };
            } else {
                trElement = selectedProduct.productClone;
                amount = parseFloat(trElement.find('.count input')[0].value);
                product = selectedProduct.selectedItem;
            }
            const taxPercent = $.isNumeric(product.tax_percent) ? parseFloat(1 + product.tax_percent/100) : 1;
            const amountPrice = (product.price * amount).toFixed(2);
            trElement.find('.amount-price').html(amountPrice + ' zł');
            const amountPriceWithVat = (product.price * taxPercent * amount).toFixed(2);
            trElement.find('.amount-price-with-vat').html(amountPriceWithVat + ' zł');
        }
    </script>
@endsection