<div class="row panel-body">
    <div class="col-md-6 padding-bottom-1">
        <h2>Firmy</h2>
        <div>
            <a href="{{ route('company.index') }}">Lista Firm</a>
        </div>
        <div>
            <a href="{{ route('company.create') }}">Dodaj Firmę</a>
        </div>
    </div>

    <div class="col-md-6 padding-bottom-1">
        <h2>Kontrachenci</h2>
        <div>
            <a href="{{ route('buyer.index') }}">Lista Kontrachentów</a>
        </div>
        <div>
            <a href="{{ route('buyer.create') }}">Dodaj Kontrachenta</a>
        </div>
    </div>

    <div class="col-md-6 padding-bottom-1">
        <h2>Faktury</h2>
        <div>
            <a href="{{ route('invoices.index') }}">Lista Faktur</a>
        </div>
        <div>
            <a href="{{ route('invoices.create') }}">Dodaj Fakturę</a>
        </div>
    </div>

    <div class="col-md-6 padding-bottom-1">
        <h2>Produkty</h2>
        <div>
            <a href="{{ route('product.index') }}">Lista Produktów</a>
        </div>
        <div>
            <a href="{{ route('product.create') }}">Dodaj Produkt</a>
        </div>
    </div>

</div>