<?php
declare(strict_types=1);

namespace KajStrom\DependencyConstraints;


class PackageRegistry
{
    /** @var Package[] */
    private $packages = [];

    public function add(Package $package)
    {
        $this->packages[] = $package;
    }

    public function has(string $package) : bool
    {
        return false;
    }

    public function get(string $package) : Package
    {

    }
}