<?php

declare(strict_types=1);

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Repositories\Exceptions\EntityAlreadyExistsException;
use App\Repositories\UserRepository\UserRepositoryInterface;
use Illuminate\Http\RedirectResponse;
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

    public function register(Request $request): RedirectResponse
    {
        try {
            $validator = $this->validator($request::all());
            $payload = $validator->validate();
            $user = $this->userRepository->create($payload);
        } catch (ValidationException | EntityAlreadyExistsException $e) {
            return redirect('/login')
                ->with('message', $e->getMessage())
                ->with('register', false)
                ->withInput(['email' => $request::input('email'), 'register' => false]);
        }

        auth()->login($user);

        return redirect()->route('dashboard');
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
