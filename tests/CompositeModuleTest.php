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

    public function testIsWhenMOduleIsNotTheModuleBeingTestedReturnsFalse()
    {
        $cm = new CompositeModule("Some\\Module\\");

        $this->assertFalse($cm->is("Totally\\Different\\Module\\"));
    }
}
