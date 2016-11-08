<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Invoice;

class InvoiceToPdfController extends Controller
{

    public function toPdf(Invoice $invoice)
    {
        $user = \Auth::user();

//        abort_if(!$invoice->isOwner(), 404);
        $url = route('api.invoices.to.html', compact('invoice') + ['api_token' => $user->api_token]);
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
        $spellOutAmount = spellOutAmount($invoice->price);

        return view('api.invoices.pdf.html', compact('invoice', 'spellOutAmount'));
    }

    public function footer()
    {
        return view('api.invoices.pdf.footer');
    }
}