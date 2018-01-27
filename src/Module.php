<?php
declare(strict_types=1);

namespace KajStrom\DependencyConstraints;

interface Module
{
    /**
     * Returns the qualified name of this module.
     *
     * @return string
     */
    public function getName() : string;

    /**
     * Checks if this module depends on the specified module.
     *
     * @param string $module
     * @return bool
     */
    public function dependsOnModule(string $module) : bool;

    /**
     * Checks if this module depends on the specified class/function/constant.
     *
     * @param string $fqn
     * @return bool
     */
    public function hasDependencyOn(string $fqn) : bool;

    /**
     * Checks if this module is the given module.
     *
     * @param string $module
     * @return bool
     */
    public function is(string $module) : bool;

    /**
     * Provides a description of dependencies to the specified module.
     *
     * @param string $module
     * @return string
     */
    public function describeDependenciesTo(string $module) : string;
}
