<?php

declare(strict_types=1);

namespace App\Http\Controllers\Settings;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CompanyInvoiceNumberController extends Controller
{

    public function index(): View
    {
        /** @var User $user */
        $user = Auth::user();
        $companyInvoiceNumber = $user->requireCompany()->companyInvoiceNumber;

        return view('settings.company_invoice_number', compact('companyInvoiceNumber'));
    }

    public function update(Request $request): RedirectResponse
    {
        $this->validate($request, $this->rules());
        $input = $request->all();
        $input['autoincrement_number'] = $request->get('autoincrement_number') ? 1 : 0;
        $input['show_number'] = $request->get('show_number') ? 1 : 0;
        $input['show_month'] = $request->get('show_month') ? 1 : 0;
        $input['show_year'] = $request->get('show_year') ? 1 : 0;
        /** @var User $user */
        $user = Auth::user();

        $user->requireCompany()->companyInvoiceNumber->update($input);

        return redirect()->route('settings.company_invoice_number.edit');
    }

    /**
     * @return array<string, string>
     */
    protected function rules(): array
    {
        return [
            'number' => 'required|integer',
        ];
    }
}