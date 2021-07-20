<?php

declare(strict_types=1);

namespace App\Service;

final class Counter
{
    private Database $database;
    private int $nbrPost;
    private int $nbrComment;
    private int $nbrUser;

    public function __construct(Database $database)
    {
        $this->database = $database;
    }

    public function countItems(): void
    {
        //compter le nbr de posts
        $req = $this->database->getPDO()->query("SELECT COUNT(*) as NbrPosts from post");
        $donnees = $req->fetch();
        $req->closeCursor();
        $this->setNbrPost($donnees['NbrPosts']);

        //compter le nombre d utilisateurs
        $req = $this->database->getPDO()->query("SELECT COUNT(*) as NbrUsers from user");
        $donnees = $req->fetch();
        $req->closeCursor();
        $this->setNbrUser($donnees['NbrUsers']);

        //compter le nombre de commentaires
        $req = $this->database->getPDO()->query("SELECT COUNT(*) as NbrComment from comment");
        $donnees = $req->fetch();
        $req->closeCursor();
        $this->setNbrComment($donnees['NbrComment']);
    }
    public function getNbrPost(): int
    {
        return $this->nbrPost;
    }

    public function setNbrPost($nbr): self
    {
        $this->nbrPost = (int) $nbr;
        return $this;
    }
    public function getNbrComment(): int
    {
        return $this->nbrComment;
    }

    public function setNbrComment($nbr): self
    {
        $this->nbrComment = (int) $nbr;
        return $this;
    }
    public function getNbrUser(): int
    {
        return $this->nbrUser;
    }

    public function setNbrUser($nbr): self
    {
        $this->nbrUser = (int) $nbr;
        return $this;
    }
}
