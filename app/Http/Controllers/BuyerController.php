<?php

namespace App\Http\Controllers;

use App\Buyer;
use Illuminate\Http\Request;

class BuyerController extends Controller
{
    protected $request;

    public function __construct(Request $request)
    {
        $this->request = $request;
    }

    public function index()
    {
        return view('buyers.index');
    }

    public function create()
    {
        return view('buyers.create');
    }

    public function store()
    {
        $this->validate($this->request, $this->rules());

        $buyer = Buyer::create($this->request->all());
        $buyer->user()->associate(\Auth::user());
        $buyer->save();

        $this->request->session()->flash('flash', ['success' => 'Dodano nowego kontrahenta!']);

        return redirect()->route('buyer.index');
    }

    protected function rules()
    {
        $rules = [
            'name'                => 'required|max:255',
            'address'             => 'max:255',
            'tax_id_number'       => 'max:255',
            'regon'               => 'max:255',
            'email'               => 'max:255',
            'www'                 => 'max:255',
            'phone'               => 'max:255',
            'bank_account_number' => 'max:255',
        ];

        return $rules;
    }

    public function edit(Buyer $buyer)
    {
        abort_if(!$buyer->isOwner(), 404);

        return view('buyers.edit', compact('buyer'));
    }

    public function update(Buyer $buyer)
    {
        abort_if(!$buyer->isOwner(), 404);
        $this->validate($this->request, $this->rules());
        $buyer->update($this->request->all());

        return redirect()->route('buyer.index');
    }

    public function destroy(Buyer $buyer)
    {
        abort_if(!$buyer->isOwner(), 404);
        $buyer->delete();

        return redirect()->route('buyer.index');
    }
}
