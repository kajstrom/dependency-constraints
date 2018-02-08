<?php
namespace KajStrom\DependencyConstraints\Analyzer;

use KajStrom\DependencyConstraints\Dependency;
use KajStrom\DependencyConstraints\Dependent;
use KajStrom\DependencyConstraints\Token\Helpers as TH;

class UseAnalyzer implements Analyzer
{
    /**
     * @var array
     */
    private $tokens;
    /**
     * @var Dependent
     */
    private $module;
    /**
     * @var string
     */
    private $file;

    public function __construct(array $tokens, string $file, Dependent $module)
    {
        $this->tokens = $tokens;
        $this->module = $module;
        $this->file = $file;
    }

    public function analyze() : void
    {
        if ($this->isGlobalClass()) {
            return;
        }

        if ($this->isTraitVisibilityOverride()) {
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
                        $this->module->addDependency(new Dependency(
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
                $this->module->addDependency(new Dependency(
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
            $this->module->addDependency(new Dependency($fqn, $this->file, $lineNumber));
        }
    }

    private function isTraitVisibilityOverride() {
        foreach ($this->tokens as $token) {
            if (TH::isSemicolon($token)) {
                return true;
            }
        }

        return false;
    }

    private function isGlobalClass() : bool
    {
        return count($this->tokens) === 1;
    }
}
