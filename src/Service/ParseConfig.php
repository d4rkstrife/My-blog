<?php

declare(strict_types=1);

namespace App\Service;

class ParseConfig
{
    public string $file;
    public array $config;

    public function __construct(string $file)
    {
        $this->file = $file;
    }

    public function parseFile()
    {
        $iniArray = parse_ini_file($this->file);
        $this->config = $iniArray;
    }
    public function getConfig(string $param): string
    {
        return $this->config[$param];
    }
}
