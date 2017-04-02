<?php

namespace App\Steps\Account;

use App\Company;
use Illuminate\Http\Request;
use Smajti1\Laravel\Step;

class SetCompanyBasicData extends Step
{

    public static $label = 'Podstawowe dane firmy';
    public static $slug = 'podstawowe-dane-firmy';
    public static $view = 'wizard.account._step_set_company_basic_data';

    public function process(Request $request)
    {
        $company = null;
        $user = \Auth::user();
        $data = $request->all();
        if ($this->wizard->dataHas('company_id')) {
            $company = $this->wizard->dataGet('company_id');
            $company = Company::where('id', $company)->first();
        }

        if ($user) {
            if ($company) {
                $company->update([
                    'name'          => $data['company_name'],
                    'tax_id_number' => $data['tax_id_number'],
                    'regon'         => $data['regon'],
                    'bank_account'  => $data['bank_account'],
                ]);
            } else {
                $company = Company::create([
                    'name'          => $data['company_name'],
                    'tax_id_number' => $data['tax_id_number'],
                    'regon'         => $data['regon'],
                    'bank_account'  => $data['bank_account'],
                ]);

                $company->user()->associate($user);
                $company->save();
            }

            $this->saveProgress($request, ['company_id' => $company->id]);
        }

    }

    public function rules(Request $request = null)
    {
        return [
            'company_name'  => 'required|max:255|unique:companies,name',
            'tax_id_number' => 'max:255',
            'regon'         => 'max:255',
            'bank_account'  => 'max:255',
        ];
    }
}