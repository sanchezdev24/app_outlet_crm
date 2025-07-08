<?
// Domain/User/Contracts/UserRepositoryInterface.php
namespace App\Domain\Auth\Repositories;

use App\Domain\Auth\ValueObjects\Email;
use App\Domain\Auth\Entities\User as DomainUser;
use App\Infrastructure\Persistence\Eloquent\Models\User as EloquentUser;

interface UserRepositoryInterface
{
    public function findById(int $id): ?DomainUser;
    public function findByEmail(Email $email): ?DomainUser;
    public function save(DomainUser $id): ?DomainUser;
    public function toDomainEntity(EloquentUser $id): DomainUser;
    public function generateTokenForUser(DomainUser $user): string;
}