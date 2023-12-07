<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\User;
use Illuminate\Contracts\View\View;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Http\RedirectResponse;
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

    public function index(): View
    {
        $activeTaxes = activeTaxes();

        return view('products.index', compact('activeTaxes'));
    }

    public function create(): View
    {
        $measureUnits = config('invoice.measure_units.' . config('app.locale'));
        $activeTaxes = activeTaxes();

        return view('products.create', compact('measureUnits', 'activeTaxes'));
    }

    /**
     * @return Collection<int, Product>
     */
    public function jsonList(): Collection
    {
        $search = $this->request->searchText;

        /** @var User $user */
        $user = Auth::user();
        $list = $user
            ->products()
            ->where('name', 'ILIKE', $search . '%')
            ->limit(self::JSON_LIST_LIMIT)
            ->get();
        if (!$list->count()) {
            $list = $user
                ->products()
                ->limit(self::JSON_LIST_LIMIT)
                ->get();
        }

        return $list;
    }

    public function store(): RedirectResponse
    {
        $this->validate($this->request, $this->rules());

        /** @var User $user */
        $user = Auth::user();
        $productData = $this->request->only('name', 'measure_unit', 'price', 'tax_percent');
        $product = new Product();
        $product->fill($productData);
        $product->user()->associate($user);
        $product->save();

        return redirect()->route('product.index');
    }

    /**
     * @return array<string, string>
     */
    protected function rules(): array
    {
        return [
            'name' => 'required|max:255',
            'measure_unit' => 'max:255',
            'price' => 'required|numeric',
            'tax_percent' => 'required',
        ];
    }

    public function edit(Product $product): View
    {
        abort_if(!$product->isOwner(), 404);
        $activeTaxes = activeTaxes();

        return view('products.edit', compact('product', 'activeTaxes'));
    }

    public function update(Product $product, Request $request): RedirectResponse
    {
        $this->validate($request, $this->rules());
        abort_if(!$product->isOwner(), 404);
        $product->update($request->all());

        return redirect()->route('product.index');
    }

    public function destroy(Product $product): RedirectResponse
    {
        abort_if(!$product->isOwner(), 404);
        $product->delete();

        return redirect()->route('product.index');
    }
}
