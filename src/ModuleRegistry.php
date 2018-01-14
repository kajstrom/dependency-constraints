<?php
declare(strict_types=1);
namespace KajStrom\DependencyConstraints;

class ModuleRegistry
{
    /** @var Module[] */
    private $modules = [];

    public function add(Module $module) : void
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

    public function get(string $name) : ?Module
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
