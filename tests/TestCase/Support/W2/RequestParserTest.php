<?php

declare(strict_types = 1);

namespace App\Test\TestCase\Support;

use App\Support\W2\Operators;
use App\Support\W2\Request;
use App\Support\W2\RequestParser;
use App\Support\W2\Search;
use App\Support\W2\SearchLogic;
use App\Support\W2\Sort;
use App\Support\W2\Types;
use PHPUnit\Framework\TestCase;

/**
 * Test.
 */
class RequestParserTest extends TestCase
{
    private RequestParser $object;

    /**
     * SetUp.
     *
     * @return void
     */
    public function setUp(): void
    {
        $this->object = new RequestParser();
    }

    /**
     * Test.
     *
     * @return void
     */
    public function testSortAsc(): void
    {
        $json = '{"sort":[{"field":"userid","direction":"asc"}]}';
        $data = json_decode($json);
        $actual = $this->object->parseSort((array)$data->sort[0]);
        $expected = new Sort(['field' => 'userid', 'direction' => Sort::ASC]);
        $this->assertEquals($expected, $actual);
    }

    /**
     * Test.
     *
     * @return void
     */
    public function testSortDesc(): void
    {
        $json = '{"sort":[{"field":"userid","direction":"desc"}]}';
        $data = json_decode($json);
        $actual = $this->object->parseSort((array)$data->sort[0]);
        $expected = new Sort(['field' => 'userid', 'direction' => Sort::DESC]);
        $this->assertEquals($expected, $actual);
    }

    /**
     * Test.
     *
     * @return void
     */
    public function testSearchList(): void
    {
        $json = '{"search":[{"field":"fk_utypeid","type":"list","operator":"is","value":1,"text":"Admin"}]}';
        $data = json_decode($json);
        $actual = $this->object->parseSearch((array)$data->search[0]);
        $expected = new Search([
            'field' => 'fk_utypeid',
            'type' => Types::LIST,
            'operator' => Operators::EQ,
            'value' => 1,
        ]);
        $this->assertEquals($expected, $actual);
    }

    /**
     * Test.
     *
     * @return void
     */
    public function testSearchEnum(): void
    {
        $json = '{"search":[{"field":"roles","type":"enum","operator":' .
            '"not in","value":[{"id":1,"text":"Admin"}],"svalue":[1]}]}';
        $data = json_decode($json);
        $actual = $this->object->parseSearch((array)$data->search[0]);
        $expected = new Search([
            'field' => 'roles',
            'type' => Types::ENUM,
            'operator' => Operators::NOTINLIST,
            'value' => [1],
        ]);
        $this->assertEquals($expected, $actual);
    }

    /**
     * Test.
     *
     * @return void
     */
    public function testRequest(): void
    {
        $json = '{"limit":100,"offset":0,"searchLogic":"OR",' .
            '"search":[{"field":"userid","type":"int","operator":"<=","value":1}],' .
            '"sort":[{"field":"userid","direction":"asc"}]}';
        $data = json_decode($json);
        $actual = $this->object->parseRequest((array)$data);
        $expected = (new Request())
            ->setLimit(100)
            ->setOffset(0)
            ->setSearchLogic(SearchLogic::OR)
            ->addSearch(new Search([
                'field' => 'userid',
                'type' => Types::INT,
                'operator' => Operators::LE,
                'value' => 1,
            ]))
            ->addSort(new Sort([
                'field' => 'userid',
                'direction' => Sort::ASC,
            ]));

        $this->assertEquals($expected, $actual);
    }
}
