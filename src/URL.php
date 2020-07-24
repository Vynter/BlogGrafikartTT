<?php

namespace App;

use Exception;

class URL
{
    public static function getInt(string $name, int $default = null)
    {
        if (!isset($_GET[$name])) return $default;
        if ($_GET[$name] === '0')  return 0;

        if (!filter_var($_GET[$name], FILTER_VALIDATE_INT)) {
            throw new Exception("Le paramètre $name n'est pas un entier");
        }
        return (int)$_GET[$name];
    }
    public static function getPositiveInt(string $name, int $default = null): ?int
    {
        $currentPage = self::getInt($name, $default);
        if ($currentPage <= 0 && $currentPage !== null) {
            throw new Exception("Le paramétre $name n'est pas positif");
        }
        return $currentPage;
    }
}