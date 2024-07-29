<?php

declare(strict_types = 1);

namespace App\Domain\Session\Service;

use App\Domain\Session\Session\SessionInterface;
use App\Domain\User\Repository\UsersessionRepository;
use Cake\Chronos\Chronos;

/**
 * Service.
 */
final class Authorizer
{
    private SessionInterface $session;

    private UsersessionRepository $usersessionRepository;

    /**
     * Constructor.
     *
     * @param SessionInterface $session The session
     * @param UsersessionRepository $usersessionRepository The usersession repo
     */
    public function __construct(
        SessionInterface $session,
        UsersessionRepository $usersessionRepository
    ) {
        $this->session = $session;
        $this->usersessionRepository = $usersessionRepository;
    }

    /**
     * Check the session is authorized.
     *
     * @return bool
     */
    public function isAuthorized(): bool
    {
        if (!$this->hasAuthorization()) {
            return false;
        }
        $userId = $this->session->get('userId');
        if ($this->isSessionExpired($userId)) {
            return false;
        }
        $this->setLastActive($userId);

        return true;
    }

    /**
     * Check if the session has already authorized.
     *
     * @return bool
     */
    private function hasAuthorization(): bool
    {
        if (!$this->session->isStarted()) {
            $this->session->start();
        }

        return $this->session->has('userId');
    }

    /**
     * Check if the session is expired.
     *
     * @param int $userId The userid
     *
     * @return bool
     */
    private function isSessionExpired(int $userId): bool
    {
        if ($this->usersessionRepository->existsUsersessionId($userId)) {
            $sessionData = $this->usersessionRepository->getUsersessionById($userId);
            if (!$sessionData->isExpired()) {
                return false;
            }
        }

        return true;
    }

    /**
     * Set lastactive.
     *
     * @param int $userId The userid
     */
    private function setLastActive(int $userId): void
    {
        $now = new Chronos();
        $lastactive = $now->toDateTimeString();
        $this->usersessionRepository->updateLastactive($userId, $lastactive);
    }
}
