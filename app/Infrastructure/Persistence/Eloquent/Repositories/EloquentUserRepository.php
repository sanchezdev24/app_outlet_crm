<?php

namespace App\Infrastructure\Persistence\Eloquent\Repositories;

use App\Domain\Auth\Entities\User as DomainUser;
use App\Domain\Auth\Repositories\UserRepositoryInterface;
use App\Domain\Auth\ValueObjects\Email;
use App\Domain\Auth\ValueObjects\Password;
use App\Infrastructure\Persistence\Eloquent\Models\User as EloquentUser;

class EloquentUserRepository implements UserRepositoryInterface
{
    public function __construct(
        private EloquentUser $model
    ) {}

    public function findById(int $id): ?DomainUser
    {
        $user = $this->model->find($id);
        if (!$user) {
            return null;
        }

        return $this->toDomainEntity($user);
    }

    public function findByEmail(Email $email): ?DomainUser
    {
        $user = $this->model->where('email', $email->getValue())->first();
        if (!$user) {
            return null;
        }

        return $this->toDomainEntity($user);
    }

    public function save(DomainUser $user): DomainUser
    {
        $eloquentUser = $this->model->updateOrCreate(
            ['id' => $user->getId()],
            [
                'name' => $user->getName(),
                'email' => $user->getEmail()->getValue(),
                'password' => $user->getPassword()->getHashedValue(),
                'role' => $user->getRole(),
            ]
        );

        return $this->toDomainEntity($eloquentUser);
    }

    private function toDomainEntity(EloquentUser $user): DomainUser
    {
        return new DomainUser(
            $user->id,
            $user->name,
            new Email($user->email),
            Password::fromHash($user->password),
            $user->role,
            $user->created_at,
            $user->updated_at
        );
    }
}