<?php

declare(strict_types = 1);

namespace App\Test\TestCase\Support;

use PHPUnit\Framework\TestCase;

/**
 * Test.
 */
class TemplateEngineTest extends TestCase
{
    protected FakeTemplateEngine $object;

    /**
     * Set up.
     *
     * @return void
     */
    public function setUp(): void
    {
        $this->object = new FakeTemplateEngine();
        $this->object->setFileExt('html');
        $this->object->setTemplatePath($this->getDir() . 'templates/tests');
    }

    /**
     * Gett base directory.
     *
     * @return string
     */
    public function getDir(): string
    {
        $dir = __DIR__;
        $path = explode('tests', $dir);

        return $path[0];
    }

    /**
     * Test.
     *
     * @return void
     */
    public function testContruct(): void
    {
        $object = new FakeTemplateEngine();
        $this->assertEquals('html', $object->getFileExt());
        $this->assertEquals('', $object->getTemplatePath());
        $this->assertEquals(0, count($object->getValueMap()));
    }

    /**
     * Test.
     *
     * @return void
     */
    public function testSetters(): void
    {
        $this->object->setFileExt('foo');
        $this->assertEquals('foo', $this->object->getFileExt());
        $this->object->setTemplatePath('bar');
        $this->assertEquals('bar', $this->object->getTemplatePath());
    }

    /**
     * Test.
     *
     * @return void
     */
    public function testAddValueMap(): void
    {
        $nameValueMap = ['k1' => 'v1', 'k2' => 'v2', 'k3' => [1, 2, 3, 4, 5]];
        $this->object->setValueMap($nameValueMap);
        $this->assertEquals($nameValueMap, $this->object->getValueMap());
    }

    /**
     * Test.
     *
     * @return void
     */
    public function testAddValue(): void
    {
        $this->object->addValue('k1', 'v1');
        $this->object->addValue('k2', 'v2');
        $this->object->addValue('k3', 'v3');
        $this->assertEquals(3, count($this->object->getValueMap()));
    }

    /**
     * Test.
     *
     * @return void
     */
    public function testGetFileName(): void
    {
        $tempname = 'pwreset';
        $expected = $this->object->getTemplatePath() . DIRECTORY_SEPARATOR .
                $tempname . '.' . $this->object->getFileExt();
        $this->assertEquals($expected, $this->object->getFileName($tempname));
    }

    /**
     * Test.
     *
     * @return void
     */
    public function testGetFileContents(): void
    {
        $tempname = 'test';
        $expected = 'test';
        $this->assertEquals($expected, $this->object->getFileContents($tempname));
    }

    /**
     * Test.
     *
     * @return void
     */
    public function testGetFileContentsNoFile(): void
    {
        $tempname = 'none';
        $this->expectException(\Exception::class);
        // $this->expectExceptionMessageMatches('');
        $this->object->getFileContents($tempname);
    }

    /**
     * Test.
     *
     * @return void
     */
    public function testGetParams(): void
    {
        $this->assertEquals('', $this->object->agetParams('test'));
        $this->assertEquals('123', $this->object->agetParams('(123)'));
        $this->assertEquals('123', $this->object->agetParams('xx(123)yy'));
        $this->assertEquals('123', $this->object->agetParams('( 123)'));
        $this->assertEquals('123', $this->object->agetParams('(123 )'));
    }

    /**
     * Test.
     *
     * @return void
     */
    public function testGetFuncPrefix(): void
    {
        $this->assertEquals('var', $this->object->agetFuncPrefix('test'));
        $this->assertEquals('fn', $this->object->agetFuncPrefix('fn()'));
        $this->assertEquals('fn', $this->object->agetFuncPrefix('fn(name)'));
        $this->assertEquals('fn', $this->object->agetFuncPrefix(' fn(name)'));
        $this->assertEquals('fn', $this->object->agetFuncPrefix('fn (name)'));
        $this->assertEquals('fn', $this->object->agetFuncPrefix(' fn (name)'));
    }

    /**
     * Test.
     *
     * @return void
     */
    public function testParseInclude(): void
    {
        $this->assertEquals('test', $this->object->aparseInclude('include(test)'));
    }

    /**
     * Test.
     *
     * @return void
     */
    public function testParseForeachOk(): void
    {
        $this->object->addValue('arr', ['1', '2', '3']);
        $this->assertEquals('123', $this->object->aparseForeach('foreach(arr)'));
    }

    /**
     * Test.
     *
     * @return void
     */
    public function testParseForeachNonstring(): void
    {
        $this->object->addValue('arr', [1, 2, 3]);
        $this->expectException(\Exception::class);
        // $this->expectExceptionMessageMatches('');
        $this->object->aparseForeach('foreach(arr)');
    }

    /**
     * Test.
     *
     * @return void
     */
    public function testParseForeachNonarray(): void
    {
        $this->object->addValue('arr', 'xx');
        $this->expectException(\Exception::class);
        // $this->expectExceptionMessageMatches('');
        $this->object->aparseForeach('foreach(arr)');
    }

    /**
     * Test.
     *
     * @return void
     */
    public function testParseForeachNovalue(): void
    {
        $this->expectException(\Exception::class);
        // $this->expectExceptionMessageMatches('');
        $this->object->aparseForeach('foreach(arr)');
    }

    /**
     * Test.
     *
     * @return void
     */
    public function testParseForeachParam(): void
    {
        $this->object->addValue('arr', ['1', '2', '3']);
        $this->assertEquals(
            '<li>1</li><li>2</li><li>3</li>',
            $this->object->aparseForeach('foreach(arr,<li>%s</li>)')
        );
    }

