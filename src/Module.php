<?php
declare(strict_types=1);

namespace KajStrom\DependencyConstraints;

interface Module
{
    public function getName() : string;

    public function dependsOnModule(string $module) : bool;

    public function hasDependencyOn(string $fqn) : bool;

    public function is(string $module) : bool;

    /**
     * Provides a description of dependencies to the specified module.
     *
     * @param string $module
     * @return string
     */
    public function describeDependenciesTo(string $module) : string;
}
