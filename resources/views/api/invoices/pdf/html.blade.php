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
        <div id="invoice-number" class="col-12">
            <h1>FAKTURA VAT NR {{ $invoice->number }}</h1>
        </div>
    </div>

    <div class="row border-box-content">
        <div class="col-6 company padding-left-20">
            <h4>Sprzedawca</h4>
            <div class="padding-left-20">
                <div id="company-name-placeholder">
                    <strong>Nazwa:</strong>
                    <div class="padding-left-20">
                        {{ $invoice->company->name }}
                    </div>
                </div>
                <div>
                    <strong>Nip:</strong>
                    <div class="padding-left-20">
                        {{ $invoice->company->tax_id_number }}
                    </div>
                </div>
                <div id="company-address-placeholder">
                    <strong>Adres:</strong>
                    @foreach($invoice->company->getAddress() as $address)
                        <div class="padding-left-20">{{ $address }}</div>
                    @endforeach
                </div>
                @if(strlen($invoice->company->bank_account) > 0)
                    <div>
                        <strong>Konto bankowe:</strong>
                        <div class="padding-left-20">
                            {{ $invoice->company->bank_name }}
                        </div>
                        <div class="padding-left-20">
                            {{ $invoice->company->bank_account}}
                        </div>
                    </div>
                @endif
            </div>
        </div>
        <div class="col-6 buyer">
            <h4>Nabywca</h4>
            <div class="padding-left-20">
                <div id="buyer-name-placeholder">
                    <strong>Nazwa:</strong>
                    <div class="padding-left-20">
                        {{ $invoice->buyer->name }}
                    </div>
                </div>
                <div>
                    <strong>Nip:</strong>
                    <div class="padding-left-20">
                        {{ $invoice->buyer->tax_id_number }}
                    </div>
                </div>
                <div id="buyer-address-placeholder">
                    <strong>Adres:</strong>
                    @foreach($invoice->buyer->getAddress() as $address)
                        <div class="padding-left-20">{{ $address }}</div>
                    @endforeach
                </div>
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
            <tr>
                <td>{{ $loop->iteration }}</td>
                <td>{{ str_replace(' ', '&nbsp;', $product->name) }}</td>
                <td>{{ $product->measure_unit }}</td>
                <td>{{ $product->amount }}</td>
                <td>{{ money_pl_format($product->price) }}&nbsp;zł</td>
                <td>{{ money_pl_format($product->netPrice())  }}&nbsp;zł</td>
                <td>{{ $product->tax_percent }}%</td>
                <td>{{ money_pl_format($product->taxAmount()) }}&nbsp;zł</td>
                <td>{{ money_pl_format($product->grossPrice()) }}&nbsp;zł</td>
            </tr>
        @endforeach
        </tbody>
    </table>

    <div class="row">
        <div class="col-6 offset-6">
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
                    @foreach($taxPercentsSum as $vat => $sum)
                        <tr>
                            <td>{{ money_pl_format($sum['netPrice']) }}&nbsp;zł</td>
                            <td>{{ $vat }}%</td>
                            <td>{{ money_pl_format($sum['amountVat']) }}&nbsp;zł</td>
                            <td>{{ money_pl_format($sum['grossPrice']) }}&nbsp;zł</td>
                        </tr>
                    @endforeach
                    <tr>
                        <td>{{ money_pl_format($totalSum['net']) }}&nbsp;zł</td>
                        <td>Razem</td>
                        <td>{{ money_pl_format($totalSum['tax']) }}&nbsp;zł</td>
                        <td>{{ money_pl_format($totalSum['gross']) }}&nbsp;zł</td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>

    <div id="row summary">
        <div class="to-pay">
            Do zapłaty: {{ money_pl_format($invoice->price) }} zł
        </div>
        <div>
            <strong>Kwota słownie:</strong>
            {{ $spellOutAmount }}
        </div>

        <div>
            <strong>Data&nbsp;wystawienia:</strong>
            {{ $invoice->issue_date }}
        </div>

        <div>
            <strong>Data&nbsp;płatności:</strong>
            {{ $invoice->payment_at }}
        </div>

    </div>
</div>

</body>
</html>
