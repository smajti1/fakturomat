@section('scripts')
    @parent
    <script>
        var $priceNet = $('input#price');
        var $priceGross = $('input#price_gross');
        var $taxPercent = $('select#tax_percent');

        $(window).on('keyup', $priceNet, calculateAndSetUpPriceWithTax);
        $(window).on('change', $taxPercent, calculateAndSetUpPriceWithTax);

        function calculateAndSetUpPriceWithTax() {
            var taxPercent = $taxPercent.val();
            if (!isNaN(taxPercent) && taxPercent) {
                var gross = +$priceNet.val() + $priceNet.val() * taxPercent / 100;
                $priceGross.val(gross.toFixed(2));
            } else {
                var price = parseFloat($priceNet.val());
                price = isNaN(price) ? 0.00 : price;
                $priceGross.val(price.toFixed(2));
            }
        }

    </script>
@endsection