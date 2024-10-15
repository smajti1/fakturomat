<?php

declare(strict_types=1);

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Invoice;
use HeadlessChromium\BrowserFactory;
use Illuminate\Http\Response;
use Illuminate\View\View;

class InvoiceToPdfController extends Controller
{

    public function toPdf(Invoice $invoice): Response
    {
        $date = $invoice->issue_date;
        $companySlug = mb_convert_case($invoice->company->slug, MB_CASE_TITLE, "UTF-8");
        $title = "FV_{$companySlug}_{$date}";
        $filename = "{$title}.pdf";

        $browserFactory = new BrowserFactory('chromium');
        $browser = $browserFactory->createBrowser([
            'windowSize' => [1920, 1080],
            'noSandbox' => true,
        ]);
        try {
            $page = $browser->createPage();

            $html_body = $this->toHtml($invoice)->toHtml();
            $html_footer = $this->footer($invoice)->toHtml();
            $page->setHtml($html_body);

            $pdf_options = [
                'marginBottom' => 0,
                'marginLeft' => 0,
                'marginRight' => 0,
                'displayHeaderFooter' => true,
                'headerTemplate' => '<div></div>',
                'footerTemplate' => $html_footer,
            ];
            $pdf = base64_decode($page->pdf($pdf_options)->getBase64());
        } finally {
            $browser->close();
        }

        $headers = [
            'Content-Description' => 'File Transfer',
            'Content-Disposition' => "inline; filename=\"$filename\"",
            'Content-Type' => 'application/pdf',
            'Content-Transfer-Encoding' => 'binary',
            'Expires' => 0,
            'Cache-Control' => 'must-revalidate, post-check=0, pre-check=0',
        ];

        return response()->make($pdf, 200, $headers);
    }

    public function toHtml(Invoice $invoice): View
    {
        $spellOutAmount = spellOutAmount($invoice->price);
        $taxPercentsSum = $invoice->getTaxPercentsSum();
        $totalSum = $invoice->getTotalSum();
        $activeTaxes = activeTaxes();

        return view('api.invoices.pdf.html', compact('invoice', 'spellOutAmount', 'taxPercentsSum', 'totalSum', 'activeTaxes'));
    }

    public function footer(Invoice $invoice): View
    {
        return view('api.invoices.pdf.footer', compact('invoice'));
    }
}