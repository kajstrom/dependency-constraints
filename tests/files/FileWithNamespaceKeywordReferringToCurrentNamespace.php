<?php
namespace Test\Package;


class FileWithNamespaceKeywordReferringToCurrentNamespace
{
    public function __construct()
    {
        new namespace\FileWithUseFunctionClause();
    }
}