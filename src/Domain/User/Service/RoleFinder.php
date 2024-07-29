<?php

declare(strict_types = 1);

namespace App\Domain\User\Service;

use App\Domain\User\Data\RoleData;
use App\Domain\User\Repository\RoleFinderRepository;

/**
 * Service.
 */
final class RoleFinder
{
    private RoleFinderRepository $repository;

    /**
     * The constructor.
     *
     * @param RoleFinderRepository $repository The repository
     */
    public function __construct(RoleFinderRepository $repository)
    {
        $this->repository = $repository;
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
        // Input validation
        // ...

        return $this->repository->findRoles($roles);
    }
}
