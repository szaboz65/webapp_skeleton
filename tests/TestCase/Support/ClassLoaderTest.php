<?php

declare(strict_types = 1);

namespace App\Test\TestCase\Support;

use App\Support\ClassLoader;
use App\Support\ClassLoaderErrorException;
use PHPUnit\Framework\TestCase;

/**
 * Test.
 */
class ClassLoaderTest extends TestCase
{
    protected ClassLoader $loader;

    /**
     * Set up.
     *
     * @return void
     */
    public function setUp(): void
    {
        $this->loader = new ClassLoader();
    }

    /**
     * Test.
     *
     * @return void
     */
    public function testMakeFilename(): void
    {
        $dir = '\var\html';
        $classname = 'valami';
        $expected = '\var\html\valami.php';
        $actual = $this->loader->makeFilename($dir, $classname);
        $this->assertEquals($expected, $actual);
    }

    /**
     * Test.
     *
     * @return void
     */
    public function testCreateClass(): void
    {
        require_once dirname(dirname(dirname(__DIR__))) . '/src/Support/Settings.php';
        $expected = new \App\Support\Settings();
        $actual = $this->loader->createClass('\App\Support\Settings');
        $this->assertEquals($expected, $actual);
    }

    /**
     * Test.
     *
     * @return void
     */
    public function testCreateClassMissing(): void
    {
        require_once dirname(dirname(dirname(__DIR__))) . '/src/Support/Settings.php';
        $this->expectException(ClassLoaderErrorException::class);
        $this->loader->createClass('MissingClass');
    }

    /**
     * Test.
     *
     * @return void
     */
    public function testLoadClass(): void
    {
        $directory = dirname(dirname(dirname(__DIR__))) . '/src/Support';
        $namespace = '\App\Support\\';
        $classname = 'Settings';
        $filename = $directory . '/' . $classname . '.php';
        require_once $filename;
        $expected = new \App\Support\Settings();
        $actual = $this->loader->loadClass($directory, $namespace, $classname);
        $this->assertEquals($expected, $actual);
    }

    /**
     * Test.
     *
     * @return void
     */
    public function testLoadClassEmptyName(): void
    {
        $this->expectException(ClassLoaderErrorException::class);
        $this->loader->loadClass('', '', '');
    }

    /**
     * Test.
     *
     * @return void
     */
    public function testLoadClassMissingFile(): void
    {
        $this->expectException(ClassLoaderErrorException::class);
        $this->loader->loadClass('foo', 'bar', 'baz');
    }
}
