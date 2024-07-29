<?php

declare(strict_types = 1);

namespace App\Domain\User\Data;

/**
 * Data Model.
 */
final class UserFindData
{
    public UserData $userData;

    public UsertypeData $usertypeData;

    public UserprefData $userprefData;

    public UsersessionData $usersessionData;

    /**
     * The constructor.
     *
     * @param array $data The data
     */
    public function __construct(array $data = [])
    {
        $this->userData = new UserData($data);
        $this->usertypeData = new UsertypeData($data);
        $this->userprefData = new UserprefData($data);
        $this->usersessionData = new UsersessionData($data);
    }

    /**
     * The transformer.
     *
     * @return array The data
     */
    public function transform(): array
    {
        return array_merge(
            $this->userData->transform(),
            $this->usertypeData->transform(),
            $this->userprefData->transform(),
            $this->usersessionData->transform()
        );
    }

    /**
     * The fields.
     *
     * @return array The fields
     */
    public static function fields(): array
    {
        return array_merge(UserData::FIELDS, UsertypeData::FIELDS, UserprefData::FIELDS, UsersessionData::FIELDS);
    }
}
