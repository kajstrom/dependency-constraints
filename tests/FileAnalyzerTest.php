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

        $module = $analyzer->getModules()[0];

        $this->assertEquals("KajStrom\\DependencyConstraints", $module->getName());
    }

    public function testAnalyzeFindsDependencyFromClassUseKeyword()
    {
        $analyzer = $this->makeAnalyzer(__DIR__ . "/files/FileWithClassUseClause.php");
        $analyzer->analyze();

        $module = $analyzer->getModules()[0];

        $this->assertTrue($module->dependsOnModule("KajStrom\\DependencyConstraints"));
    }

    public function testAnalyzeFindsDependencyFromClassUseKeywordWithAlias()
    {
        $analyzer = $this->makeAnalyzer(__DIR__ . "/files/FileWithClassUseClauseAlias.php");
        $analyzer->analyze();

        $module = $analyzer->getModules()[0];

        $this->assertTrue($module->dependsOnModule("KajStrom\\DependencyConstraints"));
        $this->assertTrue($module->hasDependencyOn("KajStrom\\DependencyConstraints\\DependencyConstraints"));
    }

    public function testAnalyzeFindsDependencyFromGroupedClassUseKeyword()
    {
        $analyzer = $this->makeAnalyzer(__DIR__ . "/files/FileWithClassGroupedClassUseClause.php");
        $analyzer->analyze();

        $module = $analyzer->getModules()[0];

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

        $module = $analyzer->getModules()[0];

        $this->assertTrue($module->dependsOnModule("KajStrom\\DependencyConstraints"));
        $this->assertTrue($module->hasDependencyOn("KajStrom\\DependencyConstraints\\CompositeModule"));
        $this->assertTrue($module->hasDependencyOn("KajStrom\\DependencyConstraints\\DependencyConstraints"));
    }

    public function testAnalyzeFindsDependencyFromFQNUsages()
    {
        $analyzer = $this->makeAnalyzer(__DIR__ . "/files/FileWithFQNUsages.php");
        $analyzer->analyze();

        $module = $analyzer->getModules()[0];

        $this->assertTrue($module->dependsOnModule("KajStrom\\DependencyConstraints"));
        $this->assertTrue($module->hasDependencyOn("KajStrom\\DependencyConstraints\\Dependency"));
        $this->assertTrue($module->hasDependencyOn("KajStrom\\DependencyConstraints\\ModuleRegistry"));
        $this->assertSame(2, $module->getDependencyCount());
    }

    public function testAnalyzeDoesNotConsiderSubModuleAsADependency()
    {
        $analyzer = $this->makeAnalyzer(__DIR__ . "/files/FileWithClassUseClauseSubModule.php");
        $analyzer->analyze();

        $module = $analyzer->getModules()[0];

        $this->assertFalse($module->dependsOnModule("Test\\Package\\SubModule"));
        $this->assertFalse($module->hasDependencyOn("Test\\Package\\SubModule\\SubModule"));;
    }

    public function testAnalyzeFindsFunctionDependency()
    {
        $analyzer = $this->makeAnalyzer(__DIR__ . "/files/FileWithUseFunctionClause.php");
        $analyzer->analyze();

        $module = $analyzer->getModules()[0];

        $this->assertTrue($module->dependsOnModule("Test\\FunctionModule"));
        $this->assertTrue($module->hasDependencyOn("Test\\FunctionModule\\some_function"));
    }

    public function testAnalyzeFindsFunctionDependenciesFromUseMultiple()
    {
        $analyzer = $this->makeAnalyzer(__DIR__ . "/files/FileWithUseFunctionMultipleClause.php");
        $analyzer->analyze();

        $module = $analyzer->getModules()[0];

        $this->assertTrue($module->dependsOnModule("Test\\FunctionModule"));
        $this->assertTrue($module->hasDependencyOn("Test\\FunctionModule\\some_function"));
        $this->assertTrue($module->hasDependencyOn("Test\\FunctionModule\\another_function"));
    }

    public function testAnalyzeFindsFunctionDependenciesFromCommaSeparatedUse()
    {
        $analyzer = $this->makeAnalyzer(__DIR__ . "/files/FileWithUseFunctionCommaSeparated.php");
        $analyzer->analyze();

        $module = $analyzer->getModules()[0];

        $this->assertTrue($module->dependsOnModule("Test\\FunctionModule"));
        $this->assertTrue($module->hasDependencyOn("Test\\FunctionModule\\some_function"));
        $this->assertTrue($module->hasDependencyOn("Test\\FunctionModule\\another_function"));
    }

    public function testAnalyzeFunctionUseVariableDoesNotCauseError()
    {
        $analyzer = $this->makeAnalyzer(__DIR__ . "/files/FileWithFunctionVariableUseClause.php");
        $analyzer->analyze();

        $module = $analyzer->getModules()[0];

        $this->assertSame(0, $module->getDependencyCount());
    }

    public function testAnalyzeFindsConstantDependency()
    {
        $analyzer = $this->makeAnalyzer(__DIR__ . "/files/FileWithUseConstClause.php");
        $analyzer->analyze();

        $module = $analyzer->getModules()[0];

        $this->assertTrue($module->dependsOnModule("Test\\ConstantModule"));
        $this->assertTrue($module->hasDependencyOn("Test\\ConstantModule\\SOME_CONST"));
    }

    public function testAnalyzeFindsMultipleConstantDependencies()
    {
        $analyzer = $this->makeAnalyzer(__DIR__ . "/files/FileWithUseConstMultiple.php");
        $analyzer->analyze();

        $module = $analyzer->getModules()[0];

        $this->assertTrue($module->dependsOnModule("Test\\ConstantModule"));
        $this->assertTrue($module->hasDependencyOn("Test\\ConstantModule\\SOME_CONST"));
        $this->assertTrue($module->hasDependencyOn("Test\\ConstantModule\\SOME_OTHER_CONST"));
    }

    public function testAnalyzeFindsCommaSeparatedConstantDependencies()
    {
        $analyzer = $this->makeAnalyzer(__DIR__ . "/files/FileWithUseConstCommaSeparated.php");
        $analyzer->analyze();

        $module = $analyzer->getModules()[0];

        $this->assertTrue($module->dependsOnModule("Test\\ConstantModule"));
        $this->assertTrue($module->hasDependencyOn("Test\\ConstantModule\\SOME_CONST"));
        $this->assertTrue($module->hasDependencyOn("Test\\ConstantModule\\SOME_OTHER_CONST"));
    }

    public function testAnalyzeFindsDependencyFromFQNTraitUse()
    {
        $analyzer = $this->makeAnalyzer(__DIR__ . "/files/FileWithFQNTraitUse.php");
        $analyzer->analyze();

        $module = $analyzer->getModules()[0];

        $this->assertTrue($module->dependsOnModule("Test\\TraitModule"));
        $this->assertTrue($module->hasDependencyOn("Test\\TraitModule\\TraitFile"));
    }

    public function testAnalyzeFindsDependencyFromImportedTraitUse()
    {
        $analyzer = $this->makeAnalyzer(__DIR__ . "/files/FileWithImportedTraitUse.php");
        $analyzer->analyze();

        $module = $analyzer->getModules()[0];

        $this->assertTrue($module->dependsOnModule("Test\\TraitModule"));
        $this->assertTrue($module->hasDependencyOn("Test\\TraitModule\\TraitFile"));
    }

    public function testAnalyzeDoesNotInterpretNamespaceKeywordAsExternalDependency()
    {
        $analyzer = $this->makeAnalyzer(__DIR__ . "/files/FileWithNamespaceKeywordReferringToCurrentNamespace.php");
        $analyzer->analyze();

        $module = $analyzer->getModules()[0];

        $this->assertFalse($module->dependsOnModule("namespace"));
        $this->assertFalse($module->hasDependencyOn("namespace\\FileWithUseFunctionClause"));
        $this->assertSame(0, $module->getDependencyCount());
    }

    private function makeAnalyzer(string $path) : FileAnalyzer
    {
        return new FileAnalyzer($path, new ModuleRegistry());
    }
}
