<?php

namespace Test\Package;


class FileWithFQNUsages
{
    public function __construct(\KajStrom\DependencyConstraints\Dependency $dependency)
    {
        $a = new \KajStrom\DependencyConstraints\ModuleRegistry();
    }
}