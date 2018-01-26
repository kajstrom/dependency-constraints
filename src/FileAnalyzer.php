<?php
declare(strict_types=1);
namespace KajStrom\DependencyConstraints;

use KajStrom\DependencyConstraints\Analyzer\FQNAnalyzer;
use KajStrom\DependencyConstraints\Analyzer\UseAnalyzer;
use KajStrom\DependencyConstraints\Token\Helpers as TH;

class FileAnalyzer
{
    /**
     * @var string
     */
    private $path;

    /** @var  SubModule|null */
    private $module;
    /**
     * @var ModuleRegistry
     */
    private $registry;

    public function __construct(string $path, ModuleRegistry $registry)
    {
        if (!is_file($path)) {
            throw new \InvalidArgumentException("$path is not a file");
        }

        $this->path = $path;
        $this->registry = $registry;
    }

    public function analyze()
    {
        $contents = file_get_contents($this->path);
        $tokens = token_get_all($contents);

        $tokenCount = count($tokens);
        $moduleName = null;
        for ($index = 0; $index < $tokenCount; $index++) {
            if (!is_array($tokens[$index])) {
                continue;
            }

            if (TH::isNamespace($tokens[$index])) {
                if (TH::isNamespaceSeparator($tokens[$index + 1])) {
                    $index += 2;
                    while(TH::partOfQualifiedName($tokens[$index])) {
                        $index++;
                    }
                    continue;
                }

                $index += 2;

                $moduleName = "";
                while (TH::notSemicolon($tokens[$index])) {
                    $moduleName .= $tokens[$index][1];

                    $index++;
                }

                if ($this->registry->has($moduleName)) {
                    $this->module = $this->registry->get($moduleName);
                } else {
                    $this->module = new SubModule($moduleName);
                    $this->registry->add($this->module);
                }
            }

            if (T_USE === $tokens[$index][0]) {
                if (TH::isOpeningParenthesis($tokens[$index + 1])) {
                    continue;
                }

                if (TH::isOpeningParenthesis($tokens[$index + 2])) {
                    continue;
                }

                $index += 2;

                if (T_FUNCTION === $tokens[$index][0]) {
                    $index += 2;
                }

                if (T_CONST === $tokens[$index][0]) {
                    $index += 2;
                }

                $analyzeTokens = [];

                while (TH::notSemicolon($tokens[$index])) {
                    $analyzeTokens[] = $tokens[$index];
                    $index++;
                }

                $useClassAnalyzer = new UseAnalyzer($analyzeTokens, $this->path, $this->module);
                $useClassAnalyzer->analyze();
            }

            if (TH::isNamespaceSeparator($tokens[$index])) {
                $analyzeTokens = [];
                while (TH::partOfQualifiedName($tokens[$index])) {
                    $analyzeTokens[] = $tokens[$index];
                    $index++;
                }

                $fqnAnalyzer = new FQNAnalyzer($analyzeTokens, $this->path, $this->module);
                $fqnAnalyzer->analyze();
            }
        }
    }

    public function getModule() : ?SubModule
    {
        return $this->module;
    }
}
