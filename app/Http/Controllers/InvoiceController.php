<?php

namespace App\Http\Controllers;

use App\Invoice;
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
        $invoices = Invoice::where('user_id', \Auth::user()->id)->with('buyer')->get();

        return view('invoices.index', compact('invoices'));
    }

    public function create()
    {
        return view('invoices.create');
    }

    public function store(Request $request)
    {
        //
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
