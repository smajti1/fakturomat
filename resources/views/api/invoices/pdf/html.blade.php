<!DOCTYPE html>
<html lang="pl">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta http-equiv="x-ua-compatible" content="ie=edge">
    <title>{{ config('app.name', 'Fakturomat') }}</title>
    <style>{!! file_get_contents('css/bootstrap.mini.css')  !!}</style>
    <style>{{ file_get_contents('css/invoice-pdf.css') }}</style>
</head>
<body class="pdf invoice-pdf" style="--bs-body-font-size: 0.8rem">

<div class="container">
    <div class="row">
        <div id="invoice-number" class="col-12">
            <h1>FAKTURA VAT NR {{ $invoice->number }}</h1>
        </div>
    </div>

    <div class="row border-box-content">
        <div class="company padding-left-20">
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
        <div class="buyer">
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
                <td class="space-nowrap">{{ $product->name }}</td>
                <td>{{ $product->measure_unit }}</td>
                <td>{{ $product->amount }}</td>
                <td class="space-nowrap">{{ money_pl_format($product->price) }} zł</td>
                <td class="space-nowrap">{{ money_pl_format($product->netPrice())  }} zł</td>
                <td>{{ is_numeric($product->tax_percent) ? ($product->tax_percent . '%') : $activeTaxes[$product->tax_percent]['label'] }}</td>
                <td class="space-nowrap">{{ money_pl_format($product->taxAmount()) }} zł</td>
                <td class="space-nowrap">{{ money_pl_format($product->grossPrice()) }} zł</td>
            </tr>
        @endforeach
        </tbody>
    </table>

    <div class="row tax-table">
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
                        <td class="space-nowrap">{{ money_pl_format($sum['netPrice']) }} zł</td>
                        <td>{{ is_numeric($vat) ? ($vat . '%') : $activeTaxes[$vat]['label'] }}</td>
                        <td class="space-nowrap">{{ money_pl_format($sum['amountVat']) }} zł</td>
                        <td class="space-nowrap">{{ money_pl_format($sum['grossPrice']) }} zł</td>
                    </tr>
                @endforeach
                <tr>
                    <td class="space-nowrap">{{ money_pl_format($totalSum['net']) }} zł</td>
                    <td>Razem</td>
                    <td class="space-nowrap">{{ money_pl_format($totalSum['tax']) }} zł</td>
                    <td class="space-nowrap">{{ money_pl_format($totalSum['gross']) }} zł</td>
                </tr>
                </tbody>
            </table>
        </div>
    </div>

    <div class="summary">
        <div class="to-pay">
            Do zapłaty: {{ money_pl_format($invoice->price) }} zł
        </div>
        <div>
            <strong>Kwota słownie:</strong>
            {{ $spellOutAmount }}
        </div>

        <div>
            <strong>Data wystawienia:</strong>
            {{ $invoice->issue_date }}
        </div>

        <div>
            <strong>Data płatności:</strong>
            {{ $invoice->payment_at }}
        </div>

    </div>
</div>

</body>
</html>
