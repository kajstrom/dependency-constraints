<?php
declare(strict_types=1);
namespace KajStrom\DependencyConstraints;

class ModuleRegistry
{
    /** @var SubModule[] */
    private $modules = [];

    public function add(SubModule $module) : void
    {
        if (!$this->has($module->getName())) {
            $this->modules[] = $module;
        }
    }

    public function has(string $name) : bool
    {
        foreach ($this->modules as $module) {
            if ($module->is($name)) {
                return true;
            }
        }

        return false;
    }

    public function get(string $name) : ?SubModule
    {
        foreach ($this->modules as $module) {
            if ($module->is($name)) {
                return $module;
            }
        }

        return null;
    }

    public function size() : int
    {
        return count($this->modules);
    }
}
