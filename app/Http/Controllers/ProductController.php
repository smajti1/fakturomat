<?php

namespace App\Http\Controllers;

use App\Product;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    const JSON_LIST_LIMIT = 20;
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

    public function jsonList()
    {
        $search = $this->request->searchText;
        $list = \Auth::user()
            ->products()
            ->where('name', 'LIKE', $search . '%')
            ->limit(self::JSON_LIST_LIMIT)
            ->get();

        return $list;
    }

    public function store()
    {
        $this->validate($this->request, $this->rules());

        $user = \Auth::user();
        $product = Product::create($this->request->all());
        $product->user()->associate($user);
        $product->save();

        return redirect()->route('product.index');
    }

    protected function rules()
    {
        return [
            'name'         => 'required|max:255',
            'measure_unit' => 'max:255',
            'price'        => 'required|integer',
            'vat'          => 'integer',
        ];
    }

    public function edit(Product $product)
    {
        abort_if(!$product->isOwner(), 404);
        return view('products.edit', compact('product'));
    }

    public function update(Product $product, Request $request)
    {
        $this->validate($request, $this->rules());
        abort_if(!$product->isOwner(), 404);
        $product->update($request->all());

        return redirect()->route('product.index');
    }

    public function destroy(Product $product)
    {
        abort_if(!$product->isOwner(), 404);
        $product->delete();

        return redirect()->route('product.index');
    }
}
