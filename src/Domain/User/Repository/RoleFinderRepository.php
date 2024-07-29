<?php

declare(strict_types = 1);

namespace App\Domain\User\Repository;

use App\Domain\User\Data\RoleData;
use App\Factory\QueryFactory;
use App\Support\Hydrator;

/**
 * Repository.
 */
final class RoleFinderRepository
{
    private QueryFactory $queryFactory;

    private Hydrator $hydrator;

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
     * Find roles.
     *
     * @param array|null $roles The role id filter
     *
     * @return RoleData[] A list of roles
     */
    public function findRoles(?array $roles = null): array
    {
        $query = $this->queryFactory->newSelect('role')
            ->select(RoleData::FIELDS);

        // Add more "use case specific" conditions to the query
        if (isset($roles)) {
            $query->whereInList('roleid', $roles);
        }

        $rows = $query->execute()->fetchAll('assoc') ?: [];

        // Convert to list of objects
        return $this->hydrator->hydrate($rows, RoleData::class);
    }
}
