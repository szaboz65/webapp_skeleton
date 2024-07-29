<?php

declare(strict_types = 1);

namespace App\Support;

/**
 * File collector.
 */
class FileCollector
{
    private array $files;

    /**
     * Constructor.
     */
    public function __construct()
    {
        $this->clear();
    }

    /**
     * Clear collected files.
     */
    public function clear(): void
    {
        $this->files = [];
    }

    /**
     * Get the collected files.
     *
     * @return array Filenames
     */
    public function get(): array
    {
        return $this->files;
    }

    /**
     * Add a file to the collected files.
     *
     * @param string $file The filename
     */
    public function add(string $file): void
    {
        $this->files[] = $file;
    }

    /**
     * Read filenames from the given directory.
     *
     * @param string $dir The directory
     * @param string $filemask The filemask
     *
     * @return array The filenames
     */
    public function collectFiles(string $dir, string $filemask): array
    {
        $handle = opendir($dir);
        if (!$handle) {
            return [];
        }
        $files = [];
        while (false !== ($file = readdir($handle))) {
            if (preg_match($filemask, $file) == 1) {
                $this->add($file);
                $files[] = $file;
            }
        }
        closedir($handle);

        return $files;
    }
}
