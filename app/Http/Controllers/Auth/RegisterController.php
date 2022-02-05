<?php

declare(strict_types=1);

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Repositories\UserRepository\UserRepositoryInterface;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Contracts\Validation\Validator as ContractValidation;
use Illuminate\Validation\ValidationException;

class RegisterController extends Controller
{
    private UserRepositoryInterface $userRepository;

    public function __construct(UserRepositoryInterface $userRepository)
    {
        $this->userRepository = $userRepository;
    }

    public function register(Request $request)
    {
        try {
            $validator = $this->validator($request::all());
            $payload = $validator->validate();
        } catch (ValidationException $e) {
            return view('welcome');
        }


        $user = $this->userRepository->create($payload);


        auth()->login($user);

        return redirect()->route('dashboard');
    }

    /**
     * @param array<string, string> $payload
     */
    private function createUser(array $payload): User
    {
        return User::create([
            'name' => $payload['name'],
            'email' => $payload['email'],
            'password' => Hash::make($payload['password']),
        ]);
    }

    /**
     * @param array<string, string> $data
     */
    private function validator(array $data): ContractValidation
    {
        return Validator::make($data, [
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users,email'],
            'password' => ['required', 'string', 'min:8'],
        ]);
    }
}
