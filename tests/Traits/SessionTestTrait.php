<?php

declare(strict_types = 1);

namespace App\Test\Traits;

use App\Domain\Session\Session\SessionInterface;
use App\Domain\User\Data\UsersessionData;
use App\Domain\User\Repository\UsersessionRepository;
use App\Factory\QueryFactory;
use App\Support\W2\RepositoryBase;

/**
 * Session Test Trait.
 */
trait SessionTestTrait
{
    /**
     * Create session with given userid.
     *
     * @param int $userId Th euserid
     *
     * @return void
     */
    protected function withSession(int $userId): void
    {
        $session = $this->container->get(SessionInterface::class);
        if (!$session->isStarted()) {
            $session->start();
        }
        $session->set('userId', $userId);

        $queryFactory = $this->container->get(QueryFactory::class);
        $repositoryBase = new RepositoryBase($queryFactory);
        $usersessionRepository = new UsersessionRepository($repositoryBase);
        if (!$usersessionRepository->existsUsersessionId($userId)) {
            $usersessionData = new UsersessionData(['ses_userid' => $userId]);
            $usersessionRepository->insertUsersession($usersessionData);
        }
    }

    /**
     * Destroy session if it exists.
     *
     * @return void
     */
    protected function withoutSession(): void
    {
        $session = $this->container->get(SessionInterface::class);
        if ($session->isStarted()) {
            $session->destroy();
        }
    }
}
