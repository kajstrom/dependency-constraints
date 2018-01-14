<?php
declare(strict_types=1);
namespace KajStrom\DependencyConstraints;

class DependencyConstraints
{
    /**
     * @var string
     */
    private $path;
    /** @var  ModuleRegistry */
    private $registry;

    public function __construct(string $path)
    {
        $this->path = $path;
        $this->registry = new ModuleRegistry();

        $traverser = new DirectoryTraverser($path, $this->registry);
        $traverser->traverse();
    }

    public function getModule(string $name) : ?Module
    {
        $compositeModule = $this->registry->getSubModulesOf($name);

        if ($compositeModule->moduleCount() !== 0) {
            return $compositeModule;
        }

        $singleName = substr($name, 0, strlen($name) - 1);
        if ($this->registry->has($singleName)) {
            return $this->registry->get($singleName);
        }

        return null;
    }
}
