<?php

namespace App\Steps\Account;

use App\Company;
use Illuminate\Http\Request;
use Smajti1\Laravel\Step;

class SetCompanySecondaryData extends Step
{

    public static $label = 'Dodatkowe dane firmy';
    public static $slug = 'dodatkowe-dane-fimry';
    public static $view = 'wizard.account._step_set_company_secondary_data';

    public function process(Request $request)
    {
        $user = \Auth::user();
        $data = $request->all();

        if ($user && $this->wizard->dataHas('company_id')) {
            $company = $this->wizard->dataGet('company_id');
            $company = Company::where('id', $company)->first();
            if ($company) {
                $company->update([
                    'email'   => $data['company_email'],
                    'website' => $data['website'],
                    'phone'   => $data['phone'],
                ]);
            }

            $this->saveProgress($request);
        }
    }

    public function rules(Request $request = null)
    {
        return [
            'email'   => 'max:255',
            'website' => 'max:255',
            'phone'   => 'max:255',
        ];
    }
}