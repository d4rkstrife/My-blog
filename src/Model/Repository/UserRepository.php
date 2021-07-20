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

        if ($data === false) {
            return null;
        }
        $user = new User();
        $user
            ->setId((int) $data['user_id'])
            ->setPseudo($data['pseudo'])
            ->setEmail($data['mail'])
            ->setPassword($data['password'])
            ->setGrade($data['grade']);
        return $user;
    }

    public function findBy(array $criteria, array $orderBy = null, int $limit = null, int $offset = null): ?array
    {
        return null;
    }

    public function findAll(): ?array
    {
        $stmt = $this->database->getPDO()->prepare('
        SELECT * FROM user
        ');
        $stmt->execute();
        $data = $stmt->fetchAll();

        if ($data == null) {
            return null;
        }
        $users = [];
        foreach ($data as $user) {
            $newUser = new User();
            $newUser
                ->setId((int) $user['user_id'])
                ->setPseudo($user['pseudo'])
                ->setName($user['name'])
                ->setSurname($user['surname'])
                ->setEmail($user['mail'])
                ->setGrade($user['grade'])
                ->setDate($user['inscription_date'])
                ->setState((int) $user['is_validate']);

            $users[] = $newUser;
        }
        return $users;
    }

    public function create(object $user): bool
    {
        $data = $user->all();

        $stmt = $this->database->getPDO()->prepare('
        INSERT INTO `user`(`name`, `surname`, `pseudo`, `mail`, `password`)
        VALUES (:name, :surname, :pseudo, :mail, :password)
        ');
        $stmt->bindValue('name', $data['nom']);
        $stmt->bindValue('surname', $data['prenom']);
        $stmt->bindValue('pseudo', $data['pseudo']);
        $stmt->bindValue('mail', $data['email']);
        $stmt->bindValue('password', password_hash($data['password'], PASSWORD_DEFAULT));

        $stmt->execute();

        return true;
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
