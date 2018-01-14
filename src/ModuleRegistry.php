<?php
declare(strict_types=1);

namespace KajStrom\DependencyConstraints;


class ModuleRegistry
{
    /** @var Module[] */
    private $packages = [];

    public function add(Module $package)
    {
        $this->packages[] = $package;
    }

    public function has(string $package) : bool
    {
        return false;
    }

    public function get(string $package) : Module
    {

    }
}