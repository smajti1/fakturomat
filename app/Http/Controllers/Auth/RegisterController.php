<?php

declare(strict_types=1);

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\Company;
use App\Models\User;
use Illuminate\Foundation\Auth\RegistersUsers;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator as ValidatorFacade;
use Illuminate\Validation\Validator;

class RegisterController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Register Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles the registration of new users as well as their
    | validation and creation. By default this controller uses a trait to
    | provide this functionality without requiring any additional code.
    |
    */

    use RegistersUsers;

    /**
     * Where to redirect users after registration.
     *
     * @var string
     */
    protected $redirectTo = '/panel';

    public function __construct()
    {
        $this->redirectTo = url()->route('panel');
        $this->middleware('guest');
    }

    /**
     * @param array<string, mixed> $data
     */
    protected function validator(array $data): Validator
    {
        $company_unique_id = '';
        if (Auth::check()) {
            /** @var User $user */
            $user = Auth::user();
            $company_unique_id = ',' . $user->company->id;
        }
        return ValidatorFacade::make($data, [
            'company_name' => "required|max:255|unique:companies,name$company_unique_id",
            'email' => 'required|email|max:255|unique:users',
            'password' => 'required|min:8|confirmed',
        ]);
    }

    /**
     * @param array<string, mixed> $data
     */
    protected function create(array $data): ?User
    {
        $user = null;

        DB::transaction(static function () use (&$user, $data) {
            $user = new User();
            $user->fill([
                'email' => $data['email'],
                'password' => bcrypt($data['password']),
            ]);

            $company = new Company();
            $company->fill([
                'name' => $data['company_name'],
                'city' => $data['city'],
                'zip_code' => $data['zip_code'],
                'street' => $data['street'],
                'tax_id_number' => $data['tax_id_number'],
                'regon' => $data['regon'],
                'email' => $data['company_email'],
                'website' => $data['website'],
                'phone' => $data['phone'],
                'bank_account' => $data['bank_account'],
            ]);

            $company->user->associate($user);
            $user->save();
            $company->save();
        });

        return $user;
    }
}
