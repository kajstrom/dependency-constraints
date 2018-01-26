<?php

use KajStrom\DependencyConstraints\Dependency;
use KajStrom\DependencyConstraints\SubModule;
use PHPUnit\Framework\TestCase;

class SubModuleTest extends TestCase
{
    public function testAddDependencyAddsDependencyToModule()
    {
        $module = new SubModule("Test\\Package");
        $module->addDependency(new Dependency("Some\\Dependency\\ClassA", "/some/file.php", 10));

        $this->assertTrue($module->dependsOnModule("Some\\Dependency"));
    }

    public function testAddDependencyWhenDependencyIsForASubModuleDoesNotAddDependency()
    {
        $module = new SubModule("Test\\Package");
        $module->addDependency(new Dependency("Test\\Package\\SubModule\\ClassA", "/some/file.php", 10));

        $this->assertFalse($module->dependsOnModule("Test\\Package\\SubModule"));
        $this->assertFalse($module->hasDependencyOn("Test\\Package\\SubModule\\ClassA"));
        $this->assertSame(0, $module->getDependencyCount());
    }

    public function testHasDependencyOnWhenDependencyExistsReturnsTrue()
    {
        $module = new SubModule("Test\\Package");
        $module->addDependency(new Dependency("Some\\Dependency\\ClassA", "/some/file.php", 10));

        $this->assertTrue($module->hasDependencyOn("Some\\Dependency\\ClassA"));
    }

    public function testHasDependencyOnWhenNoDependencyExistsReturnsFalse()
    {
        $module = new SubModule("Test\\Package");
        $module->addDependency(new Dependency("Some\\Dependency\\ClassA", "/some/file.php", 10));

        $this->assertFalse($module->hasDependencyOn("Some\\Dependency\\ClassB"));
    }

    public function testIsWhenModuleIsSameReturnsTrue()
    {
        $module = new SubModule("Test\\Package");

        $this->assertTrue($module->is("Test\\Package"));
    }

    public function testIsWhenModuleIsNotSameReturnsFalse()
    {
        $module = new SubModule("Test\\Package");

        $this->assertFalse($module->is("Some\\Other\\Module"));
    }

    public function testDescribeDependenciesToReturnsDescriptionOfDependencies()
    {
        $module = new SubModule("Test\\Package");
        $module->addDependency(new Dependency("Some\\Other\\ClassA", "/some/file.php", 5));
        $module->addDependency(new Dependency("Some\\Other\\ClassB", "/some/file2.php", 10));

        $expected = "Some\\Other\\ClassA in /some/file.php:5" . PHP_EOL
            . "Some\\Other\\ClassB in /some/file2.php:10" . PHP_EOL;

        $this->assertEquals($expected, $module->describeDependenciesTo("Some\\Other"));
    }

    public function testDescribeDependenciesToWhenThereAreNoDependenciesReturnsEmptyString()
    {
        $module = new SubModule("Test\\Package");

        $this->assertSame("", $module->describeDependenciesTo("Some\\Other"));
    }

    public function testBelongsToModuleWhenModuleBelongsToModule()
    {
        $module = new SubModule("Test\\Package");

        $this->assertTrue($module->belongsToModule("Test\\"));
    }
}
