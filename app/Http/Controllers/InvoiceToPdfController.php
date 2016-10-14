<?php

namespace App\Http\Controllers;

use App\Invoice;

class InvoiceToPdfController extends Controller
{

    public function toPdf(Invoice $invoice)
    {
        $user = \Auth::user();

//        abort_if(!$invoice->isOwner(), 404);
        $url = route('invoices.to.html', compact('invoice'));
        $filename = "faktura-" . $invoice->company->slug . '-' . date('Y-m-d') . ".pdf";
        $uploadDir = public_path('uploads/users/' . $user->id . '/invoices');

        if (!file_exists($uploadDir)) {
            mkdir($uploadDir, 0766, true);
        }

        $pathToFile = "$uploadDir/$filename";

        if (is_file($pathToFile)) {
            unlink($pathToFile);
        }


        $title = 'Faktura ' . $invoice->company->slug . ' nr ' . $invoice->number;
        $footer = '';//'--header-html ' . route('invoices.to.pdf.footer');//"--footer-line --footer-right 'strona [page]/[toPage]'";
        $code = "wkhtmltopdf $footer --title '$title' $url $pathToFile 2>&1";



        exec($code, $op);
        $headers = [
          'Content-Disposition' => "filename=\"$filename\"",
        ];

        return response()->file($pathToFile, $headers);
    }

    public function toHtml(Invoice $invoice)
    {
        return view('invoices.pdf.html', compact('invoice'));
    }

    public function footer()
    {
        return view('invoices.pdf.footer');
    }
}