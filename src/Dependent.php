<?php

namespace KajStrom\DependencyConstraints;


interface Dependent
{
    public function addDependency(Dependency $dependency) : void;

    public function belongsToModule(string $module) : bool;

    public function getDependencyCount() : int;
}