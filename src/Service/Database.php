<?php

declare(strict_types=1);

// class pour gérer la connection à la base de donnée
namespace App\Service;

use PDO;

// *** exemple fictif d'accès à la base de données
class Database
{
    private string $dbName;
    private string $dbUser;
    private string $dbPass;
    private string $dbHost;

    private ?\PDO $pdo;

    public function __construct($config)
    {
        $this->dbName = $config->dbName;
        $this->dbUser = $config->dbUser;
        $this->dbPass = $config->dbPass;
        $this->dbHost = $config->dbHost;
        $this->pdo = null;
    }

    public function getPDO(): \PDO
    {
        if ($this->pdo === null) {
            $pdo = new PDO("mysql:dbname={$this->dbName};host={$this->dbHost};port=3307", $this->dbUser, $this->dbPass);
            $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            $this->pdo = $pdo;
        }
        return $this->pdo;
    }
}
