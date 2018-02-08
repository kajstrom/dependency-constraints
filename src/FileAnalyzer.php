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
    /** @var  Dependent|null */
    private $currentModule;
    /** @var array  */
    private $allModules = [];
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
        $this->currentModule = new GlobalModule();
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
                while (TH::partOfQualifiedName($tokens[$index])) {
                    $moduleName .= $tokens[$index][1];

                    $index++;
                }

                $this->addModule($moduleName);
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


                    if (TH::isOpeningCurlyBrace($tokens[$index])) {
                        while (!TH::isClosingCurlyBrace($tokens[$index])) {
                            $analyzeTokens[] = $tokens[$index];
                            $index++;
                        }

                        $analyzeTokens[] = $tokens[$index];
                        break;
                    }
                }

                $useClassAnalyzer = new UseAnalyzer($analyzeTokens, $this->path, $this->currentModule);
                $useClassAnalyzer->analyze();
            }

            if (TH::isNamespaceSeparator($tokens[$index])) {
                $analyzeTokens = [];
                while (TH::partOfQualifiedName($tokens[$index])) {
                    $analyzeTokens[] = $tokens[$index];
                    $index++;
                }

                $fqnAnalyzer = new FQNAnalyzer($analyzeTokens, $this->path, $this->currentModule);
                $fqnAnalyzer->analyze();
            }
        }
    }

    /**
     * @return SubModule[]
     */
    public function getModules() : array
    {
        return $this->allModules;
    }

    private function addModule(string $moduleName) : void
    {
        if (empty($moduleName)) {
            $this->currentModule = new GlobalModule();
            return;
        }

        if ($this->registry->has($moduleName)) {
            $this->currentModule = $this->registry->get($moduleName);
        } else {
            $this->currentModule = new SubModule($moduleName);
            $this->registry->add($this->currentModule);
        }

        $this->allModules[] = $this->currentModule;
    }
}
