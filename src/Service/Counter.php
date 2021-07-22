<?php

declare(strict_types=1);

namespace App\Service;

final class Counter
{
    private Database $database;
    private int $nbrPost;
    private int $nbrComment;
    private int $nbrUser;
    private int $nbrPage;

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
        $this->setNbrPost((int) $donnees['NbrPosts']);

        //compter le nombre d utilisateurs
        $req = $this->database->getPDO()->query("SELECT COUNT(*) as NbrUsers from user");
        $donnees = $req->fetch();
        $req->closeCursor();
        $this->setNbrUser((int) $donnees['NbrUsers']);

        //compter le nombre de commentaires
        $req = $this->database->getPDO()->query("SELECT COUNT(*) as NbrComment from comment");
        $donnees = $req->fetch();
        $req->closeCursor();
        $this->setNbrComment((int) $donnees['NbrComment']);
    }

    public function getNbrPage(): int
    {
        return $this->nbrPage;
    }

    public function setNbrPage(int $nbrPost, int $maxPerPage): self
    {
        $this->nbrPage = $nbrPost / $maxPerPage;
        return $this;
    }

    public function getNbrPost(): int
    {
        return $this->nbrPost;
    }

    public function setNbrPost(int $nbr): self
    {
        $this->nbrPost = $nbr;
        return $this;
    }
    public function getNbrComment(): int
    {
        return $this->nbrComment;
    }

    public function setNbrComment(int $nbr): self
    {
        $this->nbrComment = $nbr;
        return $this;
    }
    public function getNbrUser(): int
    {
        return $this->nbrUser;
    }

    public function setNbrUser(int $nbr): self
    {
        $this->nbrUser = $nbr;
        return $this;
    }
}
