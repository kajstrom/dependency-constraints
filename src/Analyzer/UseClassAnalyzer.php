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
     * @var Dependency[]
     */
    private $dependencies = [];
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
            if ($tokens[$index] === "{") {
                while ("}" !== $tokens[$index]) {
                    if ($this->notCommaOrWhitespace($tokens[$index]) && $this->notAs($tokens[$index])) {
                        //fwrite(STDERR, $fqn . $tokens[$index][1] . PHP_EOL);
                        $this->subModule->addDependency(new Dependency($fqn . $tokens[$index][1]));

                        $index++;
                    } else if ($this->isAs($tokens[$index])) {
                        //Skip whitespace and alias.
                        $index += 3;
                    } else {
                        $index++;
                    }
                }

                $fqn = "";
            } else if ($this->isComma($tokens[$index])) {
                //fwrite(STDERR, $fqn);
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

        //fwrite(STDERR, $fqn . PHP_EOL);
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

}
