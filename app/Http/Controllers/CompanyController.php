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
        $companies = \Auth::user()->companies();

        return view('companies.index', compact('companies'));
    }

    public function create()
    {
        return view('companies.create');
    }

    public function store()
    {

    }


    public function edit(Company $company)
    {
        abort_if(!$company->isOwner(), 404);

        return view('companies.edit', compact('company'));
    }

    public function update(Company $company, Request $request)
    {
        abort_if(!$company->isOwner(), 404);
        
    }
}