<?php

namespace App\Http\Controllers\Settings;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CompanyInvoiceNumberController extends Controller
{

    public function index()
    {
        $user = Auth::user();
        $companyInvoiceNumber = $user->company->companyInvoiceNumber;

        return view('settings.company_invoice_number', compact('companyInvoiceNumber'));
    }

    public function update(Request $request)
    {
        $this->validate($request, $this->rules());
        $input = $request->all();
        $input['autoincrement_number'] = $request->get('autoincrement_number') ? 1 : 0;
        $input['show_number'] = $request->get('show_number') ? 1 : 0;
        $input['show_month'] = $request->get('show_month') ? 1 : 0;
        $input['show_year'] = $request->get('show_year') ? 1 : 0;
        $user = Auth::user();

        $user->company->companyInvoiceNumber->update($input);

        return redirect()->route('settings.company_invoice_number.edit');
    }

    protected function rules()
    {
        $rules = [
            'number' => 'required|integer',
        ];

        return $rules;
    }
}