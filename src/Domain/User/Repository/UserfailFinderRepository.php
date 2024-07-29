<?php

declare(strict_types = 1);

namespace App\Domain\User\Repository;

use App\Domain\User\Data\UserfailData;
use App\Factory\QueryFactory;
use App\Support\Hydrator;

/**
 * Repository.
 */
final class UserfailFinderRepository
{
    private QueryFactory $queryFactory;

    private Hydrator $hydrator;

    public const TABLE_NAME = 'userfail';

    public const TABLE_KEY = 'fail_userid';

    /**
     * The constructor.
     *
     * @param QueryFactory $queryFactory The query factory
     * @param Hydrator $hydrator The hydrator
     */
    public function __construct(QueryFactory $queryFactory, Hydrator $hydrator)
    {
        $this->queryFactory = $queryFactory;
        $this->hydrator = $hydrator;
    }

    /**
     * Find userfail.
     *
     * @param int $userId The userid
     * @param string|null $min The range min of occured
     * @param string|null $max The range max of occured
     *
     * @return UserfailData[] A list of fails
     */
    public function findUserfail(int $userId, ?string $min = null, ?string $max = null): array
    {
        $where = [self::TABLE_KEY => $userId];
        if ($min) {
            $where[] = ['fail_occured >=' => $min];
        }
        if ($max) {
            $where[] = ['fail_occured <=' => $max];
        }

        $query = $this->queryFactory->newSelect(self::TABLE_NAME)
            ->select(UserfailData::FIELDS)
            ->andWhere($where);

        $rows = $query->execute()->fetchAll('assoc') ?: [];

        // Convert to list of objects
        return $this->hydrator->hydrate($rows, UserfailData::class);
    }
}
