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

    public function getModule(string $name) : Module
    {
        return $this->registry->getSubModulesOf($name);
    }
}
