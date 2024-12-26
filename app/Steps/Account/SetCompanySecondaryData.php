<?php

declare(strict_types=1);

namespace App\Steps\Account;

use App\Models\Company;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Smajti1\Laravel\Step;

class SetCompanySecondaryData extends Step
{

    public static $label = 'Dodatkowe dane firmy';
    public static $slug = 'dodatkowe-dane-fimry';
    public static $view = 'wizard.account._step_set_company_secondary_data';

    public function process(Request $request)
    {
        /** @var User|null $user */
        $user = Auth::user();
        $data = $request->all();

        if ($user !== null && $this->wizard->dataHas('company_id')) {
            $company = $this->wizard->dataGet('company_id');
            $company = Company::whereId($company)->first();
            $company?->update([
                'email' => $data['company_email'],
                'website' => $data['website'],
                'phone' => $data['phone'],
            ]);

            $this->saveProgress($request);
        }
    }

    public function rules(Request|null $request = null): array
    {
        return [
            'email' => 'max:255',
            'website' => 'max:255',
            'phone' => 'max:255',
        ];
    }
}