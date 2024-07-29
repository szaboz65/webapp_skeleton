<?php

declare(strict_types = 1);

namespace App\Domain\User\Repository;

use App\Domain\User\Data\UserFindData;
use App\Support\Hydrator;
use App\Support\W2\FinderRepositoryBase;
use App\Support\W2\Request;

/**
 * Repository.
 */
final class UserFinderRepository
{
    private FinderRepositoryBase $finderRepositoryBase;

    private Hydrator $hydrator;

    /**
     * The constructor.
     *
     * @param FinderRepositoryBase $finderRepositoryBase The Base finder
     * @param Hydrator $hydrator The hydrator
     */
    public function __construct(FinderRepositoryBase $finderRepositoryBase, Hydrator $hydrator)
    {
        $this->finderRepositoryBase = $finderRepositoryBase;
        $this->hydrator = $hydrator;
    }

    /**
     * Get total number of last found users.
     *
     * @return int Number of users
     */
    public function getTotal(): int
    {
        return $this->finderRepositoryBase->getTotal();
    }

    /**
     * Find users.
     *
     * @param Request|null $request The request
     *
     * @return UserFindData[] A list of users
     */
    public function findUsers(?Request $request = null): array
    {
        $query = $this->finderRepositoryBase->newSelect('user')
            ->select(UserFindData::fields())
            ->leftJoin('usertype', 'fk_utypeid = utypeid')
            ->leftJoin('userpref', 'userid = upref_id')
            ->leftJoin('usersession', 'userid = ses_userid');

        $rows = $this->finderRepositoryBase->findRecords($query, $request);

        // Convert to list of objects
        return $this->hydrator->hydrate($rows, UserFindData::class);
    }
}
