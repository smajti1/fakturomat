<div class="table-responsive{{ $errors->has('product') ? ' has-danger' : '' }}">
    <table id="invoice-product-list" class="table table-striped">
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
                    <td class='amount-price space-nowrap'>{{ money_pl_format($invoice_product->amount*$invoice_product->price) }} zł</td>
                    <td class='tax_percent'>{{ $invoice_product->tax_percent }}</td>
                    <td class='price-with-vat space-nowrap'>{{ money_pl_format($invoice_product->priceWithVat()) }} zł</td>
                    <td class='amount-price-with-vat space-nowrap'>{{ money_pl_format($invoice_product->amount*$invoice_product->priceWithVat()) }} zł</td>
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
        var selectedProductList = [];
        var productTemplate =
            $("<tr class='product search-product'>" +
                "<td class='product_number'>1</td>" +
                "<td class='select-product'>" +
                "<select autocomplete='off' multiple='multiple'>" +
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
            productClone.find('.select-product > select').select2({
                placeholder: 'Wyszukaj produkt',
                ajax: {
                    url: productListSrc,
                    dataType: 'json',
                    delay: 100,
                    data: params => ({searchText: params.term}),
                    processResults: (data) => ({results: data}),
                    cache: true
                },
                templateResult: function (state) {
                    if (!state.id) {
                        return state.text;
                    }
                    return $(
                        '<div>' + state.name + ' <span class="small block">' + state.price + 'zł + ' + state.tax_percent + '%</span></div>'
                    );
                },
            })
            .on("select2:select", function (e) {
                const selectedItem = e.params.data;
                selectedProductList[e.params.data.id] = selectedItem;

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
            })
            [0].focus();
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
            if (this.name.indexOf('invoice_products') !== -1) {
                amountChange(this);
            } else {
                const productId = /[0-9]+/.exec(this.name)[0];
                const selectedItem = selectedProductList[productId];
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