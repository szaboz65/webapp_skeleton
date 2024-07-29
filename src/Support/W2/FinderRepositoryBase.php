<?php

declare(strict_types = 1);

namespace App\Support\W2;

use App\Factory\QueryFactory;
use Cake\Database\Query;

/**
 * FinderRepository Base.
 */
final class FinderRepositoryBase
{
    private QueryFactory $queryFactory;

    private int $total;

    /**
     * The constructor.
     *
     * @param QueryFactory $queryFactory The query factory
     */
    public function __construct(QueryFactory $queryFactory)
    {
        $this->queryFactory = $queryFactory;
        $this->total = 0;
    }

    /**
     * Get total number of last found users.
     *
     * @return int Number of users
     */
    public function getTotal(): int
    {
        return $this->total;
    }

    /**
     * Get a new select query from $table.
     *
     * @param string $table The table name
     *
     * @return Query The new query
     */
    public function newSelect(string $table): Query
    {
        $this->total = 0;

        return $this->queryFactory->newSelect($table);
    }

    /**
     * Find records.
     *
     * @param Query $query The query
     * @param Request|null $request The request
     *
     * @return array A list of rowa
     */
    public function findRecords(Query $query, ?Request $request = null): array
    {
        $transformer = new RequestToQuery();
        if ($request) {
            $transformer->transformSearch($request, $query);
        }

        $cquery = clone $query;
        $cquery->select(['total' => $cquery->func()->count('*')], true);
        $result = $cquery->execute()->fetchAll('assoc');
        $this->total = isset($result['0']['total']) ? intval($result['0']['total']) : 0;

        if ($request) {
            $transformer->transformSort($request, $query);
            $transformer->transformLimitAndOffset($request, $query);
        }

        return $query->execute()->fetchAll('assoc') ?: [];
    }
}
