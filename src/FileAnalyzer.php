<?php
declare(strict_types=1);
namespace KajStrom\DependencyConstraints;

class FileAnalyzer
{
    /**
     * @var string
     */
    private $path;

    /** @var  SubModule|null */
    private $module;
    /**
     * @var ModuleRegistry
     */
    private $registry;

    public function __construct(string $path, ModuleRegistry $registry)
    {
        if (!is_file($path)) {
            throw new \InvalidArgumentException("$path is not a file");
        }

        $this->path = $path;
        $this->registry = $registry;
    }

    public function analyze()
    {
        $contents = file_get_contents($this->path);
        $tokens = token_get_all($contents);

        //fwrite(STDERR, print_r(array_slice($tokens, 9, 5), true));
        //fwrite(STDERR, print_r(token_name(390), true));

        $tokenCount = count($tokens);
        $moduleName = null;
        for ($index = 0; $index < $tokenCount; $index++) {
            if (!is_array($tokens[$index])) {
                continue;
            }

            if (T_NAMESPACE === $tokens[$index][0]) {
                $index += 2;

                $moduleName = "";
                while (";" !== $tokens[$index]) {
                    $moduleName .= $tokens[$index][1];

                    $index++;
                }

                if ($this->registry->has($moduleName)) {
                    $this->module = $this->registry->get($moduleName);
                } else {
                    $this->module = new SubModule($moduleName);
                    $this->registry->add($this->module);
                }
            }

            if (T_USE === $tokens[$index][0]) {
                $index += 2;

                $fqn = "";
                while ($this->notSemicolonOrWhitespace($tokens[$index])) {
                    $fqn .= $tokens[$index][1];

                    $index++;
                }

                $this->module->addDependency(new Dependency($fqn));
            }
        }
    }

    public function getModule() : ?SubModule
    {
        return $this->module;
    }

    private function notSemicolonOrWhitespace($token) : bool
    {
        if ($token === ";") {
            return false;
        }

        return T_WHITESPACE !== $token[0];
    }
}
