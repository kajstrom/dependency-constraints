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

    public function testAnalyzeFindsDependencyFromUseKeyword()
    {
        $analyzer = $this->makeAnalyzer(__DIR__ . "/files/FileWithUseClause.php");
        $analyzer->analyze();

        $module = $analyzer->getModule();

        $this->assertTrue($module->dependsOnModule("KajStrom\\DependencyConstraints"));
    }

    public function testAnalyzeFindsDependencyFromUseKeywordWithAlias()
    {
        $analyzer = $this->makeAnalyzer(__DIR__ . "/files/FileWithUseClauseAlias.php");
        $analyzer->analyze();

        $module = $analyzer->getModule();

        $this->assertTrue($module->dependsOnModule("KajStrom\\DependencyConstraints"));
    }

    private function makeAnalyzer(string $path) : FileAnalyzer
    {
        return new FileAnalyzer($path, new ModuleRegistry());
    }
}
