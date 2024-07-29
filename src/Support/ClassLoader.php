<?php

declare(strict_types = 1);

namespace App\Support;

/**
 * General class loader.
 */
class ClassLoader
{
    /**
     * Load a class from file.
     *
     * @param string $dir The direcory
     * @param string $namespace The namespace
     * @param string $classname The classname
     *
     * @throws ClassLoaderErrorException
     *
     * @return mixed The object form the class
     */
    public function loadClass(string $dir, string $namespace, string $classname)
    {
        $classname = preg_replace("/\-+/", '_', $classname) ?? '';
        if ('' == $classname) {
            throw new ClassLoaderErrorException('Invalid classname <strong>' . $classname . '</strong>');
        }
        $filename = $this->makefilename($dir, $classname);
        if (!file_exists($filename)) {
            throw new ClassLoaderErrorException('Missing class file for <strong>' . $classname . '</strong>');
        }
        try {
            /* @ */ include_once $filename;

            return $this->createClass($namespace . $classname);
        } catch (\Exception $exc) {
            throw new ClassLoaderErrorException($exc->getMessage());
        }
    }

    /**
     * Make filename.
     *
     * @param string $dir The directory
     * @param string $classname The classname
     *
     * @return string The filename
     */
    public function makeFilename(string $dir, string $classname): string
    {
        $len = strlen($dir);
        if ($len > 0 && $dir[$len - 1] != DIRECTORY_SEPARATOR) {
            $dir .= DIRECTORY_SEPARATOR;
        }
        $filename = $dir . $classname;
        if (strpos($filename, '.php') === false) {
            $filename .= '.php';
        }

        return $filename;
    }

    /**
     * Create object from a classname.
     *
     * @param string $classname The classname
     *
     * @throws ClassLoaderErrorException
     *
     * @return mixed The object
     */
    public function createClass(string $classname)
    {
        if (!class_exists($classname, false)) {
            throw new ClassLoaderErrorException('Class not found <strong>' . $classname . '</strong>');
        }

        return new $classname();
    }
}
