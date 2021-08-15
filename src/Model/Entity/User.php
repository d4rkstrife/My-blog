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
    private string $date;
    private int $state;
    private ?string $registrationKey;

    public function getId(): int
    {
        return $this->id;
    }

    public function setId(int $id): self
    {
        $this->id = $id;
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
    public function getDate(): string
    {
        return $this->date;
    }

    public function setDate(string $date): self
    {
        $this->date = $date;
        return $this;
    }
    public function getState(): int
    {
        return $this->state;
    }

    public function setState(int $state): self
    {
        $this->state = $state;
        return $this;
    }
    public function getRegistrationKey(): ?string
    {
        return $this->registrationKey;
    }

    public function setRegistrationKey(?string $registrationKey): self
    {
        $this->registrationKey = $registrationKey;
        return $this;
    }
}
