<?php
declare(strict_types=1);
namespace KajStrom\DependencyConstraints;

class Dependency
{
    /**
     * @var string
     */
    private $fqn;
    /**
     * @var string
     */
    private $file;
    /**
     * @var int
     */
    private $row;

    public function __construct(string $fqn, string $file, int $row)
    {
        $this->fqn = $fqn;
        $this->file = $file;
        $this->row = $row;
    }

    public function belongsToModule(string $module) : bool
    {
        return strpos($this->fqn, $module) === 0;
    }

    public function is(string $fqn) : bool
    {
        return $this->fqn === $fqn;
    }

    public function __toString()
    {
        return sprintf(
            "%s in %s:%s",
            $this->fqn,
            $this->file,
            $this->row
        );
    }
}
