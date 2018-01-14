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
    private $packageRegistry;

    public function __construct(string $path)
    {
        $this->path = $path;
        $this->packageRegistry = new ModuleRegistry();

        $traverser = new DirectoryTraverser($path, $this->packageRegistry);
        $traverser->traverse();
    }
}
