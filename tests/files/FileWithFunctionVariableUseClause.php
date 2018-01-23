<?php

namespace Test\Package;


class FileWithFunctionVariableUseClause
{
    public function __construct()
    {
        $myVar = 123;

        $functionOne = function() use ($myVar) {};
        $functionTwo = function() use($myVar) {};
    }
}