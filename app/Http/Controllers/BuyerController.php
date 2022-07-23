<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Models\Buyer;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Session\Store;
use Illuminate\Support\Facades\Auth;

class BuyerController extends Controller
{
    protected Request $request;

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

        /** @var User $user */
        $user = Auth::user();
        $buyer = new Buyer();
        $buyer->fill($this->request->all());
        $buyer->user()->associate($user);
        $buyer->save();

		/** @var Store $session */
		$session = $this->request->session();
		$session->flash('flash', ['success' => 'Dodano nowego kontrahenta!']);

        return redirect()->route('buyer.index');
    }

    protected function rules(): array
    {
        return [
            'name'          => 'required|max:255',
            'city'          => 'max:255',
            'zip_code'      => 'max:255',
            'street'        => 'max:255',
            'tax_id_number' => 'max:255',
            'regon'         => 'max:255',
            'email'         => 'max:255',
            'website'       => 'max:255',
            'phone'         => 'max:255',
            'bank_account'  => 'max:255',
        ];
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
