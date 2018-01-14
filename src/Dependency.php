<?php
declare(strict_types=1);
namespace KajStrom\DependencyConstraints;

class Dependency
{
    /**
     * @var string
     */
    private $fqn;

    public function __construct(string $fqn)
    {
        $this->fqn = $fqn;
    }

    public function belongsToModule(string $module) : bool
    {
        return strpos($this->fqn, $module) === 0;
    }
}
