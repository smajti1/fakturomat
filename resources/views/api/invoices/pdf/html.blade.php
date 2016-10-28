<!DOCTYPE html>
<html lang="pl">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta http-equiv="x-ua-compatible" content="ie=edge">
    <title>{{ config('app.name', 'Fakturomat') }}</title>
    <link href="{{ asset('/css/invoice-pdf.css') }}" rel="stylesheet">
</head>
<body class="pdf invoice-pdf" onload="subst()">

<div class="container">
    <div class="row">
        <div id="invoice-number" class="col-xs-12">
            <h1>Faktura {{ $invoice->number }}</h1>
        </div>
    </div>

    <div class="row border-box-content">
        <div class="col-xs-4 offset-xs-1 company border-box">
            <strong>Sprzedawca</strong>
            <div id="company-name-placeholder">
                {{ $invoice->company->name }}
            </div>
            <div id="company-address-placeholder">
                {{ $invoice->company->address }}
            </div>
        </div>
        <div class="col-xs-4 offset-xs-2s buyer border-box">
            <strong>Nabywca</strong>
            <div id="buyer-name-placeholder">
                {{ $invoice->buyer->name }}
            </div>
            <div id="buyer-address-placeholder">
                {{ $invoice->buyer->address }}
            </div>
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
            $grossSum[$product->vat] = ($grossSum[$product->vat] ?? 0) + $product->price * $product->amount;
            $netSum[$product->vat] = ($netSum[$product->vat] ?? 0) + $product->price * $product->amount;
            ?>
            <tr>
                <td>{{ $loop->iteration }}</td>
                <td>{{ str_replace(' ', '&nbsp;', $product->name) }}</td>
                <td>{{ $product->measure_unit }}</td>
                <td>{{ $product->amount }}</td>
                <td>{{ money_pl_format($product->price) }}&nbsp;zł</td>
                <td>{{ money_pl_format($product->price * $product->amount)  }}&nbsp;zł</td>
                <td>{{ $product->vat }}%</td>
                <td>{{ money_pl_format($product->price * $product->amount * $product->vat / 100) }}&nbsp;zł</td>
                <td>{{ money_pl_format($product->price * $product->amount * $product->calculateVat()) }}&nbsp;zł</td>
            </tr>
        @endforeach
        </tbody>
    </table>

    <div class="row">
        <div class="col-xs-6">
            <div>
                <strong>Płatność:</strong>
                {{ $invoice->payment }}
            </div>
            @if(strlen($invoice->company->bank_account) > 0)
                <div>
                    <strong>Konto bankowe:</strong>
                    {{ $invoice->company->bank_account}}
                </div>
            @endif

            <div>
                <label for="issue_date">Data&nbsp;wystawienia:</label>
                {{ $invoice->issue_date }}
            </div>

            <div>
                <label for="payment_at">Data&nbsp;płatności:</label>
                {{ $invoice->payment_at }}
            </div>

            <div>
                <label for="status">Status:</label>
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
                            <td>{{ money_pl_format($allNetSum += $sum) }}&nbsp;zł</td>
                            <td>{{ $vat }}%</td>
                            <td>{{ money_pl_format($allVatSum  += $sum*$vat/100) }}&nbsp;zł</td>
                            <td>{{ money_pl_format($allSum += $sum*(1 + $vat/100)) }}&nbsp;zł</td>
                        </tr>
                    @endforeach
                    <tr>
                        <td>{{ money_pl_format($allNetSum) }}&nbsp;zł</td>
                        <td>Razem</td>
                        <td>{{ money_pl_format($allVatSum) }}&nbsp;zł</td>
                        <td>{{ money_pl_format($allSum) }}&nbsp;zł</td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>
</div>

</body>
</html>
