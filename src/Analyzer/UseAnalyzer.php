<?php
namespace KajStrom\DependencyConstraints\Analyzer;

use KajStrom\DependencyConstraints\Dependency;
use KajStrom\DependencyConstraints\SubModule;
use KajStrom\DependencyConstraints\Token\Helpers as TH;

class UseAnalyzer implements Analyzer
{
    /**
     * @var array
     */
    private $tokens;
    /**
     * @var SubModule
     */
    private $subModule;
    /**
     * @var string
     */
    private $file;

    public function __construct(array $tokens, string $file, SubModule $subModule)
    {
        $this->tokens = $tokens;
        $this->subModule = $subModule;
        $this->file = $file;
    }

    public function analyze() : void
    {
        if ($this->isGlobalClass()) {
            return;
        }

        $tokens = $this->tokens;

        if (TH::isNamespaceSeparator($tokens[0])) {
            $tokens = array_slice($tokens, 1);
        }

        $fqn = "";
        $lineNumber = 0;
        for ($index = 0; $index < count($tokens); $index++) {
            if (is_array($tokens[$index])) {
                $lineNumber = $tokens[$index][2];
            }

            if (TH::isOpeningCurlyBrace($tokens[$index])) {
                while (!TH::isClosingCurlyBrace($tokens[$index])) {

                    if (TH::notCommaOrWhitespace($tokens[$index]) && TH::notAs($tokens[$index])) {
                        $this->subModule->addDependency(new Dependency(
                            $fqn . $tokens[$index][1],
                            $this->file,
                            $lineNumber
                        ));

                        $index++;
                    } else if (TH::isAs($tokens[$index])) {
                        $index += 3;
                    } else {
                        $index++;
                    }
                }

                $fqn = "";
            } else if (TH::isComma($tokens[$index])) {
                $this->subModule->addDependency(new Dependency(
                    $fqn,
                    $this->file,
                    $lineNumber
                ));
                $fqn = "";
            } else if (TH::notWhiteSpace($tokens[$index])) {
                if (TH::isAs($tokens[$index])) {
                    //Skip whitespace and alias.
                    $index += 2;
                } else {
                    $fqn .= $tokens[$index][1];
                }
            }
        }

        if (!empty($fqn)) {
            $this->subModule->addDependency(new Dependency($fqn, $this->file, $lineNumber));
        }
    }

    private function isGlobalClass() : bool
    {
        return count($this->tokens) === 1;
    }
}
