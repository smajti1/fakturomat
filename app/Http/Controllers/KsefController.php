<?php
declare(strict_types=1);

namespace App\Http\Controllers;


use App\Models\Company;
use App\Models\Invoice;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Monolog\Handler\RotatingFileHandler;
use Monolog\Logger;
use N1ebieski\KSEFClient\ClientBuilder;
use N1ebieski\KSEFClient\DTOs\Requests\Sessions\Adres;
use N1ebieski\KSEFClient\DTOs\Requests\Sessions\Fa;
use N1ebieski\KSEFClient\DTOs\Requests\Sessions\Faktura;
use N1ebieski\KSEFClient\DTOs\Requests\Sessions\FaWiersz;
use N1ebieski\KSEFClient\DTOs\Requests\Sessions\FormaPlatnosciGroup;
use N1ebieski\KSEFClient\DTOs\Requests\Sessions\Naglowek;
use N1ebieski\KSEFClient\DTOs\Requests\Sessions\NIPGroup;
use N1ebieski\KSEFClient\DTOs\Requests\Sessions\NrRBGroup;
use N1ebieski\KSEFClient\DTOs\Requests\Sessions\P_13_1Group;
use N1ebieski\KSEFClient\DTOs\Requests\Sessions\P_13_2Group;
use N1ebieski\KSEFClient\DTOs\Requests\Sessions\P_13_3Group;
use N1ebieski\KSEFClient\DTOs\Requests\Sessions\Platnosc;
use N1ebieski\KSEFClient\DTOs\Requests\Sessions\Podmiot1;
use N1ebieski\KSEFClient\DTOs\Requests\Sessions\Podmiot1DaneIdentyfikacyjne;
use N1ebieski\KSEFClient\DTOs\Requests\Sessions\Podmiot2;
use N1ebieski\KSEFClient\DTOs\Requests\Sessions\Podmiot2DaneIdentyfikacyjne;
use N1ebieski\KSEFClient\DTOs\Requests\Sessions\RachunekBankowy;
use N1ebieski\KSEFClient\Factories\EncryptionKeyFactory;
use N1ebieski\KSEFClient\Requests\Sessions\Online\Close\CloseRequest;
use N1ebieski\KSEFClient\Requests\Sessions\Online\Open\OpenRequest;
use N1ebieski\KSEFClient\Requests\Sessions\Online\Send\SendRequest;
use N1ebieski\KSEFClient\Resources\ClientResource;
use N1ebieski\KSEFClient\Support\Optional;
use N1ebieski\KSEFClient\ValueObjects\InternalId;
use N1ebieski\KSEFClient\ValueObjects\Mode;
use N1ebieski\KSEFClient\ValueObjects\NIP;
use N1ebieski\KSEFClient\ValueObjects\NipVatUe;
use N1ebieski\KSEFClient\ValueObjects\PeppolId;
use N1ebieski\KSEFClient\ValueObjects\Requests\ReferenceNumber;
use N1ebieski\KSEFClient\ValueObjects\Requests\Sessions\AdresL1;
use N1ebieski\KSEFClient\ValueObjects\Requests\Sessions\FormaPlatnosci;
use N1ebieski\KSEFClient\ValueObjects\Requests\Sessions\FormCode;
use N1ebieski\KSEFClient\ValueObjects\Requests\Sessions\KodWaluty;
use N1ebieski\KSEFClient\ValueObjects\Requests\Sessions\Nazwa;
use N1ebieski\KSEFClient\ValueObjects\Requests\Sessions\NazwaBanku;
use N1ebieski\KSEFClient\ValueObjects\Requests\Sessions\NrRB;
use N1ebieski\KSEFClient\ValueObjects\Requests\Sessions\NrWierszaFa;
use N1ebieski\KSEFClient\ValueObjects\Requests\Sessions\P_1;
use N1ebieski\KSEFClient\ValueObjects\Requests\Sessions\P_11;
use N1ebieski\KSEFClient\ValueObjects\Requests\Sessions\P_11Vat;
use N1ebieski\KSEFClient\ValueObjects\Requests\Sessions\P_12;
use N1ebieski\KSEFClient\ValueObjects\Requests\Sessions\P_13_1;
use N1ebieski\KSEFClient\ValueObjects\Requests\Sessions\P_13_2;
use N1ebieski\KSEFClient\ValueObjects\Requests\Sessions\P_13_3;
use N1ebieski\KSEFClient\ValueObjects\Requests\Sessions\P_14_1;
use N1ebieski\KSEFClient\ValueObjects\Requests\Sessions\P_14_2;
use N1ebieski\KSEFClient\ValueObjects\Requests\Sessions\P_14_3;
use N1ebieski\KSEFClient\ValueObjects\Requests\Sessions\P_15;
use N1ebieski\KSEFClient\ValueObjects\Requests\Sessions\P_2;
use N1ebieski\KSEFClient\ValueObjects\Requests\Sessions\P_7;
use N1ebieski\KSEFClient\ValueObjects\Requests\Sessions\P_8A;
use N1ebieski\KSEFClient\ValueObjects\Requests\Sessions\P_8B;
use N1ebieski\KSEFClient\ValueObjects\Requests\Sessions\P_9A;
use SensitiveParameter;
use function array_key_exists;

class KsefController extends Controller
{

    public function index(): View
    {
        /** @var User $user */
        $user = Auth::user();
        $company = $user->requireCompany();

        return view('ksef.index', compact('company'));
    }

