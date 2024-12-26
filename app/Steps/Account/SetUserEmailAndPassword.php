<?php

declare(strict_types=1);

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
        /** @var User|null $user */
        $user = Auth::user();
        $data = $request->all();

        if ($user !== null) {
            $user->update([
                'email' => $data['email'],
            ]);

            if ($request->has('password')) {
                $user->update([
                    'password' => bcrypt($data['password']),
                ]);
            }
        } else {
            $user = new User();
            $user->fill([
                'email' => $data['email'],
                'password' => bcrypt($data['password']),
            ]);
            $user->saveOrFail();

            Auth::login($user);
        }
    }

    public function rules(Request|null $request = null): array
    {
        /** @var User|null $user */
        $user = Auth::user();

        return [
            'email' => 'required|email|max:255|unique:users' . ($user !== null ? ",email,$user->id,id" : ''),
            'password' => ($user === null ? 'required|' : '') . 'min:8|confirmed',
        ];
    }
}