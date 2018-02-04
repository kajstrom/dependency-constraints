<?php

namespace Test\Package;


class FileWithMultipleNameSpacesSimpleCombinationSyntax
{
    public function __construct()
    {
        new \Test\OtherPackage\SomeOtherClass();
    }
}

namespace Test\OtherPackage;

class SomeOtherClass
{
    public function __construct()
    {
        new \Test\Package\FileWithMultipleNameSpacesSimpleCombinationSyntax();
    }
}