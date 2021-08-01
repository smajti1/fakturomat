<?php

declare(strict_types=1);

namespace App\Steps\Account;

use App\Models\Company;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Smajti1\Laravel\Step;

class SetAddress extends Step
{

    public static $label = 'Adres';
    public static $slug = 'adres';
    public static $view = 'wizard.account._step_set_address';

    public function process(Request $request)
    {
        /** @var User|null $user */
        $user = Auth::user();
        if ($user && $this->wizard->dataHas('company_id')) {
            $company = $this->wizard->dataGet('company_id');
            $company = Company::whereId($company)->first();

            $company->update(
                $request->only(['city', 'zip_code', 'street'])
            );

            $this->saveProgress($request);
        }
    }

    public function rules(Request $request = null): array
    {
        return [
            'city'     => 'max:255',
            'zip_code' => 'max:255',
            'street'   => 'max:255',
        ];
    }
}