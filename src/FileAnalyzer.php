<?php
declare(strict_types=1);
namespace KajStrom\DependencyConstraints;

use KajStrom\DependencyConstraints\Analyzer\FQNAnalyzer;
use KajStrom\DependencyConstraints\Analyzer\UseClassAnalyzer;

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

        //fwrite(STDERR, print_r(array_slice($tokens, 9, 10), true));
        //fwrite(STDERR, print_r(token_name(390), true));

        $tokenCount = count($tokens);
        $moduleName = null;
        for ($index = 0; $index < $tokenCount; $index++) {
            if (!is_array($tokens[$index])) {
                continue;
            }

            if (T_NAMESPACE === $tokens[$index][0]) {
                $index += 2;

                $moduleName = "";
                while (";" !== $tokens[$index]) {
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
                $index += 2;

                $analyzeTokens = [];

                while ($this->notSemicolon($tokens[$index])) {
                    $analyzeTokens[] = $tokens[$index];
                    $index++;
                }

                $useClassAnalyzer = new UseClassAnalyzer($analyzeTokens, $this->module);
                $useClassAnalyzer->analyze();
            }

            if (T_NS_SEPARATOR === $tokens[$index][0]) {
                $analyzeTokens = [];
                while ($this->partOfQuafiedName($tokens[$index])) {
                    $analyzeTokens[] = $tokens[$index];
                    $index++;
                }

                $fqnAnalyzer = new FQNAnalyzer($analyzeTokens, $this->module);
                $fqnAnalyzer->analyze();
            }
        }
    }

    public function getModule() : ?SubModule
    {
        return $this->module;
    }

    private function notSemicolon($token) : bool
    {
        return $token !== ";";
    }

    private function partOfQuafiedName($token) : bool
    {
        if (T_NS_SEPARATOR === $token[0]) {
            return true;
        }

        if (T_STRING === $token[0]) {
            return true;
        }

        return false;
    }
}
