<?php

declare(strict_types=1);


namespace App\Service;

class ParseConfig
{
    public string $file;
    public Object $config;

    public function __construct(string $file)
    {
        $this->file = $file;
    }

    public function parseFile(): void
    {
        $iniArray = parse_ini_file($this->file);
        $this->config = (object) $iniArray;
    }
    public function getConfig(): Object
    {
        return $this->config;
    }
}
