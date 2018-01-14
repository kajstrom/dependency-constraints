<?php

use KajStrom\DependencyConstraints\Dependency;
use KajStrom\DependencyConstraints\Module;
use PHPUnit\Framework\TestCase;

class ModuleTest extends TestCase
{
    public function testAddDependencyAddsDependencyToModule()
    {
        $module = new Module("Test\\Package");
        $module->addDependency(new Dependency("Some\\Dependency\\ClassA"));

        $this->assertTrue($module->dependsOnModule("Some\\Dependency"));
    }

    public function testHasDependencyOnWhenDependencyExistsReturnsTrue()
    {
        $module = new Module("Test\\Package");
        $module->addDependency(new Dependency("Some\\Dependency\\ClassA"));

        $this->assertTrue($module->hasDependencyOn("Some\\Dependency\\ClassA"));
    }

    public function testHasDependencyOnWhenNoDependencyExistsReturnsFalse()
    {
        $module = new Module("Test\\Package");
        $module->addDependency(new Dependency("Some\\Dependency\\ClassA"));

        $this->assertFalse($module->hasDependencyOn("Some\\Dependency\\ClassB"));
    }
}
