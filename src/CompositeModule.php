<?php
declare(strict_types=1);

namespace KajStrom\DependencyConstraints;


class CompositeModule implements Module
{
    /**
     * @var string
     */
    private $name;
    /**
     * @var SubModule[]
     */
    private $subModules = [];

    public function __construct(string $name)
    {
        $this->name = $name;
    }

    public function add(SubModule $module)
    {
        $this->subModules[] = $module;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function moduleCount() : int
    {
        return count($this->subModules);
    }

    public function dependsOnModule(string $module): bool
    {
        foreach ($this->subModules as $sub) {
            if ($sub->dependsOnModule($module)) {
                return true;
            }
        }

        return false;
    }

    public function hasDependencyOn(string $fqn): bool
    {
        // TODO: Implement hasDependencyOn() method.
    }

    public function is(string $module): bool
    {
        // TODO: Implement is() method.
    }
}