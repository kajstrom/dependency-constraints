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
        foreach ($this->subModules as $sub) {
            if ($sub->hasDependencyOn($fqn)) {
                return true;
            }
        }

        return false;
    }

    public function is(string $module): bool
    {
        return $this->name === $module;
    }

    public function describeDependenciesTo(string $module): string
    {
        $description = "";
        foreach ($this->subModules as $sub) {
            $description .= $sub->describeDependenciesTo($module);
        }

        return $description;
    }


}
