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

        $post = new Post();
        $user = new User();
        $user
            ->setId($data['fk_user'])
            ->setPseudo($data['pseudo'])
            ->setEmail($data['mail']);
        $post->setAutor($user)
            ->setId($data['id'])
            ->setTitle($data['title'])
            ->setContent($data['content'])
            ->setChapo($data['chapo'])
            ->setDate($data['date']);
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
            $postObj = new Post();
            $user = new User();
            $user
                ->setId($post['fk_user'])
                ->setPseudo($post['pseudo'])
                ->setEmail($post['mail']);
            $postObj
                ->setAutor($user)
                ->setId($post['id'])
                ->setTitle($post['title'])
                ->setContent($post['content'])
                ->setChapo($post['chapo'])
                ->setDate($post['date']);

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
