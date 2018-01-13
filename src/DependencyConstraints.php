<?php
declare(strict_types=1);

namespace KajStrom\DependencyConstraints;


class DependencyConstraints
{
    /**
     * @var string
     */
    private $path;
    /** @var  PackageRegistry */
    private $packageRegistry;

    public function __construct(string $path)
    {
        $this->path = $path;
        $this->packageRegistry = new PackageRegistry();

        $traverser = new DirectoryTraverser($path, $this->packageRegistry);
        $traverser->traverse();
    }
}