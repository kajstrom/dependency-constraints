<?php
declare(strict_types=1);

use KajStrom\DependencyConstraints\DependencyConstraints;
use KajStrom\DependencyConstraints\Package;
use PHPUnit\Framework\TestCase;

class Test extends TestCase
{
    public function testSkeleton()
    {
        $dc = new DependencyConstraints("src/");

        /*$package = $dc->getPackage("KajStrom\\DependencyConstraints");

        $p1 = new Package("src/");
        $p2 = new Package("tests/");

        $this->assertTrue($p2->dependsOnPackage($p1));*/
    }
}
