<?php
namespace KajStrom\DependencyConstraints\Analyzer;

use KajStrom\DependencyConstraints\Dependency;
use KajStrom\DependencyConstraints\SubModule;

class UseClassAnalyzer
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
        $tokens = $this->tokens;

        //fwrite(STDERR, print_r($tokens, true));

        $fqn = "";
        for ($index = 0; $index < count($tokens); $index++) {
            if ($this->isOpeningCurlyBrace($tokens[$index])) {
                while (!$this->isClosingCurlyBrace($tokens[$index])) {

                    if ($this->notCommaOrWhitespace($tokens[$index]) && $this->notAs($tokens[$index])) {
                        $this->subModule->addDependency(new Dependency($fqn . $tokens[$index][1]));

                        $index++;
                    } else if ($this->isAs($tokens[$index])) {
                        $index += 3;
                    } else {
                        $index++;
                    }
                }

                $fqn = "";
            } else if ($this->isComma($tokens[$index])) {
                $this->subModule->addDependency(new Dependency($fqn));
                $fqn = "";
            } else if ($this->notWhiteSpace($tokens[$index])) {
                if ($this->isAs($tokens[$index])) {
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

    private function notWhiteSpace($token) : bool
    {
        if (is_array($token)) {
            return T_WHITESPACE !== $token[0];
        }

        return true;
    }

    private function notCommaOrWhitespace($token) : bool
    {
        if ($this->isComma($token)) {
            return false;
        }

        return $this->notWhiteSpace($token);
    }

    private function notAs($token) : bool
    {
        if (!is_array($token)) {
            return false;
        }

        return T_AS !== $token[0];
    }

    private function isAs($token) : bool
    {
        if (!is_array($token)) {
            return false;
        }

        return T_AS === $token[0];
    }

    private function isComma($token) : bool
    {
        return $token === ",";
    }

    private function isOpeningCurlyBrace($token) : bool
    {
        return $token === "{";
    }

    private function isClosingCurlyBrace($token) : bool
    {
        return $token === "}";
    }

}
