<?php
declare(strict_types=1);
namespace KajStrom\DependencyConstraints;

class SubModule implements Module
{
    /** @var string  */
    private $name = "";
    /** @var Dependency[] */
    private $dependencies = [];

    public function __construct(string $name)
    {
        $this->name = $name;
    }

    public function addDependency(Dependency $dependency) : void
    {
        $this->dependencies[] = $dependency;
    }

    public function getName() : string
    {
        return $this->name;
    }

    public function dependsOnModule(string $module) : bool
    {
        foreach ($this->dependencies as $dependency) {
            if ($dependency->belongsToModule($module)) {
                return true;
            }
        }

        return false;
    }

    public function hasDependencyOn(string $fqn) : bool
    {
        foreach ($this->dependencies as $dependency) {
            if ($dependency->is($fqn)) {
                return true;
            }
        }

        return false;
    }

    public function is(string $module) : bool
    {
        return $this->name === $module;
    }

    public function belongsToModule(string $module) : bool
    {
        if (substr($module, -1) !== "\\") {
            throw new \InvalidArgumentException("Module name $module does not end in namespace separator '\\'");
        }

        return strpos($this->name, $module) === 0;
    }
}