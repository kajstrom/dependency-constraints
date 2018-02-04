<?php

namespace KajStrom\DependencyConstraints;


class GlobalModule implements Module, Dependent
{
    public function getName(): string
    {
        return "";
    }

    public function dependsOnModule(string $module): bool
    {
        return false;
    }

    public function hasDependencyOn(string $fqn): bool
    {
        return false;
    }

    public function is(string $module): bool
    {
        return false;
    }

    public function describeDependenciesTo(string $module): string
    {
        return "";
    }

    public function addDependency(Dependency $dependency): void
    {

    }

    public function belongsToModule(string $module): bool
    {
        return false;
    }

    public function getDependencyCount(): int
    {
        return 0;
    }
}