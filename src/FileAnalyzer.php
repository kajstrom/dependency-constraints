<?php
declare(strict_types=1);

namespace KajStrom\DependencyConstraints;


class FileAnalyzer
{
    /**
     * @var string
     */
    private $path;

    /** @var  Module|null */
    private $module;

    public function __construct(string $path)
    {
        if (!is_file($path)) {
            throw new \InvalidArgumentException("$path is not a file");
        }

        $this->path = $path;
    }

    public function analyze()
    {
        $contents = file_get_contents($this->path);
        $tokens = token_get_all($contents);

        fwrite(STDERR, print_r(array_slice($tokens, 9, 5), true));
        fwrite(STDERR, print_r(token_name(390), true));

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

                $this->module = new Module($moduleName);
            }

            if (T_USE === $tokens[$index][0]) {
                $index += 2;

                $fqn = "";
                while (";" !== $tokens[$index]) {
                    $fqn .= $tokens[$index][1];

                    $index++;
                }

                $this->module->addDependency(new Dependency($fqn));
            }
        }
    }

    public function getModule() : ?Module
    {
        return $this->module;
    }
}