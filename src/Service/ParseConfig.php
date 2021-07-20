<?php

declare(strict_types=1);

namespace App\Service;

class ParseConfig
{
    public string $file;
    public object $config;

    public function __construct(string $file)
    {
        $this->file = $file;
    }

    public function parseFile(): void
    {
        $iniArray = parse_ini_file($this->file);
        $this->config = (object) $iniArray;
    }
    public function getConfig(): object
    {
        return $this->config;
    }
}
