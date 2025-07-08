<?php

namespace App\Application\Handlers\Commands\Auth;

use App\Application\Commands\Auth\LoginCommand;
use App\Domain\Auth\Repositories\UserRepositoryInterface;
use App\Domain\Auth\ValueObjects\Email;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class LoginCommandHandler
{
    public function __construct(
        private UserRepositoryInterface $userRepository
    ) {}

    public function handle(LoginCommand $command): array
    {
        $email = new Email($command->email);
        $user = $this->userRepository->findByEmail($email);

        if (!$user || !Hash::check($command->password, $user->getPassword()->getHashedValue())) {
            throw new \Exception('Invalid credentials');
        }

        //$token = $user->createToken('auth-token')->plainTextToken;
        $token = $this->userRepository->generateTokenForUser($user);

        return [
            'user' => [
                'id' => $user->getId(),
                'name' => $user->getName(),
                'email' => $user->getEmail()->getValue(),
                'role' => $user->getRole()
            ],
            'token' => $token
        ];
    }
}