<?php

use KajStrom\DependencyConstraints\CompositeModule;
use KajStrom\DependencyConstraints\Dependency;
use KajStrom\DependencyConstraints\SubModule;
use PHPUnit\Framework\TestCase;

class CompositeModuleTest extends TestCase
{
    public function testDependsOnModuleReturnsTrueWhenASubmoduleHasDependency()
    {
        $cm = new CompositeModule("Some\\Module\\");
        $module = new SubModule("Some\\Module\\Mod1");
        $module->addDependency(new Dependency("Some\\OtherModule\\ClassA", "/some/file.php", 10));

        $cm->add($module);
        $cm->add(new SubModule("Some\\Module\\Mod2"));

        $this->assertTrue($cm->dependsOnModule("Some\\OtherModule"));
    }

    public function testDependsOnModuleReturnsFalseWhenSubmodulesDoNotHaveADependency()
    {
        $cm = new CompositeModule("Some\\Module\\");
        $cm->add(new SubModule("Some\\Module\\Mod1"));
        $cm->add(new SubModule("Some\\Module\\Mod2"));

        $this->assertFalse($cm->dependsOnModule("Some\\OtherModule", "/some/file.php", 10));
    }

    public function testHasDependencyOnWhenSubModuleHasDependencyOnAClassReturnsTrue()
    {
        $cm = new CompositeModule("Some\\Module\\");

        $module = new SubModule("Some\\Module\\Mod1");
        $module->addDependency(new Dependency("Some\\OtherModule\\ClassA", "/some/file.php", 10));

        $cm->add($module);
        $cm->add(new SubModule("Some\\Module\\Mod2"));

        $this->assertTrue($cm->hasDependencyOn("Some\\OtherModule\\ClassA"));
    }

    public function testHasDependencyOnWhenSubModulesHaveNoDependencyOnAClassReturnsFalse()
    {
        $cm = new CompositeModule("Some\\Module\\");
        $cm->add(new SubModule("Some\\Module\\Mod1"));

        $this->assertFalse($cm->hasDependencyOn("Some\\OtherModule\\ClassA"));
    }

    public function testIsWhenModuleIsTheModuleBeingTestedReturnsTrue()
    {
        $cm = new CompositeModule("Some\\Module\\");

        $this->assertTrue($cm->is("Some\\Module\\"));
    }

    public function testIsWhenModuleIsNotTheModuleBeingTestedReturnsFalse()
    {
        $cm = new CompositeModule("Some\\Module\\");

        $this->assertFalse($cm->is("Totally\\Different\\Module\\"));
    }

    public function testDescribeDependenciesToReturnsDependencyDescriptionsOfSubModules()
    {
        $cm = new CompositeModule("Some");
        $module = new SubModule("Some\\ModuleA");
        $module->addDependency(new Dependency("Something\\Else\\ClassA", "/some/file.php", 5));
        $cm->add($module);

        $another = new SubModule("Some\\ModuleB");
        $another->addDependency(new Dependency("Something\\Else\\ClassB", "/some/file2.php", 15));
        $cm->add($another);

        $expected = "Something\\Else\\ClassA in /some/file.php:5" . PHP_EOL
            . "Something\\Else\\ClassB in /some/file2.php:15" . PHP_EOL;

        $this->assertSame($expected, $cm->describeDependenciesTo("Something\\Else"));
    }
}
