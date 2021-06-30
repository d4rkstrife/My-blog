<?php

declare(strict_types=1);

namespace App\Model\Repository;

use App\Service\Database;
use App\Model\Entity\User;
use App\Model\Entity\Comment;
use App\Model\Entity\Interfaces\EntityObjectInterface;
use App\Model\Repository\Interfaces\EntityRepositoryInterface;

use \PDO;

final class CommentRepository implements EntityRepositoryInterface
{
    private Database $database;

    public function __construct(Database $database)
    {
        $this->database = $database;
    }

    public function find(int $id): ?Comment
    {
        return null;
    }

    public function findOneBy(array $criteria, array $orderBy = null): ?Comment
    {
        return null;
    }

    public function findBy(array $criteria, array $orderBy = null, int $limit = null, int $offset = null): ?array
    {
        $stmt = $this->database->getPDO()->prepare('
        SELECT * FROM comment
        INNER JOIN user
        ON comment.fk_user = user.user_id
        WHERE comment.fk_post=:idPost');
        $stmt->execute($criteria);
        $data = $stmt->fetchAll();

        if ($data === null) {
            return null;
        }

        // réfléchir à l'hydratation des entités;
        $comments = [];
        foreach ($data as $comment) {
            $newComment = new Comment((int)$comment['id'], $comment['content'], (int)$comment['fk_post']);
            $user = new User((int) $comment['fk_user'], (string) $comment['pseudo'], (string) $comment['mail'], (string) $comment['password']);
            $newComment->setUser($user);
            $newComment->setDate($comment['date']);
            $comments[] = $newComment;
        }

        return $comments;
    }

    public function findAll(): ?array
    {
        return null;
    }

    public function create(object $comment): bool
    {
        return false;
    }

    public function update(object $comment): bool
    {
        return false;
    }

    public function delete(object $comment): bool
    {
        return false;
    }
}
