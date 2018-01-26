<?php

use KajStrom\DependencyConstraints\Dependency;
use PHPUnit\Framework\TestCase;

class DependencyTest extends TestCase
{
    public function testIsWithMatchingFQNReturnsTrue()
    {
        $dependency = new Dependency("Some\\Module\\ClassA", "/path/to/file.php", 10);
        $this->assertTrue($dependency->is("Some\\Module\\ClassA"));
    }

    public function testIsWithNonMatchingFQNReturnsFalse()
    {
        $dependency = new Dependency("Some\\Module\\ClassA", "/path/to/file.php", 10);
        $this->assertFalse($dependency->is("Some\\Module\\ClassB"));
    }

    public function testToStringReturnsDependencyDescription()
    {
        $dependency = new Dependency("Some\\Module\\ClassA", "/path/to/file.php", 10);
        $this->assertSame("Some\\Module\\ClassA in /path/to/file.php:10", (string)$dependency);
    }
}
