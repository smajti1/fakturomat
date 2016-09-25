<?php

namespace App\Http\Controllers;

use App\Product;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    protected $request;

    public function __construct(Request $request)
    {
        $this->request = $request;
    }

    public function index()
    {
        return view('products.index');
    }

    public function create()
    {
        return view('products.create');
    }

    public function store()
    {
        $rules = [
            'name'         => 'required|max:255',
            'pkwiu'        => 'max:255',
            'measure_unit' => 'max:255',
            'price'        => 'required|integer',
            'vat'          => 'integer',
        ];

        $this->validate($this->request, $rules);

        $user = \Auth::user();
        $product = Product::create($this->request->all());
        $product->user()->associate($user);
        $product->save();

        return redirect()->route('product.index');
    }

    public function show(Product $product)
    {
        //
    }

    public function edit(Product $product)
    {
        //
    }

    public function update(Product $product)
    {
        //
    }

    public function destroy(Product $product)
    {
        abort_if($product->isOwner(), 404);
        $product->delete();

        return redirect()->route('product.index');
    }
}
