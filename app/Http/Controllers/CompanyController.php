<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CompanyController extends Controller
{

    private const JSON_LIST_LIMIT = 20;

    public function jsonList(Request $request)
    {
        $search = $request->searchText;
        /** @var User $user */
        $user = Auth::user();
        return $user
            ->company()
            ->where('name', 'ILIKE', $search . '%')
            ->limit(self::JSON_LIST_LIMIT)
            ->get();
    }

    public function edit()
    {
        /** @var User $user */
        $user = Auth::user();
        $company = $user->company;

        return view('companies.edit', compact('company'));
    }

    public function update(Request $request)
    {
        $this->validate($request, $this->rules());
        /** @var User $user */
        $user = Auth::user();
        $company = $user->company;
        $company->update($request->all());
        $company->save();

        return redirect()->route('company.edit');
    }

    protected function rules(): array
    {
        /** @var User $user */
        $user = Auth::user();
        $company_id = $user->company->id;
        return [
            'name'          => "required|max:255|unique:companies,name,$company_id",
            'address'       => 'max:255',
            'tax_id_number' => 'max:255|tax_id_number: -',
            'regon'         => 'max:255',
            'email'         => 'max:255',
            'website'       => 'max:255',
            'phone'         => 'max:255',
			'bank_name' => 'max:255',
            'bank_account'  => 'max:255',
        ];
    }
}
