<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Invoice;

class InvoiceToPdfController extends Controller
{

    public function toPdf(Invoice $invoice)
    {
        $user = \Auth::user();

        if (!$this->commandExist()) {
            throw new \Exception('Brak wkhtmltopdf');
        }
        $route_parameters = compact('invoice') + ['api_token' => $user->api_token];
        $url = route('api.invoices.to.html', $route_parameters);
        $filename = "faktura-" . $invoice->company->slug . '-' . date('Y-m-d') . ".pdf";
        $uploadDir = public_path('uploads/users/' . $user->id . '/invoices');

        if (!file_exists($uploadDir)) {
            mkdir($uploadDir, 0766, true);
        }

        $pathToFile = "$uploadDir/$filename";

        if (is_file($pathToFile)) {
            unlink($pathToFile);
        }

        $title = 'FV_' . date('Y-m') . '_' . $invoice->company->slug . '_' . $invoice->number;
        $footer = '--footer-html ' . route('api.invoices.to.pdf.footer', $route_parameters);
        $code = "wkhtmltopdf --margin-top 10 --margin-bottom 10 $footer --title '$title' $url $pathToFile 2>&1";

        shell_exec($code);
        $headers = [
            'Content-Disposition' => "filename=\"$filename\"",
        ];

        return response()->file($pathToFile, $headers);
    }

    public function toHtml(Invoice $invoice)
    {
        $spellOutAmount = spellOutAmount($invoice->price);
        $taxPercentsSum = $invoice->getTaxPercentsSum();
        $totalSum = $invoice->getTotalSum();
        $activeTaxes = activeTaxes();

        return view('api.invoices.pdf.html', compact('invoice', 'spellOutAmount', 'taxPercentsSum', 'totalSum', 'activeTaxes'));
    }

    public function footer()
    {
        return view('api.invoices.pdf.footer');
    }

    function commandExist() {
        $returnVal = shell_exec("which wkhtmltopdf");
        return (empty($returnVal) ? false : true);
    }
}