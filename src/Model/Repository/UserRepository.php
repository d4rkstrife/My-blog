<?php

declare(strict_types=1);

namespace App\Model\Repository;

use App\Service\Database;
use App\Model\Entity\User;
use App\Model\Repository\Interfaces\EntityRepositoryInterface;

final class UserRepository implements EntityRepositoryInterface
{
    private Database $database;


    public function __construct(Database $database)
    {
        $this->database = $database;
    }

    public function find(int $id): ?User
    {
        return null;
    }

    public function findOneBy(array $criteria, array $orderBy = null): ?User
    {
        $stmt = $this->database->getPDO()->prepare('select * from user where mail=:email');
        $stmt->execute($criteria);
        $data = $stmt->fetch();

        // réfléchir à l'hydratation des entités;
        return $data === null ? null : new user((int)$data['user_id'], $data['pseudo'], $data['mail'], $data['password']);
    }

    public function findBy(array $criteria, array $orderBy = null, int $limit = null, int $offset = null): ?array
    {
        return null;
    }

    public function findAll(): ?array
    {
        return null;
    }

    public function create(object $user): bool
    {
        return false;
    }

    public function update(object $user): bool
    {
        return false;
    }

    public function delete(object $user): bool
    {
        return false;
    }
}
