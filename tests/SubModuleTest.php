<?php

use KajStrom\DependencyConstraints\Dependency;
use KajStrom\DependencyConstraints\SubModule;
use PHPUnit\Framework\TestCase;

class SubModuleTest extends TestCase
{
    public function testAddDependencyAddsDependencyToModule()
    {
        $module = new SubModule("Test\\Package");
        $module->addDependency(new Dependency("Some\\Dependency\\ClassA"));

        $this->assertTrue($module->dependsOnModule("Some\\Dependency"));
    }

    public function testHasDependencyOnWhenDependencyExistsReturnsTrue()
    {
        $module = new SubModule("Test\\Package");
        $module->addDependency(new Dependency("Some\\Dependency\\ClassA"));

        $this->assertTrue($module->hasDependencyOn("Some\\Dependency\\ClassA"));
    }

    public function testHasDependencyOnWhenNoDependencyExistsReturnsFalse()
    {
        $module = new SubModule("Test\\Package");
        $module->addDependency(new Dependency("Some\\Dependency\\ClassA"));

        $this->assertFalse($module->hasDependencyOn("Some\\Dependency\\ClassB"));
    }

    public function testIsWhenModuleIsSameReturnsTrue()
    {
        $module = new SubModule("Test\\Package");

        $this->assertTrue($module->is("Test\\Package"));
    }

    public function testIsWhenModuleIsSameReturnsFalse()
    {
        $module = new SubModule("Test\\Package");

        $this->assertFalse($module->is("Some\\Other\\Module"));
    }

    public function testBelongsToModuleWhenModuleBelongsToModule()
    {
        $module = new SubModule("Test\\Package");

        $this->assertTrue($module->belongsToModule("Test\\"));
    }

    public function testBelongsToModuleWhenModuleNameDoesNotEndInNSSeparatorThrowsInvalidArgumentException()
    {
        $module = new SubModule("Test\\Package");

        $this->expectException(InvalidArgumentException::class);
        $module->belongsToModule("Test");
    }
}