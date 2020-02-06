<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\Company;
use App\Models\User;
use Illuminate\Foundation\Auth\RegistersUsers;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

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

    protected function validator(array $data)
    {
        $company_unique_id = '';
        if (Auth::check()) {
            $company_unique_id = ',' . Auth::user()->company->id;
        }
        return Validator::make($data, [
            'company_name' => "required|max:255|unique:companies,name$company_unique_id",
            'email'        => 'required|email|max:255|unique:users',
            'password'     => 'required|min:8|confirmed',
        ]);
    }

    protected function create(array $data): ?User
    {
        $user = null;

        DB::transaction(static function () use (&$user, $data) {
            $user = User::create([
                'email'    => $data['email'],
                'password' => bcrypt($data['password']),
            ]);

            $company = Company::create([
                'name'          => $data['company_name'],
                'city'          => $data['city'],
                'zip_code'      => $data['zip_code'],
                'street'        => $data['street'],
                'tax_id_number' => $data['tax_id_number'],
                'regon'         => $data['regon'],
                'email'         => $data['company_email'],
                'website'       => $data['website'],
                'phone'         => $data['phone'],
                'bank_account'  => $data['bank_account'],
            ]);

            $company->user()->associate($user);
            $company->save();
        });

        return $user;
    }
}
