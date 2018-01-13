<?php
/**
 * Created by PhpStorm.
 * User: Kaitsu
 * Date: 13.1.2018
 * Time: 20:25
 */

use KajStrom\DependencyConstraints\FileAnalyzer;
use PHPUnit\Framework\TestCase;

class FileAnalyzerTest extends TestCase
{
    public function testAnalyzeFindsCorrectPackageName()
    {
        $analyzer = new FileAnalyzer(dirname(__DIR__) . "/src/FileAnalyzer.php");
        $analyzer->analyze();

        $package = $analyzer->getPackage();

        $this->assertEquals("KajStrom\\DependencyConstraints", $package->getName());
    }
}
