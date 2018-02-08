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

    public static function isSemicolon($token) : bool
    {
        return $token === ";";
    }

    public static function notSemicolon($token) : bool
    {
        return $token !== ";";
    }

    public static function isOpeningCurlyBrace($token) : bool
    {
        return $token === "{";
    }

    public static function isClosingCurlyBrace($token) : bool
    {
        return $token === "}";
    }

    public static function isOpeningParenthesis($token) : bool
    {
        if (is_array($token)) {
            return false;
        }

        return $token === "(";
    }

    public static function isComma($token) : bool
    {
        return $token === ",";
    }

    public static function notCommaOrWhitespace($token) : bool
    {
        if (self::isComma($token)) {
            return false;
        }

        return self::notWhiteSpace($token);
    }

    public static function notWhiteSpace($token) : bool
    {
        if (is_array($token)) {
            return T_WHITESPACE !== $token[0];
        }

        return true;
    }

    public static function isAs($token) : bool
    {
        if (!is_array($token)) {
            return false;
        }

        return T_AS === $token[0];
    }

    public static function notAs($token) : bool
    {
        if (!is_array($token)) {
            return false;
        }

        return T_AS !== $token[0];
    }

    public static function isNamespaceSeparator($token) : bool
    {
        if (!is_array($token)) {
            return false;
        }

        return T_NS_SEPARATOR === $token[0];
    }

    public static function isNamespace($token) : bool
    {
        if (!is_array($token)) {
            return false;
        }

        return T_NAMESPACE === $token[0];
    }
}
