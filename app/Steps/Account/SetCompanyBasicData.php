<?php

declare(strict_types=1);

namespace App\Steps\Account;

use App\Models\Company;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Smajti1\Laravel\Step;

class SetCompanyBasicData extends Step
{

    public static $label = 'Podstawowe dane firmy';
    public static $slug = 'podstawowe-dane-firmy';
    public static $view = 'wizard.account._step_set_company_basic_data';

    public function process(Request $request): void
    {
        $company = null;
        /** @var User|null $user */
        $user = Auth::user();
        $data = $request->all();
        if ($this->wizard->dataHas('company_id')) {
            $company = $this->wizard->dataGet('company_id');
            $company = Company::whereId($company)->first();
        }

        if ($user === null) {
            return;
        }
        if ($company !== null) {
            $company->update([
                'name' => $data['company_name'],
                'tax_id_number' => $data['tax_id_number'],
                'regon' => $data['regon'],
                'bank_account' => $data['bank_account'],
            ]);
        } else {
            $company = new Company();
            $company->fill([
                'name' => $data['company_name'],
                'tax_id_number' => $data['tax_id_number'],
                'regon' => $data['regon'],
                'bank_account' => $data['bank_account'],
            ]);

            $company->user()->associate($user);
            $company->save();
        }

        $this->saveProgress($request, ['company_id' => $company->id]);

    }

    public function rules(Request $request = null): array
    {
        $company_unique_id = '';
        /** @var User $user */
        $user = Auth::user();
        if ($user->company !== null) {
            $company_unique_id = ',' . $user->company->id;
        }
        return [
            'company_name' => "required|max:255|unique:companies,name$company_unique_id",
            'tax_id_number' => 'max:255|tax_id_number: -',
            'regon' => 'max:255',
            'bank_account' => 'max:255',
        ];
    }
}