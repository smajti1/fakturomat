<?php
declare(strict_types=1);

namespace App\Http\Controllers;


use App\Models\Company;
use App\Models\Invoice;
use App\Models\InvoiceProduct;
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
use N1ebieski\KSEFClient\DTOs\Requests\Sessions\Naglowek;
use N1ebieski\KSEFClient\DTOs\Requests\Sessions\NIPGroup;
use N1ebieski\KSEFClient\DTOs\Requests\Sessions\Podmiot1;
use N1ebieski\KSEFClient\DTOs\Requests\Sessions\Podmiot1DaneIdentyfikacyjne;
use N1ebieski\KSEFClient\DTOs\Requests\Sessions\Podmiot2;
use N1ebieski\KSEFClient\DTOs\Requests\Sessions\Podmiot2DaneIdentyfikacyjne;
use N1ebieski\KSEFClient\Factories\EncryptionKeyFactory;
use N1ebieski\KSEFClient\Requests\Sessions\Online\Close\CloseRequest;
use N1ebieski\KSEFClient\Requests\Sessions\Online\Open\OpenRequest;
use N1ebieski\KSEFClient\Requests\Sessions\Online\Send\SendRequest;
use N1ebieski\KSEFClient\Resources\ClientResource;
use N1ebieski\KSEFClient\ValueObjects\InternalId;
use N1ebieski\KSEFClient\ValueObjects\Mode;
use N1ebieski\KSEFClient\ValueObjects\NIP;
use N1ebieski\KSEFClient\ValueObjects\NipVatUe;
use N1ebieski\KSEFClient\ValueObjects\PeppolId;
use N1ebieski\KSEFClient\ValueObjects\Requests\ReferenceNumber;
use N1ebieski\KSEFClient\ValueObjects\Requests\Sessions\AdresL1;
use N1ebieski\KSEFClient\ValueObjects\Requests\Sessions\FormCode;
use N1ebieski\KSEFClient\ValueObjects\Requests\Sessions\KodWaluty;
use N1ebieski\KSEFClient\ValueObjects\Requests\Sessions\Nazwa;
use N1ebieski\KSEFClient\ValueObjects\Requests\Sessions\NrWierszaFa;
use N1ebieski\KSEFClient\ValueObjects\Requests\Sessions\P_1;
use N1ebieski\KSEFClient\ValueObjects\Requests\Sessions\P_11;
use N1ebieski\KSEFClient\ValueObjects\Requests\Sessions\P_11A;
use N1ebieski\KSEFClient\ValueObjects\Requests\Sessions\P_11Vat;
use N1ebieski\KSEFClient\ValueObjects\Requests\Sessions\P_12;
use N1ebieski\KSEFClient\ValueObjects\Requests\Sessions\P_15;
use N1ebieski\KSEFClient\ValueObjects\Requests\Sessions\P_2;
use N1ebieski\KSEFClient\ValueObjects\Requests\Sessions\P_7;
use N1ebieski\KSEFClient\ValueObjects\Requests\Sessions\P_8A;
use N1ebieski\KSEFClient\ValueObjects\Requests\Sessions\P_8B;
use N1ebieski\KSEFClient\ValueObjects\Requests\Sessions\P_9A;
use N1ebieski\KSEFClient\ValueObjects\Requests\Sessions\P_9B;
use SensitiveParameter;

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
        $company = $user->requireCompany()->ksefToken()->create([
            'ksef_token' => $request->input('ksef_token'),
        ]);

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
        $i = 1;
        $now = Carbon::now();
        $buyer = $invoice->buyer;
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
                faWiersz: $invoice->invoice_products->map(fn(InvoiceProduct $product) => new FaWiersz(
                    new NrWierszaFa($i++),
                    p_7: new P_7($product->name),
                    p_8A: new P_8A($product->measure_unit),
                    p_8B: new P_8B($product->amount),
                    p_9A: new P_9A($product->price),
                    p_9B: new P_9B($product->priceWithVat()),
                    p_11: new P_11($product->netPrice()),
                    p_11A: new P_11A($product->grossPrice()),
                    p_11Vat: new P_11Vat($product->grossPrice() - $product->netPrice()),
                    p_12: P_12::from($product->tax_percent),
                ))->toArray(),
            ),
        );
    }
}