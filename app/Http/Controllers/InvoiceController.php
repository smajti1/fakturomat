<?php

namespace App\Http\Controllers;

use App\Models\Buyer;
use App\Models\Invoice;
use App\Models\InvoiceProduct;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class InvoiceController extends Controller
{
    protected Request $request;

    public function __construct(Request $request)
    {
        $this->request = $request;
    }

    public function index()
    {
        $invoices = Invoice::where('user_id', Auth::user()->id)
            ->with(['buyer', 'invoice_products', 'company'])
            ->orderBy('created_at', 'desc')
            ->get();

        return view('invoices.index', compact('invoices'));
    }

    public function create()
    {
        $user = Auth::user();
        if ($user->buyers->isEmpty()) {
            //message = Aby dodać fakturę musisz dodać kontrachenta
            //ToDo in the future add some dialog or wizard

            return redirect()->route('buyer.create');
        }
        $company = $user->company;
        $buyers = $user->buyers;
        $measureUnits = config('invoice.measure_units.' . config('app.locale'));
        $activeTaxes = activeTaxes();

        return view('invoices.create', compact('company', 'buyers', 'measureUnits', 'activeTaxes'));
    }

    public function store(Request $request)
    {
        $this->validate($request, $this->rules());

        $products = Product::whereIn('id', array_keys($request->product))->get();

        $price = 0;
        $invoice_products = [];
        foreach ($products as $product) {
            $amount = $request->product[$product->id];
            $tax_percent = is_numeric($product->calculateVat()) ? $product->calculateVat() : 1;
            $price += $product->price * $tax_percent * $amount;
            $invoice_products[] = [
                'name'         => $product->name,
                'measure_unit' => $product->measure_unit,
                'price'        => $product->price,
                'tax_percent'  => $product->tax_percent,
                'amount'       => $amount,
            ];
        }

        $invoiceData = $request->only('number', 'company_id', 'buyer_id', 'issue_date', 'payment_at', 'payment', 'status');
        $invoice = new Invoice(
            $invoiceData + compact('price')
        );

        $user = Auth::user();
        $invoice->user()->associate($user);
        $invoice->company()->associate($user->company);

        $buyer = Buyer::where('id', $request->buyer_id)->first();
        $invoice->buyer()->associate($buyer);

        $invoice->save();
        $invoice->invoice_products()->createMany($invoice_products);

        return redirect()->route('invoices.index');
    }

    protected function rules(): array
    {
        return [
            'product' => 'required',
        ];
    }

    public function edit(Invoice $invoice)
    {
        $user = Auth::user();
        $company = $user->company;
        $buyers = $user->buyers;
        $measureUnits = config('invoice.measure_units.' . config('app.locale'));
        $activeTaxes = activeTaxes();

        return view('invoices.edit', compact('invoice', 'company', 'buyers', 'measureUnits', 'activeTaxes'));
    }

    public function update(Invoice $invoice, Request $request)
    {
        abort_if(!$invoice->isOwner(), 404);
        $price = 0;
        $new_invoice_products = [];
        if (isset($request->product)) {
            $products = Product::whereIn('id', array_keys($request->product))->get();
            $new_invoice_products = [];
            foreach ($products as $product) {
                $amount = $request->product[$product->id];
                $tax_percent = is_numeric($product->calculateVat()) ? $product->calculateVat() : 1;
                $price += $product->price * $tax_percent * $amount;
                $new_invoice_products[] = [
                    'name'         => $product->name,
                    'measure_unit' => $product->measure_unit,
                    'price'        => $product->price,
                    'tax_percent'  => $product->tax_percent,
                    'amount'       => $amount,
                ];
            }
        }

        if (isset($request->invoice_products)) {
            $invoice_product_ids = array_keys($request->invoice_products);
            $invoice->invoice_products()->whereNotIn('id', $invoice_product_ids)->delete();
            $invoice_products = InvoiceProduct::whereIn('id', array_keys($request->invoice_products))->get();
            foreach ($invoice_products as $invoice_product) {
                $amount = $request->invoice_products[$invoice_product->id];
                $tax_percent = is_numeric($invoice_product->calculateVat()) ? $invoice_product->calculateVat() : 1;
                $price += $invoice_product->price * $tax_percent * $amount;
                $invoice_product->amount = $amount;
                $invoice_product->save();
            }
        }

        $invoice->invoice_products()->createMany($new_invoice_products);
        $invoiceData = $request->only('number', 'company_id', 'buyer_id', 'issue_date', 'payment_at', 'payment', 'status');
        $invoice->update($invoiceData + compact('price'));
        $buyer = Buyer::where('id', $request->buyer_id)->first();
        $invoice->buyer()->associate($buyer);
        $invoice->save();

        return redirect()->route('invoices.index');
    }

    public function destroy(Invoice $invoice)
    {
        abort_if(!$invoice->isOwner(), 404);
        $invoice->delete();

        return redirect()->route('invoices.index');
    }
}
