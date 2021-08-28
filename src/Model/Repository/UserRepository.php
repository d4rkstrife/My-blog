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
        $stmt = $this->database->getPDO()->prepare('select * from user where user_id=:id');
        $stmt->bindValue('id', $id);
        $stmt->execute();
        $data = $stmt->fetch();

        if ($data === false) {
            return null;
        }
        $user = new User();
        $user->setId((int) $data['user_id'])
            ->setName($data['name'])
            ->setDate($data['inscription_date'])
            ->setSurname($data['surname'])
            ->setPseudo($data['pseudo'])
            ->setEmail($data['mail'])
            ->setPassword($data['password'])
            ->setGrade($data['grade'])
            ->setState((int) $data['is_validate'])
            ->setRegistrationKey($data['registration_key']);
        return $user;
    }

    public function findOneBy(array $criteria, array $orderBy = null): ?User
    {
        $sql = 'select * from user ';
        if ($orderBy !== null) {
            $sql .= " ORDER BY $orderBy[0] DESC";
        }
        if (array_key_exists('email', $criteria)) {
            $sql .= " WHERE mail=:email";
        } elseif (array_key_exists('pseudo', $criteria)) {
            $sql .= " WHERE pseudo=:pseudo";
        }


        $stmt = $this->database->getPDO()->prepare($sql);
        $stmt->execute($criteria);
        $data = $stmt->fetch();

        if ($data === false) {
            return null;
        }
        $user = new User();
        $user
            ->setId((int) $data['user_id'])
            ->setName($data['name'])
            ->setDate($data['inscription_date'])
            ->setSurname($data['surname'])
            ->setPseudo($data['pseudo'])
            ->setEmail($data['mail'])
            ->setPassword($data['password'])
            ->setGrade($data['grade'])
            ->setState((int) $data['is_validate'])
            ->setRegistrationKey($data['registration_key']);
        return $user;
    }

    public function findBy(array $criteria, array $orderBy = null, int $limit = null, int $offset = null): ?array
    {

        $sql = '
        SELECT * from user ';
        if ($orderBy !== null) {
            $sql .= "ORDER BY " . $orderBy['order'] . " DESC ";
        };
        if ($limit !== null) {
            $sql .= "LIMIT " . $limit;
        };
        if ($offset !== null) {
            $sql .= " OFFSET " . $offset;
        };
        if ($criteria !== null) {
            $sql .= 'WHERE ';
            if (array_key_exists('grade1', $criteria)) {
                $sql .= "grade = :grade1 ";
            }
            if (array_key_exists('grade2', $criteria)) {
                $sql .= "OR grade = :grade2 ";
            }
        }
        $stmt = $this->database->getPDO()->prepare($sql);
        $stmt->execute($criteria);
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
    /**
     * @param User $user
     */
    public function create(object $user): bool
    {
        $stmt = $this->database->getPDO()->prepare('
            INSERT INTO `user`(`name`, `surname`, `pseudo`, `mail`, `password`, `registration_key`)
            VALUES (:name, :surname, :pseudo, :mail, :password, :registrationKey)
            ');
        $stmt->bindValue('name', $user->getName());
        $stmt->bindValue('surname', $user->getSurname());
        $stmt->bindValue('pseudo', $user->getPseudo());
        $stmt->bindValue('mail', $user->getEmail());
        $stmt->bindValue('registrationKey', $user->getRegistrationKey());
        $stmt->bindValue('password', password_hash($user->getPassword(), PASSWORD_DEFAULT));

        return $stmt->execute();
    }

    /**
     * @param User $user
     */
    public function update(object $user): bool
    {
        $stmt = $this->database->getPDO()->prepare('
        UPDATE `user` 
        SET `name`=:name,`surname`=:surname,`pseudo`=:pseudo,`mail`=:mail,`grade`=:grade,`password`=:password,`is_validate`=:isValidate, registration_key=:registrationKey 
        WHERE `user_id` = :id');
        $stmt->bindValue('name', $user->getName());
        $stmt->bindValue('surname', $user->getSurname());
        $stmt->bindValue('pseudo', $user->getPseudo());
        $stmt->bindValue('mail', $user->getEmail());
        $stmt->bindValue('grade', $user->getGrade());
        $stmt->bindValue('password', $user->getPassword());
        $stmt->bindValue('isValidate', $user->getState());
        $stmt->bindValue('id', $user->getId());
        $stmt->bindValue('registrationKey', $user->getRegistrationKey());

        return $stmt->execute();
    }

    /**
     * @param User $user
     */
    public function delete(object $user): bool
    {
        $stmt = $this->database->getPDO()->prepare('
        DELETE FROM `user` WHERE user_id = :id
        ');
        $stmt->bindValue('id', $user->getId());
        return $stmt->execute();
    }

    public function count(array $criteria = null): int
    {
        $stmt = $this->database->getPDO()->prepare("SELECT COUNT(*) as Nbr from user");

        $countType = '';
        if ($criteria !== null) {
            $key = array_keys($criteria);
            $countType = $key[0];
        }

        if ($countType === 'email') {
            $stmt = $this->database->getPDO()->prepare('SELECT COUNT(*) as Nbr from user where mail=:email');
        } elseif ($countType === 'pseudo') {
            $stmt = $this->database->getPDO()->prepare('SELECT COUNT(*) as Nbr from user where pseudo=:pseudo');
        }

        $stmt->execute($criteria);
        $donnees = $stmt->fetch();
        return (int) $donnees['Nbr'];
    }
}
