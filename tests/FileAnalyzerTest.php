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
        $this->assertTrue($module->hasDependencyOn("KajStrom\\DependencyConstraints\\DependencyConstraints"));
    }

    public function testAnalyzeFindsDependencyFromGroupedClassUseKeyword()
    {
        $analyzer = $this->makeAnalyzer(__DIR__ . "/files/FileWithClassGroupedClassUseClause.php");
        $analyzer->analyze();

        $module = $analyzer->getModule();

        $this->assertTrue($module->dependsOnModule("KajStrom\\DependencyConstraints"));
        $this->assertTrue($module->hasDependencyOn("KajStrom\\DependencyConstraints\\DependencyConstraints"));
        $this->assertTrue($module->hasDependencyOn("KajStrom\\DependencyConstraints\\FileAnalyzer"));
        $this->assertTrue($module->hasDependencyOn("KajStrom\\DependencyConstraints\\SubModule"));
        $this->assertSame(3, $module->getDependencyCount());
    }

    public function testAnalyzeFindsDependencyFromCommaSeparatedUseKeyword()
    {
        $analyzer = $this->makeAnalyzer(__DIR__ . "/files/FileWithClassUseClauseCommaSeparated.php");
        $analyzer->analyze();

        $module = $analyzer->getModule();

        $this->assertTrue($module->dependsOnModule("KajStrom\\DependencyConstraints"));
        $this->assertTrue($module->hasDependencyOn("KajStrom\\DependencyConstraints\\CompositeModule"));
        $this->assertTrue($module->hasDependencyOn("KajStrom\\DependencyConstraints\\DependencyConstraints"));
    }

    public function testAnalyzeFindsDependencyFromFQNUsages()
    {
        $analyzer = $this->makeAnalyzer(__DIR__ . "/files/FileWithFQNUsages.php");
        $analyzer->analyze();

        $module = $analyzer->getModule();

        $this->assertTrue($module->dependsOnModule("KajStrom\\DependencyConstraints"));
        $this->assertTrue($module->hasDependencyOn("KajStrom\\DependencyConstraints\\Dependency"));
        $this->assertTrue($module->hasDependencyOn("KajStrom\\DependencyConstraints\\ModuleRegistry"));
        $this->assertSame(2, $module->getDependencyCount());
    }

    public function testAnalyzeDoesNotConsiderSubModuleAsADependency()
    {
        $analyzer = $this->makeAnalyzer(__DIR__ . "/files/FileWithClassUseClauseSubModule.php");
        $analyzer->analyze();

        $module = $analyzer->getModule();

        $this->assertFalse($module->dependsOnModule("Test\\Package\\SubModule"));
        $this->assertFalse($module->hasDependencyOn("Test\\Package\\SubModule\\SubModule"));;
    }

    public function testAnalyzeFindsFunctionDependency()
    {
        $analyzer = $this->makeAnalyzer(__DIR__ . "/files/FileWithUseFunctionClause.php");
        $analyzer->analyze();

        $module = $analyzer->getModule();

        $this->assertTrue($module->dependsOnModule("Test\\FunctionPackage"));
        $this->assertTrue($module->hasDependencyOn("Test\\FunctionPackage\\some_function"));
    }

    private function makeAnalyzer(string $path) : FileAnalyzer
    {
        return new FileAnalyzer($path, new ModuleRegistry());
    }
}
