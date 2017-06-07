<?php

namespace App\Http\Controllers\Imports;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class ImportController extends Controller
{
    public function index()
    {
        return view('import.index');
    }

    public function fromMegaFaktura(Request $request, ImportFromMegaFakturaV4 $importFromMegaFakturaV4)
    {
        $this->validate($request, [
            'products' => 'required',
            'buyers'   => 'required',
        ]);
        $importFromMegaFakturaV4->import();

        return redirect()->route('panel');
    }

}