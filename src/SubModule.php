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
        if (!$dependency->belongsToModule($this->name)) {
            $this->dependencies[] = $dependency;
        }
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

    public function describeDependenciesTo(string $module): string
    {
        $description = "";

        foreach ($this->dependencies as $dependency) {
            if ($dependency->belongsToModule($module)) {
                $description .= (string)$dependency . PHP_EOL;
            }
        }

        return $description;
    }

    public function belongsToModule(string $module) : bool
    {
        return strpos($this->name, $module) === 0;
    }

    public function getDependencyCount() : int
    {
        return count($this->dependencies);
    }
}
