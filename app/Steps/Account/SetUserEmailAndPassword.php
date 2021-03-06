<?php

namespace App\Steps\Account;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Smajti1\Laravel\Step;

class SetUserEmailAndPassword extends Step
{

    public static $label = 'E-mail i hasło';
    public static $slug = 'email-haslo';
    public static $view = 'wizard.account._step_set_user_email_and_password';

    public function process(Request $request)
    {
        $user = Auth::user();
        $data = $request->all();

        if ($user) {
            $user->update([
                'email' => $data['email'],
            ]);

            if ($request->has('password')) {
                $user->update([
                    'password' => bcrypt($data['password']),
                ]);
            }
        } else {
        	/** @var User $user */
            $user = User::create([
                'email'    => $data['email'],
                'password' => bcrypt($data['password']),
            ]);

            Auth::login($user);
        }
    }

    public function rules(Request $request = null): array
    {
        $user = Auth::user();

        return [
            'email'    => 'required|email|max:255|unique:users' . ($user ? ",email,$user->id,id" : ''),
            'password' => (!$user ? 'required|' : '') . 'min:8|confirmed',
        ];
    }
}