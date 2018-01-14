<?php

use KajStrom\DependencyConstraints\Dependency;
use PHPUnit\Framework\TestCase;

class DependencyTest extends TestCase
{
    public function testIsWithMatchingFQNReturnsTrue()
    {
        $dependency = new Dependency("Some\\Module\\ClassA");
        $this->assertTrue($dependency->is("Some\\Module\\ClassA"));
    }

    public function testIsWithNonMatchingFQNReturnsFalse()
    {
        $dependency = new Dependency("Some\\Module\\ClassA");
        $this->assertFalse($dependency->is("Some\\Module\\ClassB"));
    }
}
