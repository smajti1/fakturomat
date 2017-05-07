<div class="row panel-body">
    <div class="col-md-6 padding-bottom-1">
        <h3>Kontrachenci</h3>
        <div>
            <a href="{{ route('buyer.index') }}">Lista Kontrachentów</a>
        </div>
        <div>
            <a href="{{ route('buyer.create') }}">Dodaj Kontrachenta</a>
        </div>
    </div>

    <div class="col-md-6 padding-bottom-1">
        <h3>Faktury</h3>
        <div>
            <a href="{{ route('invoices.index') }}">Lista Faktur</a>
        </div>
        <div>
            <a href="{{ route('invoices.create') }}">Dodaj Fakturę</a>
        </div>
    </div>

    <div class="col-md-6 padding-bottom-1">
        <h3>Produkty</h3>
        <div>
            <a href="{{ route('product.index') }}">Lista Produktów</a>
        </div>
        <div>
            <a href="{{ route('product.create') }}">Dodaj Produkt</a>
        </div>
    </div>

    <div class="col-md-6 padding-bottom-1">
        <h3>Ustawienia</h3>
        <div>
            <a href="{{ route('settings.company_invoice_number.edit') }}">Numeracja faktur</a>
        </div>
        <div>
            <a href="{{ route('company.edit') }}">Ustawienia Firmy</a>
        </div>
    </div>

</div>