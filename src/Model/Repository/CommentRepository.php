<?php

declare(strict_types=1);

namespace App\Model\Repository;

use App\Service\Database;
use App\Model\Entity\User;
use App\Model\Entity\Comment;
use App\Model\Entity\Interfaces\EntityObjectInterface;
use App\Model\Repository\Interfaces\EntityRepositoryInterface;
use PDO;

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
        WHERE comment.fk_post=:idPost 
        AND comment.state=:state');
        $stmt->execute($criteria);
        $data = $stmt->fetchAll();

        if ($data == null) {
            return null;
        }

        // réfléchir à l'hydratation des entités;
        $comments = [];
        $users = [];
        foreach ($data as $comment) {
            $newComment = new Comment();
            //tester si l utilisateur existe déjà
            $user = new User();
            $user
                ->setId($comment['fk_user'])
                ->setPseudo($comment['pseudo'])
                ->setName($comment['name'])
                ->setSurname($comment['surname'])
                ->setEmail($comment['mail']);
            $newComment
                ->setId($comment['id'])
                ->setText($comment['content'])
                ->setUser($user)
                ->setIdPost($comment['fk_post'])
                ->setDate($comment['date']);
            $comments[] = $newComment;
        }

        return $comments;
    }

    public function findAll(): ?array
    {
        $stmt = $this->database->getPDO()->prepare('
        SELECT * FROM comment
        INNER JOIN user
        ON comment.fk_user = user.user_id
        WHERE comment.state = 0 ');
        $stmt->execute();
        $data = $stmt->fetchAll();

        if ($data == null) {
            return null;
        }

        // réfléchir à l'hydratation des entités;
        $comments = [];
        $users = [];
        foreach ($data as $comment) {
            $newComment = new Comment();
            //tester si l utilisateur existe déjà
            $user = new User();
            $user
                ->setId($comment['fk_user'])
                ->setPseudo($comment['pseudo'])
                ->setName($comment['name'])
                ->setSurname($comment['surname'])
                ->setEmail($comment['mail']);
            $newComment
                ->setId($comment['id'])
                ->setText($comment['content'])
                ->setUser($user)
                ->setIdPost($comment['fk_post'])
                ->setDate($comment['date']);
            $comments[] = $newComment;
        }

        return $comments;
    }


    public function create(object $criteria): bool
    {
        $stmt = $this->database->getPDO()->prepare("
        INSERT INTO `comment` (`content`, `state`, `fk_user`, `fk_post`, `date`, `id`) 
        VALUES (:text, '0', :user, :idPost, CURRENT_TIMESTAMP, NULL);");
        $stmt->bindValue('text', $criteria->getText());
        $stmt->bindValue('idPost', $criteria->getIdPost());
        $stmt->bindValue('user', $criteria->getUser()->getId());
        $stmt->execute();
        return true;
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
