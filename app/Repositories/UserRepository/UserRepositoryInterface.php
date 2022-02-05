<?php

declare(strict_types=1);

namespace App\Repositories\UserRepository;

use App\Models\User;

interface UserRepositoryInterface
{
    /**
     * @param array<string, string> $payload
     */
    public function create(array $userData): User;
}
