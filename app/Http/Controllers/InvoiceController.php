<?php

namespace App\Http\Controllers;

use App\Buyer;
use App\Company;
use App\Invoice;
use App\Product;
use Illuminate\Http\Request;

class InvoiceController extends Controller
{
    protected $request;

    public function __construct(Request $request)
    {
        $this->request = $request;
    }

    public function index()
    {
        $invoices = Invoice::where('user_id', \Auth::user()->id)
            ->with(['buyer', 'invoice_products', 'company'])
            ->get();

        return view('invoices.index', compact('invoices'));
    }

    public function create()
    {
        $user = \Auth::user();
        $companies = $user->companies;
        $buyers = $user->buyers;

        return view('invoices.create', compact('companies', 'buyers'));
    }

    public function store(Request $request)
    {
        $this->validate($request, $this->rules());

        $products = Product::whereIn('id', array_keys($request->product))->get();

        $price = 0;
        $invoice_products = [];
        foreach ($products as $product) {
            $amount = $request->product[$product->id];
            $price += $product->price * $product->calculateVat() * $amount;
            $invoice_products = [
                'name'         => $product->name,
                'measure_unit' => $product->measure_unit,
                'price'        => $product->price,
                'vat'          => $product->vat,
                'amount'       => $amount,
            ];
        }

        $invoice = Invoice::create(
            $request->all() + compact('price')
        );

        $invoice->invoice_products()->insert($invoice_products);

        $user = \Auth::user();
        $invoice->user()->associate($user);

        $company = Company::where('id', $request->invoice_id)->first();
        $invoice->company()->associate($company);

        $buyer = Buyer::where('id', $request->buyer_id)->first();
        $invoice->buyer()->associate($buyer);

        $invoice->save();

        return redirect()->route('invoices.index');
    }

    protected function rules()
    {
        return [
            'product' => 'required',
        ];
    }

    public function show(Invoice $invoice)
    {
        //
    }

    public function edit(Invoice $invoice)
    {
        //
    }

    public function update(Invoice $invoice)
    {
        //
    }

    public function destroy(Invoice $invoice)
    {
        abort_if(!$invoice->isOwner(), 404);
        $invoice->delete();

        return redirect()->route('invoices.index');
    }
}
