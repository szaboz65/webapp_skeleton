<?php

declare(strict_types = 1);

namespace App\Test\TestCase\Support;

use App\Support\W2\Operators;
use App\Support\W2\Request;
use App\Support\W2\RequestToQuery;
use App\Support\W2\Search;
use App\Support\W2\SearchLogic;
use App\Support\W2\Sort;
use App\Support\W2\Types;
use Cake\Database\Expression\QueryExpression;
use Cake\Database\Query;
use PHPUnit\Framework\TestCase;

/**
 * Test.
 */
class RequestToQueryTest extends TestCase
{
    private RequestToQuery $object;

    /**
     * SetUp.
     *
     * @return void
     */
    public function setUp(): void
    {
        $this->object = new RequestToQuery();
    }

    /**
     * Test.
     *
     * @return void
     */
    public function testTransformLimitAndOffset(): void
    {
        $query_stub = $this->createMock(Query::class);
        $query_stub->expects($this->once())
            ->method('limit')
            ->with(100);
        $query_stub->expects($this->once())
            ->method('offset')
            ->with(0);

        $request = new Request();
        $this->object->transformLimitAndOffset($request, $query_stub);
    }

    /**
     * Test.
     *
     * @return void
     */
    public function testTransformSort(): void
    {
        $query_stub = $this->createMock(Query::class);
        $query_stub->expects($this->once())
            ->method('order')
            ->with([
                'field1' => 'ASC',
                'field2' => 'DESC',
            ]);

        $request = new Request();
        $request->addSort(new Sort(['field' => 'field1', 'direction' => Sort::ASC]))
            ->addSort(new Sort(['field' => 'field2', 'direction' => Sort::DESC]));
        $this->object->transformSort($request, $query_stub);
    }

    /**
     * Test.
     *
     * @return void
     */
    public function testTransformSearchEQNEOR(): void
    {
        $exp_stub = $this->createMock(QueryExpression::class);
        $exp_stub->expects($this->once())
            ->method('setConjunction')
            ->with('OR')
            ->willReturn($exp_stub);
        $exp_stub->expects($this->once())
            ->method('eq')
            ->with('field1', 1, null)
            ->willReturn($exp_stub);
        $exp_stub->expects($this->once())
            ->method('notEq')
            ->with('field2', 'text', null)
            ->willReturn($exp_stub);

        $query_stub = $this->createMock(Query::class);
        $query_stub->expects($this->once())
            ->method('newExpr')
            ->willReturn($exp_stub);
        $query_stub->expects($this->once())
            ->method('where')
            ->with($exp_stub);

        $request = (new Request())
            ->setSearchLogic(SearchLogic::OR)
            ->addSearch(new Search([
                'field' => 'field1',
                'type' => Types::INT,
                'operator' => Operators::EQ,
                'value' => 1,
            ]))
            ->addSearch(new Search([
                'field' => 'field2',
                'type' => Types::TEXT,
                'operator' => Operators::NE,
                'value' => 'text',
            ]));
        $this->object->transformSearch($request, $query_stub);
    }

    /**
     * Test.
     *
     * @return void
     */
    public function testTransformSearchGT(): void
    {
        $exp_stub = $this->createMock(QueryExpression::class);
        $exp_stub->expects($this->once())
            ->method('gt')
            ->with('field1', 1, null)
            ->willReturn($exp_stub);

        $query_stub = $this->createMock(Query::class);
        $query_stub->expects($this->once())
            ->method('newExpr')
            ->willReturn($exp_stub);
        $query_stub->expects($this->once())
            ->method('where')
            ->with($exp_stub);

        $request = (new Request())
            ->addSearch(new Search([
                'field' => 'field1',
                'type' => Types::INT,
                'operator' => Operators::GT,
                'value' => 1,
            ]));
        $this->object->transformSearch($request, $query_stub);
    }

