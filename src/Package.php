<?php
declare(strict_types=1);
namespace KajStrom\DependencyConstraints;

class Package
{
    /** @var string  */
    private $name = "";

    public function __construct(string $name)
    {
        $this->name = $name;
    }

    public function getName() : string
    {
        return $this->name;
    }

    public function dependsOnPackage(Package $package) : bool
    {
        return false;
    }
}