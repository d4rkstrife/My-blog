<?php

declare(strict_types=1);

namespace App\Model\Entity;

use App\Model\Entity\Interfaces\EntityObjectInterface;

final class User
{
    private int $id;
    private string $email;
    private string $pseudo;
    private string $password;
    private string $name;
    private string $surname;
    private string $grade;

    public function getId(): int
    {
        return $this->id;
    }

    public function setId($id): self
    {
        $this->id = (int) $id;
        return $this;
    }
    public function getName(): string
    {
        return $this->name;
    }

    public function setName(string $name): self
    {
        $this->name = $name;
        return $this;
    }
    public function getSurname(): string
    {
        return $this->surname;
    }

    public function setSurname(string $surname): self
    {
        $this->surname = $surname;
        return $this;
    }
    public function getGrade(): string
    {
        return $this->grade;
    }
    public function setGrade(string $grade): self
    {
        $this->grade = $grade;
        return $this;
    }

    public function getPseudo(): string
    {
        return $this->pseudo;
    }

    public function setPseudo(string $pseudo): self
    {
        $this->pseudo = $pseudo;
        return $this;
    }

    public function getEmail(): string
    {
        return $this->email;
    }

    public function setEmail(string $email): self
    {
        $this->email = $email;
        return $this;
    }

    public function getPassword(): string
    {
        return $this->password;
    }

    public function setPassword(string $password): self
    {
        $this->password = $password;
        return $this;
    }
}
