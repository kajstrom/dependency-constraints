<?php
declare(strict_types=1);

namespace KajStrom\DependencyConstraints\Token;

class Helpers
{
    public static function partOfQualifiedName($token) : bool
    {
        if (T_NS_SEPARATOR === $token[0]) {
            return true;
        }

        if (T_STRING === $token[0]) {
            return true;
        }

        return false;
    }

    public static function notSemicolon($token) : bool
    {
        return $token !== ";";
    }
}
