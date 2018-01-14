<?php

use KajStrom\DependencyConstraints\SubModule;
use KajStrom\DependencyConstraints\ModuleRegistry;
use PHPUnit\Framework\TestCase;

class ModuleRegistryTest extends TestCase
{
    public function testAddAddsModuleToRegistry()
    {
        $registry = new ModuleRegistry();

        $module = new SubModule("Some\\Module");

        $registry->add($module);

        $this->assertTrue($registry->has($module->getName()));
        $this->assertSame($module, $registry->get($module->getName()));
        $this->assertSame(1, $registry->size());
    }

    public function testAddDoesNotAddModuleToRegistryIfAlreadyIsInRegistry()
    {
        $registry = new ModuleRegistry();

        $module = new SubModule("Some\\Module");

        $registry->add($module);
        $registry->add($module);

        $this->assertSame(1, $registry->size());
    }

    public function testGetSubModulesOfReturnsCompositeModuleOfSubModules()
    {
        $registry = new ModuleRegistry();

        $registry->add(new SubModule("Some\\Module\\Module1"));
        $registry->add(new SubModule("Some\\Module\\Module2"));
        $registry->add(new SubModule("Some\\Module\\Module3"));
        $registry->add(new SubModule("Some\\Unrelated\\Module"));

        $module = $registry->getSubModulesOf("Some\\Module\\");

        $this->assertSame(3, $module->moduleCount());
    }
}
