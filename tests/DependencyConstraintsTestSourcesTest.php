<?php

use KajStrom\DependencyConstraints\DependencyConstraints;
use PHPUnit\Framework\TestCase;

class DependencyConstraintsTestSourcesTest extends TestCase
{
    /** @var  DependencyConstraints */
    private static $dc;

    public static function setUpBeforeClass()
    {
        self::$dc = new DependencyConstraints(__DIR__ . "/files");
    }

    public function testGetModuleOnTestFilesReturnsModuleThatHasDependency()
    {
        $module = self::$dc->getModule("Test\\Package");

        $this->assertTrue($module->dependsOnModule("KajStrom\\DependencyConstraints"));
    }

    public function testGetModuleWithNonExistentModuleNameReturnsNull()
    {
        $module = self::$dc->getModule("No\\Such\\Module");

        $this->assertNull($module);
    }

    public function testGetModuleOnTestFilesProvidesDescriptionForDependencies()
    {
        $module = self::$dc->getModule("Test\\Package");

        $this->assertContains("FileWithFQNUsages.php:8", $module->describeDependenciesTo("KajStrom"));
    }
}
