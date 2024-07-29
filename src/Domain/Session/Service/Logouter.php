<?php

declare(strict_types = 1);

namespace App\Domain\Session\Service;

use App\Domain\Session\Session\SessionInterface;
use App\Domain\User\Repository\UsersessionRepository;

/**
 * Service.
 */
final class Logouter
{
    private SessionInterface $session;

    private UsersessionRepository $usersessionRepository;

    /**
     * The constructor.
     *
     * @param SessionInterface $session The session
     * @param UsersessionRepository $usersessionRepository The session repo
     */
    public function __construct(
        SessionInterface $session,
        UsersessionRepository $usersessionRepository
    ) {
        $this->session = $session;
        $this->usersessionRepository = $usersessionRepository;
    }

    /**
     * Log out a user.
     *
     * @return void
     */
    public function logout(): void
    {
        if ($this->session->isStarted()) {
            $userId = $this->session->get('userId');
            $this->usersessionRepository->deleteUsersessionById($userId);
            $this->session->destroy();
        }
    }
}
