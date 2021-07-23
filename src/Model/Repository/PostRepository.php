<?php

declare(strict_types=1);

namespace App\Model\Repository;

use App\Model\Entity\Post;
use App\Model\Entity\User;
use App\Service\Database;
use App\Model\Repository\Interfaces\EntityRepositoryInterface;
use PDO;

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

        if ($data == null) {
            return null;
        }

        $post = new Post();
        $user = new User();
        $user
            ->setId((int) $data['fk_user'])
            ->setPseudo($data['pseudo'])
            ->setName($data['name'])
            ->setSurname($data['surname'])
            ->setEmail($data['mail']);
        $post->setAutor($user)
            ->setId((int) $data['id'])
            ->setTitle($data['title'])
            ->setContent($data['content'])
            ->setChapo($data['chapo'])
            ->setModif($data['date_modif'])
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
        ON post.fk_user = user.user_id
        ORDER BY post.date
        DESC');
        $stmt->execute();
        $data = $stmt->fetchAll();


        if ($data == null) {
            return null;
        }
        $posts = [];
        $users = [];
        foreach ($data as $post) {
            $postObj = new Post();
            //si $post['fk_user'] n existe pas dans $users alors
            //création $user
            //$users[$post['fk_user']] = $user
            //fin si 
            //$postObject->setAutor($users[$post['fk_user']])
            $user = new User();
            $user
                ->setId((int) $post['fk_user'])
                ->setPseudo($post['pseudo'])
                ->setName($post['name'])
                ->setSurname($post['surname'])
                ->setEmail($post['mail']);
            $postObj
                ->setAutor($user)
                ->setId((int) $post['id'])
                ->setTitle($post['title'])
                ->setContent($post['content'])
                ->setChapo($post['chapo'])
                ->setModif($post['date_modif'])
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
    public function count(): int
    {
        $req = $this->database->getPDO()->query("SELECT COUNT(*) as NbrPosts from post");
        $donnees = $req->fetch();
        $req->closeCursor();
        return (int) $donnees['NbrPosts'];
    }
}
