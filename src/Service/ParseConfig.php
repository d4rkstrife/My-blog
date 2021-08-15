<?php

declare(strict_types=1);

namespace App\Service;

class ParseConfig
{
    public string $file;
    public ?array $config;

    public function __construct(string $file)
    {
        $this->file = $file;
    }

    public function parseFile(): void
    {
        $parsedFile = parse_ini_file($this->file);
        if ($parsedFile !== false) {
            $this->config = $parsedFile;
        }
    }
    public function getConfig(string $param): string
    {
        return $this->config[$param];
    }
}
