<?php

declare(strict_types=1);


namespace App\Service;

class ParseConfig


{
    public string $file;

    public function __construct(string $file)
    {
        $this->file = $file;
    }

    public function parseFile()
    {
        $iniArray = parse_ini_file($this->file);
        $iniObject = (object) $iniArray;

        return $iniObject;
    }
}
