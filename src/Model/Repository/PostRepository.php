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
        $sql = '
        SELECT * FROM post
        INNER JOIN user
        ON post.fk_user = user.user_id
        WHERE post.id=:id';
        if ($orderBy !== null) {
            $sql .= ' ORDER BY ' . $orderBy['order'] . ' ' . $orderBy['type'];
        }
        $stmt = $this->database->getPDO()->prepare($sql);
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
        $sql = "
        SELECT * FROM post
        INNER JOIN user    
        ON post.fk_user = user.user_id ";
        if ($criteria !== []) {
            $sql .= "WHERE post.id = :id";
        };
        if ($orderBy !== null) {
            $sql .= "ORDER BY " . $orderBy['order'] . " DESC ";
        };
        if ($limit !== null) {
            $sql .= "LIMIT " . $limit;
        };
        if ($offset !== null) {
            $sql .= " OFFSET " . $offset;
        };
        $stmt = $this->database->getPDO()->prepare($sql);
        $stmt->execute($criteria);
        $data = $stmt->fetchAll();

        if ($data === false) {
            return null;
        }
        $posts = [];
        $users = [];
        foreach ($data as $post) {
            $postObj = new Post();

            if (!in_array($post['fk_user'], $users)) {
                $user = new User();
                $user
                    ->setId((int) $post['fk_user'])
                    ->setPseudo($post['pseudo'])
                    ->setName($post['name'])
                    ->setSurname($post['surname'])
                    ->setEmail($post['mail']);
                $users[(int) $post['fk_user']] = $user;
            }

            $postObj
                ->setAutor($users[$post['fk_user']])
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


        if ($data === false) {
            return null;
        }
        $posts = [];
        $users = [];
        foreach ($data as $post) {
            $postObj = new Post();
            if (!in_array($post['fk_user'], $users)) {
                $user = new User();
                $user
                    ->setId((int) $post['fk_user'])
                    ->setPseudo($post['pseudo'])
                    ->setName($post['name'])
                    ->setSurname($post['surname'])
                    ->setEmail($post['mail']);
                $users[(int) $post['fk_user']] = $user;
            }

            $postObj
                ->setAutor($users[$post['fk_user']])
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

    /**
     * @param Post $post
     */
    public function create(object $post): bool
    {
        $stmt = $this->database->getPDO()->prepare('
        INSERT INTO post(`title`, `chapo`, `content`,`fk_user`)
        VALUES (:title,:chapo, :content, :userId)');
        $stmt->bindValue('title', $post->getTitle());
        $stmt->bindValue('chapo', $post->getChapo());
        $stmt->bindValue('content', $post->getContent());
        $stmt->bindValue('userId', $post->getAutor()->getId());

        return $stmt->execute();
    }

    /**
     * @param Post $post
     */
    public function update(object $post): bool
    {
        $stmt = $this->database->getPdo()->prepare('
        UPDATE `post` 
        SET `title`=:title,`chapo`=:chapo,`content`=:content,`date_modif`= current_timestamp, `fk_user` = :autor
        WHERE `id` = :id
        ');
        $stmt->bindValue('title', $post->getTitle());
        $stmt->bindValue('chapo', $post->getChapo());
        $stmt->bindValue('content', $post->getContent());
        $stmt->bindValue('id', $post->getId());
        $stmt->bindValue('autor', $post->getAutor()->getId());

        return $stmt->execute();
    }

    /**
     * @param Post $post
     */
    public function delete(object $post): bool
    {
        $stmt = $this->database->getPDO()->prepare('DELETE FROM `post` WHERE `id`= :id');
        $stmt->bindValue('id', $post->getId());
        return $stmt->execute();
    }

    public function count(array $criteria = null): int
    {
        $stmt = $this->database->getPDO()->prepare("SELECT COUNT(*) as NbrPosts from post");
        $stmt->execute();
        $donnees = $stmt->fetch();
        return (int) $donnees['NbrPosts'];
    }
}
