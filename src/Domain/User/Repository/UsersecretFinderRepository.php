<?php

declare(strict_types = 1);

namespace App\Domain\User\Repository;

use App\Domain\User\Data\UsersecretData;
use App\Factory\QueryFactory;
use App\Support\Hydrator;
use Cake\Chronos\Chronos;

/**
 * Repository.
 */
final class UsersecretFinderRepository
{
    private QueryFactory $queryFactory;

    private Hydrator $hydrator;

    public const TABLE_NAME = 'usersecret';

    public const TABLE_KEY = 'userid';

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
     * Find usersecret.
     *
     * @param int $userId The userid
     * @param string|null $expire The expire
     *
     * @return UsersecretData[] A list of roles
     */
    public function findUsersecret(int $userId, ?string $expire = null): array
    {
        $query = $this->queryFactory->newSelect(self::TABLE_NAME)
            ->select(UsersecretData::FIELDS)
            ->andWhere([
                self::TABLE_KEY => $userId,
                'expire >=' => $expire ?? (new Chronos())->toDateTimeString(),
                'inactive' => 0,
            ]);

        $rows = $query->execute()->fetchAll('assoc') ?: [];

        // Convert to list of objects
        return $this->hydrator->hydrate($rows, UsersecretData::class);
    }

    /**
     * Find earlier usersecret.
     *
     * @param int $userId The userid
     * @param string $start The period start
     * @param string $end The period end
     * @param int $limit The max record num
     *
     * @return UsersecretData[] A list of secrets
     */
    public function findUsersecretInperiod(int $userId, string $start, string $end, int $limit): array
    {
        $query = $this->queryFactory->newSelect(self::TABLE_NAME)
            ->select(UsersecretData::FIELDS)
            ->andWhere([
                self::TABLE_KEY => $userId,
                'expire >=' => $start,
                'expire <=' => $end,
            ])
            ->orderDesc('expire')
            ->limit($limit);

        $rows = $query->execute()->fetchAll('assoc') ?: [];

        // Convert to list of objects
        return $this->hydrator->hydrate($rows, UsersecretData::class);
    }
}
