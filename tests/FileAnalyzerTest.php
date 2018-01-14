<?php

use KajStrom\DependencyConstraints\FileAnalyzer;
use PHPUnit\Framework\TestCase;

class FileAnalyzerTest extends TestCase
{
    public function testAnalyzeFindsPackageNameFromNamespace()
    {
        $analyzer = new FileAnalyzer(dirname(__DIR__) . "/src/FileAnalyzer.php");
        $analyzer->analyze();

        $module = $analyzer->getModule();

        $this->assertEquals("KajStrom\\DependencyConstraints", $module->getName());
    }

    public function testAnalyzeFindsDependencyFromUseKeyword()
    {
        $analyzer = new FileAnalyzer(__DIR__ . "/files/FileWithUseClause.php");
        $analyzer->analyze();

        $module = $analyzer->getModule();

        $this->assertTrue($module->dependsOnModule("KajStrom\\DependencyConstraints"));
    }
}
