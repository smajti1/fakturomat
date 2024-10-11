@extends('companies.base')

@section('breadcrumb')
    @parent
    <li class="breadcrumb-item active">Numeracja faktur</li>
@endsection

@section('content')
    <div class="row">
        <div class="col-md-8 col-md-offset-2">
            <form class="form-horizontal" role="form" action="{{ route('settings.company_invoice_number.update') }}"
                  method="POST">
                {{ csrf_field() }}

                <div class="mb-3{{ $errors->has('number') ? ' has-danger' : '' }}">
                    <label for="number">Aktualny numer</label>
                    <input id="number" type="text" class="form-control form-control-danger" name="number"
                           value="{{ old('number', $companyInvoiceNumber->number ?? '') }}"
                           placeholder="Aktualny numer">
                    <div class="form-control-feedback">{{ $errors->first('number') }}</div>
                </div>

                <div class="mb-3">
                    <label>
                        <input type="checkbox"
                               name="autoincrement_number" {{ $companyInvoiceNumber->autoincrement_number ? 'checked' : '' }}>
                        Zwiększać numer przy każdej nowej fakturze
                    </label>
                </div>

                <div class="mb-3">
                    <label>
                        <input type="checkbox"
                               name="show_number" {{ $companyInvoiceNumber->show_number ? 'checked' : '' }}>
                        Pokazywać na fakturze numer faktury
                    </label>
                </div>

                <div class="mb-3">
                    <label>
                        <input type="checkbox"
                               name="show_month" {{ $companyInvoiceNumber->show_month ? 'checked' : '' }}>
                        Pokazywać miesiąc na fakturze
                    </label>
                </div>

                <div class="mb-3">
                    <label>
                        <input type="checkbox" name="show_year" {{ $companyInvoiceNumber->show_year ? 'checked' : '' }}>
                        Pokazywać rok na fakturze
                    </label>
                </div>

                <button type="submit" class="btn btn-primary">
                    Zapisz zmiany
                </button>
            </form>
        </div>
    </div>
@endsection