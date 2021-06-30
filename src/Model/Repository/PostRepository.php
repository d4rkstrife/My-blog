<?php

declare(strict_types=1);

namespace App\Model\Repository;

use App\Model\Entity\Post;
use App\Model\Entity\User;
use App\Service\Database;
use App\Model\Repository\Interfaces\EntityRepositoryInterface;

use \PDO;

final class PostRepository implements EntityRepositoryInterface
{
    private Database $database;

    public function __construct(Database $database)
    {
        $this->database = $database;
    }

    public function find(int $id): ?Post
    {
        return null;
    }

    public function findOneBy(array $criteria, array $orderBy = null): ?Post
    {
        $stmt = $this->database->getPDO()->prepare('
        SELECT * FROM post
        INNER JOIN user
        ON post.fk_user = user.user_id
        WHERE post.id=:id');
        $stmt->execute($criteria);
        $data = $stmt->fetch();

        if ($data === null) {
            return null;
        }

        $post = new Post((int)$data['id'], $data['title'], $data['content'], $data['chapo']);
        $user = new User((int) $data['fk_user'], (string) $data['pseudo'], (string) $data['mail'], (string) $data['password']);
        $post->setAutor($user);
        $post->setDate($data['date']);
        return $post;
    }

    public function findBy(array $criteria, array $orderBy = null, int $limit = null, int $offset = null): ?array
    {
        return null;
    }

    public function findAll(): ?array
    {
        $stmt = $this->database->getPDO()->prepare('
        SELECT * FROM post
        INNER JOIN user
        ON post.fk_user = user.user_id');
        $stmt->execute();
        $data = $stmt->fetchAll();
        if ($data === null) {
            return null;
        }
        $posts = [];
        foreach ($data as $post) {
            $postObj = new Post((int)$post['id'], $post['title'], $post['content'], $post['chapo']);
            $user = new User((int) $post['fk_user'], (string) $post['pseudo'], (string) $post['mail'], (string) $post['password']);
            $postObj->setAutor($user);
            $postObj->setDate($post['date']);
            $posts[] = $postObj;
        }
        return $posts;
    }

    public function create(object $post): bool
    {
        return false;
    }

    public function update(object $post): bool
    {
        return false;
    }

    public function delete(object $post): bool
    {
        return false;
    }
}
