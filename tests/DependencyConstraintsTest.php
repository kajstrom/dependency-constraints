<?php
/**
 * Created by PhpStorm.
 * User: Kaitsu
 * Date: 14.1.2018
 * Time: 15:54
 */

use KajStrom\DependencyConstraints\DependencyConstraints;
use PHPUnit\Framework\TestCase;

class DependencyConstraintsTest extends TestCase
{
    public function testWIP()
    {
        $dc = new DependencyConstraints(dirname(__DIR__) . "/src");
        $module = $dc->getModule("KajStrom\\DependencyConstraints");

        $this->assertFalse($module->dependsOnModule("No\\Such\\Module"));
    }

    public function testGetModuleOnModuleThatContainsOnlySubModulesShouldReturnCompositeModule()
    {
        $dc = new DependencyConstraints(dirname(__DIR__) . "/src");
        $module = $dc->getModule("KajStrom");

        $this->assertNotNull($module);
    }

    public function testGetModuleReturnsModuleThatIsTheModuleRequested()
    {
        $dc = new DependencyConstraints(dirname(__DIR__) . "/src");
        $module = $dc->getModule("KajStrom");

        $this->assertTrue($module->is("KajStrom"));
    }

    public function testGetModuleOnTestFilesReturnsModuleThatHasDependency()
    {
        $dc = new DependencyConstraints(__DIR__ . "/files");
        $module = $dc->getModule("Test\\Package");

        $this->assertTrue($module->dependsOnModule("KajStrom\\DependencyConstraints"));
    }

    public function testGetModuleWithNonExistentModuleNameReturnsNull()
    {
        $dc = new DependencyConstraints(__DIR__ . "/files");
        $module = $dc->getModule("No\\Such\\Module");

        $this->assertNull($module);
    }
}
