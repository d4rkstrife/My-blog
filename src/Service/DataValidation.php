<?php

declare(strict_types=1);

namespace App\Service;

final class DataValidation
{
    public function validate(string $data): string
    {
        $data = trim($data);
        $data = stripslashes($data);
        $data = htmlspecialchars($data);
        return $data;
    }
}
