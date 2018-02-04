<?php

namespace Test\Package {
    class FileWithMultipleNameSpacesBracketedSyntax
    {
        public function __construct()
        {
            new \Test\OtherPackage\FromOtherPackage();
        }
    }
}

namespace Test\OtherPackage {
    class FromOtherPackage
    {
        public function __construct()
        {
            new \Test\Package\FileWithMultipleNameSpacesBracketedSyntax();
        }
    }
}

namespace {
    class InGlobalNameSpace
    {
        public function __construct()
        {
            new \Test\Package\FileWithMultipleNameSpacesBracketedSyntax();
        }
    }
}