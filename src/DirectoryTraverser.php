<?php

namespace KajStrom\DependencyConstraints;


use RecursiveDirectoryIterator;
use RecursiveIteratorIterator;
use RegexIterator;

class DirectoryTraverser
{
    /**
     * @var PackageRegistry
     */
    private $packageRegistry;
    /**
     * @var string
     */
    private $path;

    public function __construct(string $path, PackageRegistry $packageRegistry)
    {
        $this->path = realpath($path);
        echo $this->path;
        $this->packageRegistry = $packageRegistry;
    }

    public function traverse()
    {
        $allFiles = new RecursiveIteratorIterator(new RecursiveDirectoryIterator($this->path));
        $phpFiles = new RegexIterator($allFiles, '/\.php$/');

        foreach ($phpFiles as $phpFile) {
            new FileAnalyzer($phpFile->getRealPath());
        }
    }
}