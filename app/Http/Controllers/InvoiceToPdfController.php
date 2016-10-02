<?php

namespace App\Http\Controllers;

use App\Invoice;

class InvoiceToPdfController extends Controller
{

    public function toPdf(Invoice $invoice)
    {
//        abort_if(!$invoice->isOwner(), 404);
        $url = 'http://www.fakturomat.dev/faktura/3/html';
        $filename = "faktura-" . $invoice->company->slug . '-' . date('Y-m-d') . uniqid() . ".pdf";
        $uploadDir = '/home/hd/Pobrane/faktury';

        $code = "wkhtmltopdf $url $uploadDir/$filename 2>&1";

        dump($code);
        exec($code, $op);
        dump($op);

        return view('invoices.html', compact('invoice'));
    }

    public function toHtml(Invoice $invoice)
    {
        return view('invoices.html', compact('invoice'));
    }
}