<?php

namespace App\Http\Controllers;

use App\Company;
use Illuminate\Http\Request;

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

    public function index()
    {
        $companies = \Auth::user()->companies;

        return view('companies.index', compact('companies'));
    }

    public function create()
    {
        return view('companies.create');
    }

    public function store(Request $request)
    {
        $this->validate($request, $this->rules());

        $company = Company::create($request->all());
        $company->user()->associate(\Auth::user());
        $company->save();

        return redirect()->route('company.index');
    }


    public function edit(Company $company)
    {
        abort_if(!$company->isOwner(), 404);

        return view('companies.edit', compact('company'));
    }

    public function update(Company $company, Request $request)
    {
        $this->validate($request, $this->rules());
        abort_if(!$company->isOwner(), 404);
        $company->update($request->all());

        return redirect()->route('company.index');
    }

    public function destroy(Company $company)
    {
        abort_if(!$company->isOwner(), 404);
        $company->delete();

        return redirect()->back();
    }

    protected function rules()
    {
        return [
            'name'          => 'required|max:255|unique:companies,name',
            'address'       => 'max:255',
            'tax_id_number' => 'max:255',
            'regon'         => 'max:255',
            'email'         => 'max:255',
            'www'           => 'max:255',
            'phone'         => 'max:255',
            'bank_account'  => 'max:255',
        ];
    }
}