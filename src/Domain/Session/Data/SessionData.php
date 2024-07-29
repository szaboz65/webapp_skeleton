<?php

declare(strict_types = 1);

namespace App\Domain\Session\Data;

use App\Domain\User\Data\UserData;
use App\Domain\User\Data\UserprefData;
use App\Domain\User\Data\UsersessionData;
use App\Domain\User\Data\UsertypeData;

/**
 * Session Data model.
 */
final class SessionData
{
    public UserData $user;

    public UserprefData $pref;

    public UsertypeData $type;

    public UsersessionData $session;

    public array $roles;

    /**
     * Constructor.
     */
    public function __construct()
    {
    }

    /**
     * Transform to array.
     *
     * @return array
     */
    public function transform(): array
    {
        $result = [
            'user' => isset($this->user) ? $this->user->transform() : [],
            'pref' => isset($this->pref) ? $this->pref->transform() : [],
            'type' => isset($this->type) ? $this->type->transform() : [],
            'session' => isset($this->session) ? $this->session->transform() : [],
            'roles' => [],
        ];
        foreach ($this->roles as &$role) {
            $result['roles'][] = $role->transform();
        }
        if ($this->session->isExpired()) {
            $result['session']['status'] = 'inactive';
        } else {
            $remained_time = $this->session->getRemainedTime();
            if ($remained_time < 0) {
                $remained_time = 0;
            }
            $result['session']['status'] = 'active';
            $result['session']['remained_time'] = $remained_time;
        }

        return $result;
    }
}
