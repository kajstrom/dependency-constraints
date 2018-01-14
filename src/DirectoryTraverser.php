<?php
declare(strict_types=1);
namespace KajStrom\DependencyConstraints;

use RecursiveDirectoryIterator;
use RecursiveIteratorIterator;
use RegexIterator;

class DirectoryTraverser
{
    /**
     * @var ModuleRegistry
     */
    private $registry;
    /**
     * @var string
     */
    private $path;

    public function __construct(string $path, ModuleRegistry $registry)
    {
        $this->path = realpath($path);
        $this->registry = $registry;
    }

    public function traverse()
    {
        $allFiles = new RecursiveIteratorIterator(new RecursiveDirectoryIterator($this->path));
        $phpFiles = new RegexIterator($allFiles, '/\.php$/');

        foreach ($phpFiles as $phpFile) {
            $analyzer = new FileAnalyzer($phpFile->getRealPath(), $this->registry);
            $analyzer->analyze();
        }
    }
}
