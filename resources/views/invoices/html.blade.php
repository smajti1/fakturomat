<!DOCTYPE html>
<html lang="pl">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta http-equiv="x-ua-compatible" content="ie=edge">
    <title>{{ config('app.name', 'Fakturomat') }}</title>
    <link href="{{ asset('/css/app.css') }}" rel="stylesheet">
</head>
<body class="pdf">

<div class="container">
    <div class="row">
        <div id="invoice-number" class="col-xs-12">
            <label for="number">Faktura</label>
            <strong>{{ $invoice->number }}</strong>
        </div>
    </div>

    <div class="row">
        <div class="col-xs-6 company">
            <strong>Sprzedawca</strong>
            <div id="company-name-placeholder">
                {{ $invoice->company->name }}
            </div>
            <div id="company-address-placeholder">
                {{ $invoice->company->address }}
            </div>
        </div>
        <div class="col-xs-6">
            <strong>Nabywca</strong>
            <div id="buyer-name-placeholder">
                {{ $invoice->buyer->name }}
            </div>
            <div id="buyer-address-placeholder">
                {{ $invoice->buyer->address }}
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-xs-12">
            <div>
                <strong>Płatność:</strong>
                {{ $invoice->payment }}
            </div>
            @if(strlen($invoice->company->bank_account_number) > 0)
                <div>
                    <strong>Konto:</strong>
                    {{ $invoice->company->bank_account_number }}
                </div>
            @endif
        </div>
    </div>
    <table id="invoice-product-list" class="table table-bordered table-striped">
        <thead>
            <tr>
                <th>Lp</th>
                <th>Nazwa</th>
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
        @foreach($invoice->invoice_products as $product)
            <?php
                $grossSum[$product->vat] = ($grossSum[$product->vat] ?? 0) + $product->price;
                $netSum[$product->vat] = ($netSum[$product->vat] ?? 0) + $product->price;
            ?>
            <tr>
                <td>{{ $loop->iteration }}</td>
                <td>{{ $product->name }}</td>
                <td>{{ $product->measure_unit }}</td>
                <td>{{ $product->amount }}</td>
                <td>{{ $product->price }} zł</td>
                <td>{{ $product->price * $product->amount  }} zł</td>
                <td>{{ $product->vat }}</td>
                <td>{{ $product->price * $product->amount * $product->vat }} zł</td>
                <td>{{ $product->price * $product->amount * $product->calculateVat() }} zł</td>
            </tr>
        @endforeach
        </tbody>
    </table>

    <div class="row">
        <div class="col-xs-6">
            <div>
                <label for="issue_date">Data wystawienia</label>
                {{ $invoice->issue_date }}
            </div>

            <div>
                <label for="payment_at">Data płatności</label>
                {{ $invoice->payment_at }}
            </div>

            <div>
                <label for="status">Status</label>
                {{ $invoice->status }}
            </div>
        </div>
        <div class="col-xs-6">
            <table class="table table-bordered table-striped">
                <thead>
                    <tr>
                        <th>Netto</th>
                        <th>%</th>
                        <th>VAT</th>
                        <th>Brutto</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                        $allNetSum = 0;
                        $allSum = 0;
                        $allVatSum = 0;
                    ?>
                    @foreach($grossSum as $vat => $sum)
                        <tr>
                            <td>{{ $allNetSum += $sum }}</td>
                            <td>{{ $vat }}%</td>
                            <td>{{ $allVatSum  += $sum*$vat/100 }}</td>
                            <td>{{ $allSum += $sum*(1 + $vat/100) }}</td>
                        </tr>
                    @endforeach
                    <tr>
                        <td>{{ $allNetSum }}</td>
                        <td>Razem</td>
                        <td>{{ $allVatSum }}</td>
                        <td>{{ $allSum }}</td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>
</div>

</body>
</html>
