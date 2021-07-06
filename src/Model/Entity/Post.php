<?php

declare(strict_types=1);

namespace App\Model\Entity;

use App\Model\Entity\Interfaces\EntityObjectInterface;

final class Post
{
    private int $id;
    private string $title;
    private string $content;
    private string $chapo;
    private string $date;
    private string $modif;

    /*   public function __construct(int $id, string $title, string $text, string $chapo)
    {
        $this->id = $id;
        $this->title = $title;
        $this->text = $text;
        $this->chapo = $chapo;
    }*/

    public function getId(): int
    {
        return $this->id;
    }
    public function setId($id): self
    {
        $this->id = (int)$id;
        return $this;
    }

    public function getTitle(): string
    {
        return $this->title;
    }

    public function setTitle(string $title): self
    {
        $this->title = $title;
        return $this;
    }

    public function getContent(): string
    {
        return $this->content;
    }

    public function setContent(string $content): self
    {
        $this->content = $content;
        return $this;
    }
    public function getChapo(): string
    {
        return $this->chapo;
    }
    public function setChapo(string $chapo): self
    {
        $this->chapo = $chapo;
        return $this;
    }
    public function getAutor(): User
    {
        return $this->autor;
    }
    public function setAutor(User $autor): self
    {
        $this->autor = $autor;
        return $this;
    }
    public function getDate(): string
    {
        return $this->date;
    }
    public function setDate(string $date): self
    {
        $this->date = $date;
        return $this;
    }
    public function getModif(): string
    {
        return $this->modif;
    }
    public function setModif(string $modif): self
    {
        $this->modif = $modif;
        return $this;
    }
}
