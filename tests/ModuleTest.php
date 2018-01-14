<?php

use KajStrom\DependencyConstraints\Dependency;
use KajStrom\DependencyConstraints\Module;
use PHPUnit\Framework\TestCase;

class ModuleTest extends TestCase
{
    public function testAddDependencyAddsDependencyToModule()
    {
        $package = new Module("Test\\Package");
        $package->addDependency(new Dependency("Some\\Dependency\\ClassA"));

        $this->assertTrue($package->dependsOnModule("Some\\Dependency"));
    }
}
