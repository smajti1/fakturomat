<?php

namespace App\Http\Controllers\Auth;

use App\Company;
use App\Http\Controllers\Controller;
use App\User;
use Illuminate\Foundation\Auth\RegistersUsers;
use Illuminate\Support\Facades\DB;
use Validator;

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
     * Where to redirect users after login / registration.
     *
     * @var string
     */
    protected $redirectTo = '/home';

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest');
    }

    /**
     * Get a validator for an incoming registration request.
     *
     * @param  array  $data
     * @return \Illuminate\Contracts\Validation\Validator
     */
    protected function validator(array $data)
    {
        return Validator::make($data, [
            'company_name' => 'required|max:255|unique:companies,name',
            'email'        => 'required|email|max:255|unique:users',
            'password'     => 'required|min:6|confirmed',
        ]);
    }

    /**
     * Create a new user instance after a valid registration.
     *
     * @param  array  $data
     * @return User
     */
    protected function create(array $data)
    {
        $user = null;

        DB::transaction(function () use (&$user, $data) {
            $user = User::create([
                'email'    => $data['email'],
                'password' => bcrypt($data['password']),
            ]);

            $company = Company::create([
                'name'                => $data['company_name'],
                'street'              => $data['street'],
                'city'                => $data['city'],
                'post_code'           => $data['post_code'],
                'nip'                 => $data['nip'],
                'regon'               => $data['regon'],
                'email'               => $data['company_email'],
                'www'                 => $data['www'],
                'phone'               => $data['phone'],
                'bank_account_number' => $data['bank_account_number'],
            ]);

            $company->user()->associate($user);
        });

        return $user;
    }
}
