<?php

declare(strict_types = 1);

namespace App\Domain\Session\Service;

use App\Domain\Session\Data\SessionData;
use App\Domain\User\Service\RoleFinder;
use App\Domain\User\Service\UserprefReader;
use App\Domain\User\Service\UserReader;
use App\Domain\User\Service\UsersessionReader;
use App\Domain\User\Service\UsertypeReader;
use App\Support\BinArrayConverter;

/**
 * Repository.
 */
final class SessionReader
{
    private RoleFinder $roleFinder;

    private UserReader $userReader;

    private UserprefReader $userprefReader;

    private UsertypeReader $usertypeReader;

    private UsersessionReader $usersessionReader;

    /**
     * Constructor.
     *
     * @param UserReader $userReader The user reader
     * @param UserprefReader $userprefReader The userpref reader
     * @param UsertypeReader $usertypeReader The usertype reader
     * @param UsersessionReader $usersessionReader The usersession reader
     * @param RoleFinder $roleFinder The role finder
     */
    public function __construct(
        UserReader $userReader,
        UserprefReader $userprefReader,
        UsertypeReader $usertypeReader,
        UsersessionReader $usersessionReader,
        RoleFinder $roleFinder
    ) {
        $this->userReader = $userReader;
        $this->userprefReader = $userprefReader;
        $this->usertypeReader = $usertypeReader;
        $this->usersessionReader = $usersessionReader;
        $this->roleFinder = $roleFinder;
    }

    /**
     * Get the session data.
     *
     * @param int $userId The user id
     *
     * @return SessionData
     */
    public function getSessionData(int $userId): SessionData
    {
        // Input validation
        // ...

        $sessionData = new SessionData();
        $sessionData->user = $this->userReader->getUserData($userId);
        $sessionData->pref = $this->userprefReader->getUserprefData($userId);
        $sessionData->session = $this->usersessionReader->getUsersessionData($userId);
        $sessionData->type = $this->usertypeReader->getUsertypeData((int)$sessionData->user->fk_utypeid);
        $sessionData->roles = $this->roleFinder
            ->findRoles(BinArrayConverter::makeArrayFromBin((int)$sessionData->type->roles));

        return $sessionData;
    }
}
