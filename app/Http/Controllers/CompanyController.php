<?php

namespace App\Http\Controllers;

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
}