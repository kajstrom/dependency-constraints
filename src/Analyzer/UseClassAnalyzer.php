<?php
namespace KajStrom\DependencyConstraints\Analyzer;

use KajStrom\DependencyConstraints\Dependency;
use KajStrom\DependencyConstraints\SubModule;
use KajStrom\DependencyConstraints\Token\Helpers as TH;

class UseClassAnalyzer implements Analyzer
{
    /**
     * @var array
     */
    private $tokens;
    /**
     * @var SubModule
     */
    private $subModule;

    public function __construct(array $tokens, SubModule $subModule)
    {
        $this->tokens = $tokens;
        $this->subModule = $subModule;
    }

    public function analyze() : void
    {
        if ($this->isGlobalClass()) {
            return;
        }

        $tokens = $this->tokens;

        //fwrite(STDERR, print_r($tokens, true));

        $fqn = "";
        for ($index = 0; $index < count($tokens); $index++) {
            if (TH::isOpeningCurlyBrace($tokens[$index])) {
                while (!TH::isClosingCurlyBrace($tokens[$index])) {

                    if (TH::notCommaOrWhitespace($tokens[$index]) && TH::notAs($tokens[$index])) {
                        $this->subModule->addDependency(new Dependency($fqn . $tokens[$index][1]));

                        $index++;
                    } else if (TH::isAs($tokens[$index])) {
                        $index += 3;
                    } else {
                        $index++;
                    }
                }

                $fqn = "";
            } else if (TH::isComma($tokens[$index])) {
                $this->subModule->addDependency(new Dependency($fqn));
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
            $this->subModule->addDependency(new Dependency($fqn));
        }
    }

    private function isGlobalClass() : bool
    {
        return count($this->tokens) === 1;
    }
}
