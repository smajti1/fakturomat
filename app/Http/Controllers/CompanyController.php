<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Models\Company;
use App\Models\User;
use Illuminate\Contracts\View\View;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CompanyController extends Controller
{

    private const JSON_LIST_LIMIT = 20;

    /**
     * @return Collection<int, Company>
     */
    public function jsonList(Request $request): Collection
    {
        $search = $request->searchText;
        /** @var User $user */
        $user = Auth::user();
        return $user
            ->company()
            ->where('name', 'ILIKE', $search . '%')
            ->limit(self::JSON_LIST_LIMIT)
            ->get();
    }

    public function edit(): View
    {
        /** @var User $user */
        $user = Auth::user();
        $company = $user->company;

        return view('companies.edit', compact('company'));
    }

    public function update(Request $request): RedirectResponse
    {
        $this->validate($request, $this->rules());
        /** @var User $user */
        $user = Auth::user();
        $company = $user->company;
        $company->update($request->all());
        $company->save();

        return redirect()->route('company.edit');
    }

    /**
     * @return array<string, string>
     */
    protected function rules(): array
    {
        /** @var User $user */
        $user = Auth::user();
        $company_id = $user->company->id;
        return [
            'name' => "required|max:255|unique:companies,name,$company_id",
            'address' => 'max:255',
            'tax_id_number' => 'max:255|tax_id_number: -',
            'regon' => 'max:255',
            'email' => 'max:255',
            'website' => 'max:255',
            'phone' => 'max:255',
            'bank_name' => 'max:255',
            'bank_account' => 'max:255',
        ];
    }
}
