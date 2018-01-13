<?php
declare(strict_types=1);

namespace KajStrom\DependencyConstraints;


class FileAnalyzer
{
    /**
     * @var string
     */
    private $path;

    /** @var  Package|null */
    private $package;

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

        //fwrite(STDERR, print_r(array_slice($tokens, 5, 10), true));

        $tokenCount = count($tokens);
        $packageName = null;
        for ($index = 0; $index < $tokenCount; $index++) {
            if (!is_array($tokens[$index])) {
                continue;
            }

            if (T_NAMESPACE === $tokens[$index][0]) {
                $index += 2;

                $packageName = "";
                while (";" !== $tokens[$index]) {
                    $packageName .= $tokens[$index][1];

                    $index++;
                }
            }
        }


        if (!is_null($packageName)) {
            $this->package = new Package($packageName);
        }
    }

    public function getPackage() : ?Package
    {
        return $this->package;
    }
}