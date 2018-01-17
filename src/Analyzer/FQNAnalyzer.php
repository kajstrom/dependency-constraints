<?php
/**
 * Created by PhpStorm.
 * User: Kaitsu
 * Date: 17.1.2018
 * Time: 19:11
 */

namespace KajStrom\DependencyConstraints\Analyzer;


use KajStrom\DependencyConstraints\Dependency;
use KajStrom\DependencyConstraints\SubModule;

class FQNAnalyzer implements Analyzer
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

    public function analyze(): void
    {
        $fqn = array_map(function($token) {
            return $token[1];
        }, $this->tokens);

        $fqn = implode("", $fqn);
        $fqn = substr($fqn, 1);

        $this->subModule->addDependency(new Dependency($fqn));
    }


}