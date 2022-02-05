<?php

declare(strict_types=1);

namespace App\Http\Controllers\Auth;

use Illuminate\Contracts\Validation\Validator as ContractValidation;
use Illuminate\Support\Facades\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\ValidationException;

class LoginController
{
    public function show()
    {
        return view('welcome');
    }

    public function login(Request $request)
    {
        try {
            $validator = $this->validator($request::all());
            $userData = $validator->validate();
        } catch (ValidationException $e) {
            return redirect('/login')
                ->with('error', $e->errors())
                ->withInput(['email']);
        }

        if (false === auth()->attempt($userData)) {
            return redirect('/login')
                ->with('message', 'Not valid login credentials.')
                ->withInput([
                    'email' => $userData['email']
                ]);
        }


        return redirect()->to('/');
    }

    /**
     * @param array<string, string> $data
     */
    private function validator(array $data): ContractValidation
    {
        return Validator::make($data, [
            'email' => ['required', 'string', 'email', 'max:255'],
            'password' => ['required', 'string', 'min:8'],
        ]);
    }
}
