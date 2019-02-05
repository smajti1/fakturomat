<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Invoice;
use Illuminate\View\View;
use Storage;
use Symfony\Component\Process\Exception\ProcessFailedException;
use Symfony\Component\Process\Process;

class InvoiceToPdfController extends Controller
{

    public function toPdf(Invoice $invoice)
    {
        $user = \Auth::user();

        if (!$this->commandExist()) {
            throw new \Exception('Brak wkhtmltopdf');
        }
        $date = $invoice->issue_date;
        $companySlug = mb_convert_case($invoice->company->slug, MB_CASE_TITLE, "UTF-8");
        $title = "FV_{$companySlug}_{$date}";
        $filename = "{$title}.pdf";
        $uploadDir = public_path('uploads/users/' . $user->id . '/invoices');

        if (!file_exists($uploadDir)) {
            mkdir($uploadDir, 0766, true);
        }

        $pathToFile = "$uploadDir/$filename";

        if (is_file($pathToFile)) {
            unlink($pathToFile);
        }


        $htmlTmpFilePath = $this->createHtmlTmpFile($this->toHtml($invoice));
        $htmlFooterTmpFilePath = $this->createHtmlTmpFile($this->footer());

        $process = new Process([
            "wkhtmltopdf",
            "--margin-top",
            "15mm",
            "--margin-bottom",
            "18mm",
            "--footer-html",
            "$htmlFooterTmpFilePath",
            "--title",
            $title,
            $htmlTmpFilePath,
            $pathToFile,
        ]);
        $process->run();
        if (!$process->isSuccessful()) {
            throw new ProcessFailedException($process);
        }

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

    private function commandExist()
    {
        $returnVal = shell_exec("type -P wkhtmltopdf");

        return (empty($returnVal) ? false : true);
    }

    private function createHtmlTmpFile(View $view)
    {
        $htmlContent = $view->render();
        $htmlTmpFilePath = sys_get_temp_dir() . '/' . uniqid() . '.html';
        file_put_contents($htmlTmpFilePath, $htmlContent);

        return $htmlTmpFilePath;
    }

}