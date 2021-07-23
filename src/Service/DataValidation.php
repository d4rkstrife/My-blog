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

    public function validateName(string $string): bool
    {
        if (
            !empty($string)
            && strlen($string) <= 20
            && strlen($string) > 1
            && preg_match("/^[A-Za-z '-]+$/", $string)
        ) {
            return true;
        }
        return false;
    }

    public function validateMail(string $string): bool
    {
        if (
            !empty($string)
            && filter_var($string, FILTER_VALIDATE_EMAIL)
        ) {
            return true;
        }
        return false;
    }
}
