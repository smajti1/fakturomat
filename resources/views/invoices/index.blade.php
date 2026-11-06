@extends('invoices.base')

@section('content')
    <h1>Faktury</h1>
    <div>
        <a href="{{ route('invoices.create') }}">Dodaj <i class="fa fa-plus"></i></a>
    </div>
    <div class="table-responsive">
        <table class="table table-bordered table-striped">
            <thead>
            <tr>
                <th>Nr.</th>
                <th>Nabywca</th>
                <th class="d-none d-md-table-cell d-lg-table-cell d-xl-table-cell">Płatność</th>
                <th>Kwota netto</th>
                <th>Kwota brutto</th>
                <th class="d-none d-md-table-cell d-lg-table-cell d-xl-table-cell">Ilość produktów</th>
                <th class="d-none d-md-table-cell d-lg-table-cell d-xl-table-cell">Wpłata</th>
                <th>
                    Data dostawy/usługi
                    <i class="fa fa-question-circle-o" title="Data dokonania lub zakończenia dostawy towarów lub wykonania usługi (wcześniej Data wystawienia)"></i>
                </th>
                <th>KSeF</th>
                <th></th>
            </tr>
            </thead>
            <tbody>
            @foreach($invoices as $invoice)
                <tr>
                    <td>{{ $invoice->number }}</td>
                    <td>{{ $invoice->buyer['name'] }}</td>
                    <td class="d-none d-md-table-cell d-lg-table-cell d-xl-table-cell">{{ $invoice->payment }}</td>
                    <td>{{ $invoice->price_net }} zł</td>
                    <td>{{ $invoice->price }} zł</td>
                    <td class="d-none d-md-table-cell d-lg-table-cell d-xl-table-cell">{{ $invoice->invoice_products->count() }}</td>
                    <td class="d-none d-md-table-cell d-lg-table-cell d-xl-table-cell">{{ $invoice->payment_at }}</td>
                    <td>{{ $invoice->issue_date }}</td>
                    <td>
                        @if($invoice->ksef_invoice_reference_number)
                            <span class="text-success" title="Faktura wysłana do KSeF">
                                <i class="fa fa-check-circle"></i> Wysłana
                            </span>
                        @elseif($company->ksefToken === null)
                            <span class="text-warning" title="KSeF nie jest skonfigurowany">
                                <i class="fa fa-exclamation-triangle" aria-hidden="true"></i> Nieaktywny
                            </span>
                        @else
                            <form action="{{ route('ksef.sendInvoice', $invoice) }}"
                                  method="POST"
                                  class="inline-block"
                                  data-invoice-number="{{ $invoice->number }}"
                                  data-invoice-price="{{ $invoice->price }}"
                                  data-issue-date="{{ $invoice->issue_date }}"
                                  onsubmit="return confirmSendInvoiceToKsef(event, this)">
                                {{ csrf_field() }}
                                <input type="hidden" name="sendIssueDate" value="0">
                                <button type="submit" class="btn-like-link">
                                    <i class="fa fa-paper-plane"></i> Wyślij
                                </button>
                            </form>
                        @endif
                    </td>
                    <td>
                        <a href="{{ route('invoices.edit', compact('invoice')) }}" class="inline-block">
                            <i class="fa fa-pencil-square-o"></i>
                        </a>

                        <form action="{{ route('invoices.destroy', compact('invoice')) }}" method="POST" class="inline-block">
                            {{ csrf_field() }}
                            <input type="hidden" name="_method" value="DELETE">
                            <button type="submit" class="btn-like-link" onclick="return confirm('Jesteś pewien, że chcesz usunąć fakture nr [{{ $invoice->number }}]?');">
                                <i class="fa fa-trash-o color-danger" aria-hidden="true"></i>
                            </button>
                        </form>

                        <a href="{{ route('api.invoices.to.pdf', compact('invoice') + ['api_token' => \Illuminate\Support\Facades\Auth::user()->api_token]) }}" class="inline-block" target="_blank">
                            <i class="fa fa-file-pdf-o" title="pobierz fakturę w formacie pdf"></i>
                        </a>
                        <a href="{{ route('ksef.downloadXml', $invoice) }}"
                           data-invoice-number="{{ $invoice->number }}"
                           data-invoice-price="{{ $invoice->price }}"
                           data-issue-date="{{ $invoice->issue_date }}"
                           onclick="confirmXmlDownload(event, this)">
                            <i class="fa fa-file-excel-o" title="pobierz fakturę w formacie xml"></i>
                        </a>
                    </td>
                </tr>
            @endforeach
            </tbody>
        </table>
        <dialog id="invoiceConfirmDialog">
            <form method="dialog">
                <h5 id="dialog_title">Wysłać do KSeF?</h5>
                <div>Faktura nr <strong id="invoiceNumber"></strong></div>
                <div>Na kwotę brutto <strong id="invoicePrice"></strong> zł</div>
                <br>
                <div>
                    <label>
                        <input type="checkbox" id="sendIssueDateCheckbox">
                        załączyć Data dostawy/usługi <strong id="issueDate"></strong>
                        <br/>
                        <small>(tak jeśli wystawiasz fakturę np za zaległy miesiąc)</small>
                    </label>
                </div>
                <br>
                <div>
                    <button value="cancel">Anuluj</button>
                    <button value="confirm">Potwierdź</button>
                </div>
            </form>
        </dialog>
    </div>
@endsection


@section('scripts')
    @parent
    <script>
        let pendingForm = null;
        let pendingUrl = null;

        const dialog = document.getElementById('invoiceConfirmDialog');
        const checkbox = document.getElementById('sendIssueDateCheckbox');

        function confirmSendInvoiceToKsef(event, form) {
            event.preventDefault();
            pendingForm = form;

            document.getElementById('dialog_title').textContent = "Wysłać fakturę do KSeF?";
            document.getElementById('invoiceNumber').textContent = form.dataset.invoiceNumber;
            document.getElementById('invoicePrice').textContent = form.dataset.invoicePrice;
            document.getElementById('issueDate').textContent = form.dataset.issueDate;
            dialog.showModal();
        }

        function confirmXmlDownload(event, link) {
            event.preventDefault();
            pendingUrl = link.href;

            document.getElementById('dialog_title').textContent = "Pobrać fakturę w formacie xml?";
            document.getElementById('invoiceNumber').textContent = link.dataset.invoiceNumber;
            document.getElementById('invoicePrice').textContent = link.dataset.invoicePrice;
            document.getElementById('issueDate').textContent = link.dataset.issueDate;

            dialog.showModal();
        }

        dialog.addEventListener('close', () => {
            console.log(dialog, pendingForm, pendingUrl)
            if (dialog.returnValue === 'confirm') {
                if (pendingForm) {
                    pendingForm.elements.sendIssueDate.value = checkbox.checked ? '1' : '0';
                    pendingForm.submit();
                }

                if (pendingUrl) {
                    const url = new URL(pendingUrl);
                    url.searchParams.set('sendIssueDate', checkbox.checked ? '1' : '0');
                    window.open(url.toString(), '_blank');
                }
            }

            if (pendingForm) {
                pendingForm.elements.sendIssueDate.value = '0';
            }

            checkbox.checked = false;
            pendingForm = null;
            pendingUrl = null;
        });
    </script>
@endsection