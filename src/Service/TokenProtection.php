<?php

declare(strict_types=1);

namespace App\Service;

use App\Service\Http\Session\Session;

class TokenProtection
{
    private Session $session;
    private string $token;

    public function __construct(Session $session)
    {
        $this->session = $session;
        $this->token = '';
    }

    public function generateToken(): void
    {
        $this->token = md5((string) time());
    }

    public function setToken(): void
    {
        $this->session->set('token', $this->token);
    }
    public function getToken(): ?string
    {
        return $this->token;
    }
    public function removeToken(): void
    {
        $this->session->remove('token');
    }
}
