<?php

use KajStrom\DependencyConstraints\FileAnalyzer;
use KajStrom\DependencyConstraints\ModuleRegistry;
use PHPUnit\Framework\TestCase;

class FileAnalyzerTest extends TestCase
{
    public function testAnalyzeFindsPackageNameFromNamespace()
    {
        $analyzer = $this->makeAnalyzer(dirname(__DIR__) . "/src/FileAnalyzer.php");
        $analyzer->analyze();

        $module = $analyzer->getModule();

        $this->assertEquals("KajStrom\\DependencyConstraints", $module->getName());
    }

    public function testAnalyzeFindsDependencyFromClassUseKeyword()
    {
        $analyzer = $this->makeAnalyzer(__DIR__ . "/files/FileWithClassUseClause.php");
        $analyzer->analyze();

        $module = $analyzer->getModule();

        $this->assertTrue($module->dependsOnModule("KajStrom\\DependencyConstraints"));
    }

    public function testAnalyzeFindsDependencyFromClassUseKeywordWithAlias()
    {
        $analyzer = $this->makeAnalyzer(__DIR__ . "/files/FileWithClassUseClauseAlias.php");
        $analyzer->analyze();

        $module = $analyzer->getModule();

        $this->assertTrue($module->dependsOnModule("KajStrom\\DependencyConstraints"));
    }

    private function makeAnalyzer(string $path) : FileAnalyzer
    {
        return new FileAnalyzer($path, new ModuleRegistry());
    }
}
