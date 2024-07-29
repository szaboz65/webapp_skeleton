<?php

declare(strict_types = 1);

namespace App\Support\W2;

use Cake\Database\Query;

/**
 * The finder request transform to Query.
 */
final class RequestToQuery
{
    /**
     * Transform limit and offset from request to query.
     *
     * @param Request $request The request
     * @param Query $query The query
     *
     * @return self
     */
    public function transformLimitAndOffset(Request $request, Query $query): self
    {
        $query->limit($request->limit);
        $query->offset($request->offset);

        return $this;
    }

    /**
     * Transform sort data from request to query.
     *
     * @param Request $request The request
     * @param Query $query The query
     *
     * @return self
     */
    public function transformSort(Request $request, Query $query): self
    {
        if (count($request->sort) > 0) {
            $orders = [];
            foreach ($request->sort as &$sort) {
                $orders[$sort->field] = $sort->direction === Sort::DESC ? 'DESC' : 'ASC';
            }
            $query->order($orders);
        }

        return $this;
    }

    /**
     * Transform search data from request to query.
     *
     * @param Request $request The request
     * @param Query $query The query
     *
     * @return self
     */
    public function transformSearch(Request $request, Query $query): self
    {
        if (count($request->search) == 0) {
            return $this;
        }

        $exp = $query->newExpr();
        if ($request->searchLogic === SearchLogic::OR) {
            $exp->setConjunction('OR');
        }
        foreach ($request->search as &$search) {
            switch ($search->operator) {
                case Operators::EQ:
                    $exp->eq($search->field, $search->value);
                    break;
                case Operators::NE:
                    $exp->notEq($search->field, $search->value);
                    break;
                case Operators::GT:
                    $exp->gt($search->field, $search->value);
                    break;
                case Operators::LT:
                    $exp->lt($search->field, $search->value);
                    break;
                case Operators::GE:
                    $exp->gte($search->field, $search->value);
                    break;
                case Operators::LE:
                    $exp->lte($search->field, $search->value);
                    break;
                case Operators::BETWEEN:
                    $exp->between($search->field, $search->value[0], $search->value[1]);
                    break;
                case Operators::BEGINS:
                    $exp->like($search->field, $search->value . '%');
                    break;
                case Operators::ENDS:
                    $exp->like($search->field, '%' . $search->value);
                    break;
                case Operators::CONTAINS:
                    $exp->like($search->field, '%' . $search->value . '%');
                    break;
                case Operators::ISNULL:
                    $exp->isNull($search->field);
                    break;
                case Operators::ISNOTNULL:
                    $exp->isNotNull($search->field);
                    break;
                case Operators::INLIST:
                    $exp->in($search->field, $search->value);
                    break;
                case Operators::NOTINLIST:
                    $exp->notIn($search->field, $search->value);
                    break;
            }
        }

        $query->where($exp);

        return $this;
    }

    /**
     * Transform a request to query.
     *
     * @param Request $request The request
     * @param Query $query The query
     *
     * @return self
     */
    public function transformToQuery(Request $request, Query $query): self
    {
        return $this
            ->transformSearch($request, $query)
            ->transformSort($request, $query)
            ->transformLimitAndOffset($request, $query);
    }
}
