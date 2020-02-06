<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ProductController extends Controller
{
    private const JSON_LIST_LIMIT = 10;
    protected Request $request;

    public function __construct(Request $request)
    {
        $this->request = $request;
    }

    public function index()
    {
        $activeTaxes = activeTaxes();

        return view('products.index', compact('activeTaxes'));
    }

    public function create()
    {
        $measureUnits = config('invoice.measure_units.' . config('app.locale'));
        $activeTaxes = activeTaxes();

        return view('products.create', compact('measureUnits', 'activeTaxes'));
    }

    public function jsonList()
    {
        $search = $this->request->searchText;
        $list = Auth::user()
            ->products()
            ->where('name', 'ILIKE', $search . '%')
            ->limit(self::JSON_LIST_LIMIT)
            ->get();
        if (!$list->count()) {
            $list = Auth::user()
                ->products()
                ->limit(self::JSON_LIST_LIMIT)
                ->get();
        }

        return $list;
    }

    public function store()
    {
        $this->validate($this->request, $this->rules());

        $user = Auth::user();
        $productData = $this->request->only('name', 'measure_unit', 'price', 'tax_percent');
        $product = Product::create($productData);
        $product->user()->associate($user);
        $product->save();

        return redirect()->route('product.index');
    }

    protected function rules(): array
    {
        return [
            'name'         => 'required|max:255',
            'measure_unit' => 'max:255',
            'price'        => 'required|numeric',
            'tax_percent'  => 'required',
        ];
    }

    public function edit(Product $product)
    {
        abort_if(!$product->isOwner(), 404);
        $activeTaxes = activeTaxes();

        return view('products.edit', compact('product', 'activeTaxes'));
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
