<?php

namespace KajStrom\DependencyConstraints;


use RecursiveDirectoryIterator;
use RecursiveIteratorIterator;
use RegexIterator;

class DirectoryTraverser
{
    /**
     * @var ModuleRegistry
     */
    private $moduleRegistry;
    /**
     * @var string
     */
    private $path;

    public function __construct(string $path, ModuleRegistry $moduleRegistry)
    {
        $this->path = realpath($path);
        echo $this->path;
        $this->moduleRegistry = $moduleRegistry;
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