    public function store(Request $request): RedirectResponse
    {
        $this->validate($request, ['ksef_token' => 'required',]);
        /** @var User $user */
        $user = Auth::user();
        $company = $user->requireCompany();
        if ($company->ksefToken !== null) {
            $company->ksefToken->update([
                'ksef_token' => $request->input('ksef_token'),
            ]);
        } else {
            $company->ksefToken()->create([
                'ksef_token' => $request->input('ksef_token'),
            ]);
        }

        return redirect()->route('ksef.index', [$company]);
    }

    public function create(): View
    {
        /** @var User $user */
        $user = Auth::user();
        $company = $user->requireCompany();

        return view('ksef.create', compact('company'));
    }

    public function sendInvoice(Invoice $invoice): RedirectResponse
    {
        /** @var User $user */
        $user = Auth::user();
        $company = $user->requireCompany();

        $ksef_token = $company->ksefToken->ksef_token ?? '';
        $client = $this->getClientByToken($company->tax_id_number, $ksef_token);
        $response = $client->sessions()->online()->open(
            new OpenRequest(FormCode::Fa3),
        )->data();
        $reference_number_for_session = new ReferenceNumber($response['referenceNumber'] ?? '');

        $faktura = $this->createFaktura($company, $invoice);
        $response_send_invoice = $client->sessions()->online()->send(
            new SendRequest(
                $reference_number_for_session,
                $faktura,
            ),
        )->data();

        $client->sessions()->online()->close(
            new CloseRequest($reference_number_for_session),
        );
        $invoice->ksef_invoice_reference_number = $response_send_invoice['referenceNumber'] ?? 'undefined';
        $invoice->save();

        return redirect()->route('invoices.index');
    }

    public function getClientByToken(
        NIP|NipVatUe|InternalId|PeppolId|string $identifier,
        #[SensitiveParameter] string $token,
    ): ClientResource
    {
        return new ClientBuilder()->withMode(app()->environment() === 'production' ? Mode::Production : Mode::Test)
            ->withKsefToken($token)
            ->withLogger(new Logger('ksef', [new RotatingFileHandler(storage_path('logs/ksef.log'), 5)]))
            ->withEncryptionKey(EncryptionKeyFactory::makeRandom()) // Required for invoice resources. Remember to save this value!
            ->withIdentifier($identifier) // Required for authorization. Optional otherwise
            ->build();
    }

    private function createFaktura(Company $company, Invoice $invoice): Faktura
    {
        $now = Carbon::now();
        $buyer = $invoice->buyer;
        $totalSumMappedByTax = $invoice->getTaxPercentsSum();

        $i = 1;
        $invoiceProductList = [];
        foreach ($invoice->invoice_products as $invoiceProduct) {
            $invoiceProductList[] = new FaWiersz(
                new NrWierszaFa($i++),
                p_7: new P_7($invoiceProduct->name),
                p_8A: new P_8A($invoiceProduct->measure_unit),
                p_8B: new P_8B($invoiceProduct->amount),
                p_9A: new P_9A($invoiceProduct->price),
                p_11: new P_11($invoiceProduct->netPrice()),
                p_11Vat: new P_11Vat($invoiceProduct->grossPrice() - $invoiceProduct->netPrice()),
                p_12: P_12::from($invoiceProduct->tax_percent),
            );
        }

        return new Faktura(
            new Naglowek(),
            new Podmiot1(
                new Podmiot1DaneIdentyfikacyjne(
                    new NIP($company->tax_id_number),
                    new Nazwa($company->name),
                ),
                new Adres(new AdresL1($company->getAddressString())),
            ),
            new Podmiot2(
                new Podmiot2DaneIdentyfikacyjne(
                    new NIPGroup(new NIP($buyer->tax_id_number)),
                    new Nazwa($buyer->name),
                ),
                adres: new Adres(new AdresL1($buyer->getAddressString())),
            ),
            new Fa(
                new KodWaluty('PLN'),
                new P_1($now),
                new P_2($invoice->number),
                new P_15($invoice->grossSum()),
                /** @phpstan-ignore function.impossibleType */
                p_13_1Group: array_key_exists('23', $totalSumMappedByTax) ? new P_13_1Group(new P_13_1($totalSumMappedByTax['23']['netPrice']), new P_14_1($totalSumMappedByTax['23']['amountVat'])) : new Optional(),
                /** @phpstan-ignore function.impossibleType */
                p_13_2Group: array_key_exists('8', $totalSumMappedByTax) ? new P_13_2Group(new P_13_2($totalSumMappedByTax['8']['netPrice']), new P_14_2($totalSumMappedByTax['8']['amountVat'])) : new Optional(),
                /** @phpstan-ignore function.impossibleType */
                p_13_3Group: array_key_exists('5', $totalSumMappedByTax) ? new P_13_3Group(new P_13_3($totalSumMappedByTax['5']['netPrice']), new P_14_3($totalSumMappedByTax['5']['amountVat'])) : new Optional(),
                faWiersz: $invoiceProductList,
                platnosc: new Platnosc(
                    platnoscGroup: new FormaPlatnosciGroup(FormaPlatnosci::Przelew),
                    rachunekBankowy: [new RachunekBankowy(
                        new NrRBGroup(new NrRB($company->bank_account)),
                        nazwaBanku: new NazwaBanku($company->bank_name),
                    )],
                ),
            ),
        );
    }
}