<?php

use KajStrom\DependencyConstraints\DependencyConstraints;
use PHPUnit\Framework\TestCase;

class DependencyConstraintsOwnSourceTest extends TestCase
{
    /** @var  DependencyConstraints */
    private static $dc;

    public static function setUpBeforeClass()
    {
        self::$dc = new DependencyConstraints(dirname(__DIR__) . "/src");
    }

    public function testGetModuleReturnsModuleThatIsNotCoupledToNonExistentModule()
    {
        $module = self::$dc->getModule("KajStrom\\DependencyConstraints");

        $this->assertFalse($module->dependsOnModule("No\\Such\\Module"));
    }

    public function testGetModuleOnModuleThatContainsOnlySubModulesShouldReturnCompositeModule()
    {
        $module = self::$dc->getModule("KajStrom");

        $this->assertNotNull($module);
    }

    public function testGetModuleReturnsModuleThatIsTheModuleRequested()
    {
        $module = self::$dc->getModule("KajStrom");

        $this->assertTrue($module->is("KajStrom"));
    }
}
