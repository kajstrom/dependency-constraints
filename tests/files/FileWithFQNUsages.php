<?php
/**
 * Created by PhpStorm.
 * User: Kaitsu
 * Date: 17.1.2018
 * Time: 18:43
 */

namespace Test\Package;


class FileWithFQNUsages
{
    public function __construct(\KajStrom\DependencyConstraints\Dependency $dependency)
    {
        $a = new \KajStrom\DependencyConstraints\ModuleRegistry();
    }
}