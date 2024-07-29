<?php

declare(strict_types = 1);

namespace App\Domain\User\Service;

use App\Domain\User\Data\UsertypeData;
use App\Domain\User\Repository\UsertypeFinderRepository;

/**
 * Service.
 */
final class UsertypeFinder
{
    private UsertypeFinderRepository $repository;

    /**
     * The constructor.
     *
     * @param UsertypeFinderRepository $repository The repository
     */
    public function __construct(UsertypeFinderRepository $repository)
    {
        $this->repository = $repository;
    }

    /**
     * Find roles.
     *
     * @return UsertypeData[] A list of roles
     */
    public function findUsertypes(): array
    {
        // Input validation
        // ...

        return $this->repository->findUsertypes();
    }
}
