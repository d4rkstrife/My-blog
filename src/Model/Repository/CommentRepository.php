<?php

declare(strict_types=1);

namespace App\Model\Repository;

use App\Service\Database;
use App\Model\Entity\User;
use App\Model\Entity\Comment;
use App\Model\Repository\Interfaces\EntityRepositoryInterface;

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
        $stmt = $this->database->getPDO()->prepare('
        SELECT * FROM comment    
        WHERE comment.id=:id
        ');
        $stmt->execute($criteria);
        $data = $stmt->fetch();

        $comment = new Comment();
        $comment
            ->setId((int) $data['id']);
        return $comment;
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

        if ($data === null) {
            return null;
        }

        // réfléchir à l'hydratation des entités;
        $comments = [];
        foreach ($data as $comment) {
            $newComment = new Comment();
            //tester si l utilisateur existe déjà
            $user = new User();
            $user
                ->setId((int) $comment['fk_user'])
                ->setPseudo($comment['pseudo'])
                ->setName($comment['name'])
                ->setSurname($comment['surname'])
                ->setEmail($comment['mail']);
            $newComment
                ->setId((int) $comment['id'])
                ->setText($comment['content'])
                ->setUser($user)
                ->setIdPost((int) $comment['fk_post'])
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
        foreach ($data as $comment) {
            $newComment = new Comment();
            //tester si l utilisateur existe déjà
            $user = new User();
            $user
                ->setId((int) $comment['fk_user'])
                ->setPseudo($comment['pseudo'])
                ->setName($comment['name'])
                ->setSurname($comment['surname'])
                ->setEmail($comment['mail']);
            $newComment
                ->setId((int) $comment['id'])
                ->setText($comment['content'])
                ->setUser($user)
                ->setIdPost((int) $comment['fk_post'])
                ->setDate($comment['date']);
            $comments[] = $newComment;
        }

        return $comments;
    }

    /**
     * @param Comment $comment
     */
    public function create(object $comment): bool
    {
        $stmt = $this->database->getPDO()->prepare("
        INSERT INTO `comment` (`content`, `state`, `fk_user`, `fk_post`, `date`, `id`) 
        VALUES (:text, '0', :user, :idPost, CURRENT_TIMESTAMP, NULL);");
        $stmt->bindValue('text', $comment->getText());
        $stmt->bindValue('idPost', $comment->getIdPost());
        $stmt->bindValue('user', $comment->getUser()->getId());
        $stmt->execute();
        return true;
    }

    /**
     * @param Comment $comment
     */
    public function update(object $comment): bool
    {
        $stmt = $this->database->getPDO()->prepare('
        UPDATE `comment` SET `state`= 1 WHERE id = :id
        ');
        $stmt->bindValue('id', $comment->getId());
        $stmt->execute();
        return true;
    }

    /**
     * @param Comment $comment
     */
    public function delete(object $comment): bool
    {
        $stmt = $this->database->getPDO()->prepare('
        DELETE FROM `comment` WHERE id = :id
        ');
        $stmt->bindValue('id', $comment->getId());
        $stmt->execute();
        return true;
    }
    public function count(array $criteria = null): int
    {
        $stmt = $this->database->getPDO()->prepare("SELECT COUNT(*) as Nbr from comment");
        $stmt->execute();
        $donnees = $stmt->fetch();
        return (int) $donnees['Nbr'];
    }
}