    /**
     * Test.
     *
     * @return void
     */
    public function testTransformSearchLT(): void
    {
        $exp_stub = $this->createMock(QueryExpression::class);
        $exp_stub->expects($this->once())
            ->method('lt')
            ->with('field1', 100, null)
            ->willReturn($exp_stub);

        $query_stub = $this->createMock(Query::class);
        $query_stub->expects($this->once())
            ->method('newExpr')
            ->willReturn($exp_stub);
        $query_stub->expects($this->once())
            ->method('where')
            ->with($exp_stub);

        $request = (new Request())
            ->addSearch(new Search([
                'field' => 'field1',
                'type' => Types::INT,
                'operator' => Operators::LT,
                'value' => 100,
            ]));
        $this->object->transformSearch($request, $query_stub);
    }

    /**
     * Test.
     *
     * @return void
     */
    public function testTransformSearchGE(): void
    {
        $exp_stub = $this->createMock(QueryExpression::class);
        $exp_stub->expects($this->once())
            ->method('gte')
            ->with('field1', 1, null)
            ->willReturn($exp_stub);

        $query_stub = $this->createMock(Query::class);
        $query_stub->expects($this->once())
            ->method('newExpr')
            ->willReturn($exp_stub);
        $query_stub->expects($this->once())
            ->method('where')
            ->with($exp_stub);

        $request = (new Request())
            ->addSearch(new Search([
                'field' => 'field1',
                'type' => Types::INT,
                'operator' => Operators::GE,
                'value' => 1,
            ]));
        $this->object->transformSearch($request, $query_stub);
    }

    /**
     * Test.
     *
     * @return void
     */
    public function testTransformSearchLE(): void
    {
        $exp_stub = $this->createMock(QueryExpression::class);
        $exp_stub->expects($this->once())
            ->method('lte')
            ->with('field1', 100, null)
            ->willReturn($exp_stub);

        $query_stub = $this->createMock(Query::class);
        $query_stub->expects($this->once())
            ->method('newExpr')
            ->willReturn($exp_stub);
        $query_stub->expects($this->once())
            ->method('where')
            ->with($exp_stub);

        $request = (new Request())
            ->addSearch(new Search([
                'field' => 'field1',
                'type' => Types::INT,
                'operator' => Operators::LE,
                'value' => 100,
            ]));
        $this->object->transformSearch($request, $query_stub);
    }

    /**
     * Test.
     *
     * @return void
     */
    public function testTransformSearchISNULL(): void
    {
        $exp_stub = $this->createMock(QueryExpression::class);
        $exp_stub->expects($this->once())
            ->method('isNull')
            ->with('field1')
            ->willReturn($exp_stub);

        $query_stub = $this->createMock(Query::class);
        $query_stub->expects($this->once())
            ->method('newExpr')
            ->willReturn($exp_stub);
        $query_stub->expects($this->once())
            ->method('where')
            ->with($exp_stub);

        $request = (new Request())
            ->addSearch(new Search([
                'field' => 'field1',
                'type' => Types::INT,
                'operator' => Operators::ISNULL,
                'value' => null,
            ]));
        $this->object->transformSearch($request, $query_stub);
    }

    /**
     * Test.
     *
     * @return void
     */
    public function testTransformSearchISNOTNULL(): void
    {
        $exp_stub = $this->createMock(QueryExpression::class);
        $exp_stub->expects($this->once())
            ->method('isNotNull')
            ->with('field1')
            ->willReturn($exp_stub);

        $query_stub = $this->createMock(Query::class);
        $query_stub->expects($this->once())
            ->method('newExpr')
            ->willReturn($exp_stub);
        $query_stub->expects($this->once())
            ->method('where')
            ->with($exp_stub);

        $request = (new Request())
            ->addSearch(new Search([
                'field' => 'field1',
                'type' => Types::INT,
                'operator' => Operators::ISNOTNULL,
                'value' => null,
            ]));
        $this->object->transformSearch($request, $query_stub);
    }

    /**
     * Test.
     *
     * @return void
     */
    public function testTransformSearchBEGINS(): void
    {
        $exp_stub = $this->createMock(QueryExpression::class);
        $exp_stub->expects($this->once())
            ->method('like')
            ->with('field1', '1%')
            ->willReturn($exp_stub);

        $query_stub = $this->createMock(Query::class);
        $query_stub->expects($this->once())
            ->method('newExpr')
            ->willReturn($exp_stub);
        $query_stub->expects($this->once())
            ->method('where')
            ->with($exp_stub);

        $request = (new Request())
            ->addSearch(new Search([
                'field' => 'field1',
                'type' => Types::INT,
                'operator' => Operators::BEGINS,
                'value' => 1,
            ]));
        $this->object->transformSearch($request, $query_stub);
    }

