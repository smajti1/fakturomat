<?php

namespace App\Http\Controllers;

use Illuminate\Contracts\View\View;

class PanelController extends Controller
{

    public function index(): View
    {
        return view('panel');
    }
}
