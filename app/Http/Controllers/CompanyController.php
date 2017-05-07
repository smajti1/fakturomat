<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CompanyController extends Controller
{

    const JSON_LIST_LIMIT = 20;

    public function jsonList(Request $request)
    {
        $search = $request->searchText;
        $list = \Auth::user()
            ->companies()
            ->where('name', 'LIKE', $search . '%')
            ->limit(self::JSON_LIST_LIMIT)
            ->get();

        return $list;
    }

    public function edit()
    {
        $company = Auth::user()->company;

        return view('companies.edit', compact('company'));
    }

    public function update(Request $request)
    {
        $this->validate($request, $this->rules());
        Auth::user()->update($request->all());

        return redirect()->route('company.edit');
    }

    protected function rules()
    {
        return [
            'name'          => 'required|max:255|unique:companies,name',
            'address'       => 'max:255',
            'tax_id_number' => 'max:255',
            'regon'         => 'max:255',
            'email'         => 'max:255',
            'website'       => 'max:255',
            'phone'         => 'max:255',
            'bank_account'  => 'max:255',
        ];
    }
}