    /**
     * Test.
     *
     * @return void
     */
    public function testTransformSearchENDS(): void
    {
        $exp_stub = $this->createMock(QueryExpression::class);
        $exp_stub->expects($this->once())
            ->method('like')
            ->with('field1', '%1')
            ->willReturn($exp_stub);

        $query_stub = $this->createMock(Query::class);
        $query_stub->expects($this->once())
            ->method('newExpr')
            ->willReturn($exp_stub);
        $query_stub->expects($this->once())
            ->method('where')
            ->with($exp_stub);

        $request = (new Request())
            ->addSearch(new Search([
                'field' => 'field1',
                'type' => Types::INT,
                'operator' => Operators::ENDS,
                'value' => 1,
            ]));
        $this->object->transformSearch($request, $query_stub);
    }

    /**
     * Test.
     *
     * @return void
     */
    public function testTransformSearchCONTAINS(): void
    {
        $exp_stub = $this->createMock(QueryExpression::class);
        $exp_stub->expects($this->once())
            ->method('like')
            ->with('field1', '%1%')
            ->willReturn($exp_stub);

        $query_stub = $this->createMock(Query::class);
        $query_stub->expects($this->once())
            ->method('newExpr')
            ->willReturn($exp_stub);
        $query_stub->expects($this->once())
            ->method('where')
            ->with($exp_stub);

        $request = (new Request())
            ->addSearch(new Search([
                'field' => 'field1',
                'type' => Types::INT,
                'operator' => Operators::CONTAINS,
                'value' => 1,
            ]));
        $this->object->transformSearch($request, $query_stub);
    }

    /**
     * Test.
     *
     * @return void
     */
    public function testTransformSearchINLIST(): void
    {
        $exp_stub = $this->createMock(QueryExpression::class);
        $exp_stub->expects($this->once())
            ->method('in')
            ->with('field1', [1, 2])
            ->willReturn($exp_stub);

        $query_stub = $this->createMock(Query::class);
        $query_stub->expects($this->once())
            ->method('newExpr')
            ->willReturn($exp_stub);
        $query_stub->expects($this->once())
            ->method('where')
            ->with($exp_stub);

        $request = (new Request())
            ->addSearch(new Search([
                'field' => 'field1',
                'type' => Types::INT,
                'operator' => Operators::INLIST,
                'value' => [1, 2],
            ]));
        $this->object->transformSearch($request, $query_stub);
    }

    /**
     * Test.
     *
     * @return void
     */
    public function testTransformSearchNOTINLIST(): void
    {
        $exp_stub = $this->createMock(QueryExpression::class);
        $exp_stub->expects($this->once())
            ->method('notIn')
            ->with('field1', [1, 2])
            ->willReturn($exp_stub);

        $query_stub = $this->createMock(Query::class);
        $query_stub->expects($this->once())
            ->method('newExpr')
            ->willReturn($exp_stub);
        $query_stub->expects($this->once())
            ->method('where')
            ->with($exp_stub);

        $request = (new Request())
            ->addSearch(new Search([
                'field' => 'field1',
                'type' => Types::INT,
                'operator' => Operators::NOTINLIST,
                'value' => [1, 2],
            ]));
        $this->object->transformSearch($request, $query_stub);
    }

    /**
     * Test.
     *
     * @return void
     */
    public function testTransformSearchBETWEEN(): void
    {
        $exp_stub = $this->createMock(QueryExpression::class);
        $exp_stub->expects($this->once())
            ->method('between')
            ->with('field1', 1, 2)
            ->willReturn($exp_stub);

        $query_stub = $this->createMock(Query::class);
        $query_stub->expects($this->once())
            ->method('newExpr')
            ->willReturn($exp_stub);
        $query_stub->expects($this->once())
            ->method('where')
            ->with($exp_stub);

        $request = (new Request())
            ->addSearch(new Search([
                'field' => 'field1',
                'type' => Types::INT,
                'operator' => Operators::BETWEEN,
                'value' => [1, 2],
            ]));
        $this->object->transformSearch($request, $query_stub);
    }
}
