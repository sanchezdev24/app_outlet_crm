<?php

namespace App\Application\Handlers\Commands\Auth;

use App\Application\Commands\Auth\RegisterCommand;
use App\Domain\Auth\Repositories\UserRepositoryInterface;
use App\Domain\Auth\Entities\User;
use App\Domain\Auth\ValueObjects\Email;
use App\Domain\Auth\ValueObjects\Password;

class RegisterCommandHandler
{
    public function __construct(
        private UserRepositoryInterface $userRepository
    ) {}

    public function handle(RegisterCommand $command): array
    {
        $email = new Email($command->email);
        
        if ($this->userRepository->findByEmail($email)) {
            throw new \Exception('Email already exists');
        }

        $password = new Password($command->password);
        
        $user = new User(
            0, // Will be set by repository
            $command->name,
            $email,
            $password,
            $command->role
        );

        $savedUser = $this->userRepository->save($user);
        //$token = $savedUser->createToken('auth-token')->plainTextToken;
        $token = $this->userRepository->generateTokenForUser($savedUser);

        return [
            'user' => [
                'id' => $savedUser->getId(),
                'name' => $savedUser->getName(),
                'email' => $savedUser->getEmail()->getValue(),
                'role' => $savedUser->getRole()
            ],
            'token' => $token
        ];
    }
}