<?php
declare(strict_types=1);

namespace KajStrom\DependencyConstraints\Analyzer;


use KajStrom\DependencyConstraints\Dependency;
use KajStrom\DependencyConstraints\Dependent;

class FQNAnalyzer implements Analyzer
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

    public function analyze(): void
    {
        if ($this->isGlobalFunctionOrClass()) {
            return;
        }

        $fqn = array_map(function($token) {
            return $token[1];
        }, $this->tokens);

        $fqn = implode("", $fqn);
        $fqn = substr($fqn, 1);

        $this->module->addDependency(new Dependency($fqn, $this->file, $this->tokens[0][2]));
    }

    /**
     * Checks that the FQN is not for a global function.
     *
     * FQNs with only two tokens can only be
     *
     * E.g. \is_array($myArray) will be ignored as a dependency.
     * Also global class usages will be ignored e.g. new \Datetime();
     *
     * @return bool
     */
    public function isGlobalFunctionOrClass() : bool
    {
        return count($this->tokens) === 2;
    }
}