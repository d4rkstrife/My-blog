<?php

declare(strict_types=1);

namespace App\Service;

final class DataValidation
{
    public function validate(string $string): string
    {
        $string = trim($string);
        $string = stripslashes($string);
        $string = htmlspecialchars($string);
        return $string;
    }

    public function isValidEntry(string $string): bool
    {
        if (
            !empty($string)
            && strlen($string) <= 20
            && strlen($string) > 1
            && preg_match("/^[a-zA-Zéèà]+$/", $string)
        ) {
            return true;
        }
        return false;
    }

    public function isValidMail(string $string): bool
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