    /**
     * Test.
     *
     * @return void
     */
    public function testParseIfexists(): void
    {
        $this->object->addValue('str', 'value');
        $this->assertEquals('value', $this->object->aparseIf('if(str)'));
        $this->assertEquals('value', $this->object->aparseIf('if( str)'));
        $this->assertEquals('value', $this->object->aparseIf('if(str )'));
    }

    /**
     * Test.
     *
     * @return void
     */
    public function testParseIfnonexists(): void
    {
        $this->assertEquals('', $this->object->aparseIf('if(str)'));
    }

    /**
     * Test.
     *
     * @return void
     */
    public function testParseIfelse(): void
    {
        $this->object->addValue('def', 'defvalue');
        $this->assertEquals('defvalue', $this->object->aparseIf('if(str,def)'));
        $this->assertEquals('defvalue', $this->object->aparseIf('if( str , def )'));
    }

    /**
     * Test.
     *
     * @return void
     */
    public function testParseVarExists(): void
    {
        $this->object->addValue('str', 'value');
        $this->assertEquals('value', $this->object->aparseVar('str'));
    }

    /**
     * Test.
     *
     * @return void
     */
    public function testParseVarNovalue(): void
    {
        $this->expectException(\Exception::class);
        // $this->expectExceptionMessageMatches('');
        $this->object->aparseVar('arr');
    }

    /**
     * Test.
     *
     * @return void
     */
    public function testParseTemplateStatementVar(): void
    {
        $this->object->addValue('str', 'value');
        $this->assertEquals('value', $this->object->aparseTemplateStatement('str'));
    }

    /**
     * Test.
     *
     * @return void
     */
    public function testParseTemplateStatementIf(): void
    {
        $this->object->addValue('str', 'value');
        $this->assertEquals('value', $this->object->aparseTemplateStatement('if(str)'));
    }

    /**
     * Test.
     *
     * @return void
     */
    public function testParseTemplateStatementForeach(): void
    {
        $this->object->addValue('arr', ['value']);
        $this->assertEquals('value', $this->object->aparseTemplateStatement('foreach(arr)'));
    }

    /**
     * Test.
     *
     * @return void
     */
    public function testParseTemplateStatementInclude(): void
    {
        $this->object->addValue('str', 'value');
        $this->assertEquals('test', $this->object->aparseTemplateStatement('include(test)'));
    }

    /**
     * Test.
     *
     * @return void
     */
    public function testParseTemplateStatementUnknown(): void
    {
        $this->expectException(\Exception::class);
        // $this->expectExceptionMessageMatches('');
        $this->object->aparseTemplateStatement('unknown(srt)');
    }

    /**
     * Test.
     *
     * @return void
     */
    public function testRenderTemplateText(): void
    {
        $this->object->addValue('str', 'value');
        $this->assertEquals('text', $this->object->renderTemplate('text'));
    }

    /**
     * Test.
     *
     * @return void
     */
    public function testRenderTemplateVar(): void
    {
        $this->object->addValue('str', 'text');
        $this->assertEquals('text', $this->object->renderTemplate('{str}'));
        $this->assertEquals('text', $this->object->renderTemplate('{ str}'));
        $this->assertEquals('text', $this->object->renderTemplate('{str }'));
    }

    /**
     * Test.
     *
     * @return void
     */
    public function testRenderTemplateIf(): void
    {
        $this->object->addValue('str', 'value');
        $this->assertEquals('value', $this->object->renderTemplate('{if(str)}'));
    }

    /**
     * Test.
     *
     * @return void
     */
    public function testRenderTemplateForeach(): void
    {
        $this->object->addValue('arr', ['value']);
        $this->assertEquals('value', $this->object->renderTemplate('{ foreach(arr) }'));
    }

    /**
     * Test.
     *
     * @return void
     */
    public function testRenderTemplateInclude(): void
    {
        $this->object->addValue('arr', ['value']);
        $this->assertEquals('test', $this->object->renderTemplate('{include(test)}'));
    }

    /**
     * Test test for the recursive rendering at include file.
     *
     * @return void
     */
    public function testRenderTemplateIncludeRecursive(): void
    {
        $this->object->addValue('arr', ['value']);
        $this->assertEquals('test', $this->object->renderTemplate('{include(test_inc)}'));
    }

    // complex test
    /**
     * Test.
     *
     * @return void
     */
    public function testRenderTemplateIncludeComplex1(): void
    {
        $this->object->addValue('str1', 'string 1');
        $this->object->addValue('str2', 'text 2');
        $this->assertEquals(
            "plain\nstring 1\ntext 2\n",
            $this->object->renderTemplate("plain\n{str1}\n{str2}\n")
        );
    }

    /**
     * Test.
     *
     * @return void
     */
    public function testRenderTemplateIncludeComplex4(): void
    {
        $this->object->addValue('arr', ['value1', 'value2']);
        $this->object->addValue('str1', 'string 1');
        $this->object->addValue('str2', 'text 2');
        $this->assertEquals(
            "plain\ntest\nstring 1 s <value1><value2> g text 2\n",
            $this->object->renderTemplate("plain\n{include(test_inc)}\n{str1} s {foreach(arr,<%s>)} g {str2}\n")
        );
    }

    /**
     * Test.
     *
     * @return void
     */
    public function testRenderValueMap(): void
    {
        $tempname = 'test';
        $expected = 'test';
        $this->assertEquals($expected, $this->object->renderValueMap($tempname, []));
    }

    /**
     * Test.
     *
     * @return void
     */
    public function testRender(): void
    {
        $tempname = 'test';
        $expected = 'test';
        $this->assertEquals($expected, $this->object->render($tempname));
    }
}
