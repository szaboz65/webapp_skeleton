<?php

declare(strict_types = 1);

namespace App\Domain\Session\Service;

use App\Domain\User\Data\UserpassresetData;
use App\Domain\User\Data\UsersecretData;
use App\Domain\User\Repository\UserpassresetRepository;
use App\Domain\User\Repository\UsersecretFinderRepository;
use App\Domain\User\Repository\UsersecretRepository;
use App\Domain\User\Service\UserFinder;
use App\Support\SettingsInterface;
use App\Support\Validation\ValidationException;
use Cake\Chronos\Chronos;
use Selective\ArrayReader\ArrayReader;

/**
 * Service.
 */
final class ResetPassword
{
    private SettingsInterface $settings;

    private ResetPasswordValidator $validator;

    private UserFinder $userFinder;

    private UserpassresetRepository $userpassresetRepository;

    private UsersecretFinderRepository $usersecretFinderRepository;

    private UsersecretRepository $usersecretRepository;

    public const MESSAGE = 'Please check your input';

    /**
     * The constructor.
     *
     * @param SettingsInterface $settings The settings
     * @param ResetPasswordValidator $validator The validator
     * @param UserFinder $userFinder The user finder repo
     * @param UserpassresetRepository $usersessionRepository The session repo
     * @param UsersecretFinderRepository $usersecretFinderRepository The secret finder repo
     * @param UsersecretRepository $usersecretRepository The secret repo
     */
    public function __construct(
        SettingsInterface $settings,
        ResetPasswordValidator $validator,
        UserFinder $userFinder,
        UserpassresetRepository $usersessionRepository,
        UsersecretFinderRepository $usersecretFinderRepository,
        UsersecretRepository $usersecretRepository
    ) {
        $this->settings = $settings;
        $this->validator = $validator;
        $this->userFinder = $userFinder;
        $this->userpassresetRepository = $usersessionRepository;
        $this->usersecretFinderRepository = $usersecretFinderRepository;
        $this->usersecretRepository = $usersecretRepository;
    }

    /**
     * Reset password with form data.
     *
     * @param array $data The dat
     *
     * @return void
     */
    public function reset(array $data): void
    {
        $this->validator->validateLogin($data);

        $reader = new ArrayReader($data);
        $email = (string)$reader->findString('pwemail');
        $resetCode = (string)$reader->findString('reset_code');
        $password = (string)$reader->findString('pw');

        $this->resetPassword($email, $resetCode, $password);
    }

    /**
     * Reset password.
     *
     * @param string $email The email to send resetcode
     * @param string $resetCode The reset code
     * @param string $password The new password
     *
     * @return void
     */
    public function resetPassword(string $email, string $resetCode, string $password): void
    {
        $userId = $this->getUserId($email);
        $userpassresetData = $this->loadUserpassresetData($userId);

        $this->checkResetCode($userpassresetData, $resetCode);
        $this->checkPasswordRules($password);
        $this->checkPasswordWeakness($password);
        $this->checkPasswordEarliers($userId, $password);

        $this->userpassresetRepository->deleteUserpassresetById($userId);
        $this->invalidateEarlierPasswords($userId);
        $this->storePassword($userId, $password);
    }

    /**
     * Get userid by email.
     *
     * @param string $email The user email
     *
     * @throws ValidationException
     *
     * @return int
     */
    private function getUserId(string $email): int
    {
        $users = $this->userFinder->findUsersByEmail($email);
        if (count($users) !== 1) {
            throw new ValidationException('Unknown email.');
        }

        return (int)$users[0]->userData->userid;
    }

    /**
     * Load userpassreset data.
     *
     * @param int $userId The userid
     *
     * @throws ValidationException
     *
     * @return UserpassresetData
     */
    private function loadUserpassresetData(int $userId): UserpassresetData
    {
        if (!$this->userpassresetRepository->existsUserpassresetId($userId)) {
            throw new ValidationException('Reset code is not sent.');
        }

        $userpassresetData = $this->userpassresetRepository->getUserpassresetById($userId);
        if ($userpassresetData->isExpired()) {
            throw new ValidationException('The reset code is expired.');
        }

        return $userpassresetData;
    }

    /**
     * Check reset code and it is expired.
     *
     * @param UserpassresetData $userpassresetData The pass reset data
     * @param string $resetCode The reset code
     *
     * @return void
     */
    private function checkResetCode(UserpassresetData $userpassresetData, string $resetCode): void
    {
        if ($userpassresetData->reset_code !== $resetCode) {
            throw new ValidationException('The reset code is mismatched.');
        }
    }

    /**
     * Check the password rules.
     *
     * @param string $password The password
     *
     * @throws ValidationException
     *
     * @return void
     */
    private function checkPasswordRules(string $password): void
    {
        $pattern = '/^(?=.*[?!@#$%^&*_-])(?=.*[0-9])(?=.*[A-Z])(?=.*[a-z]).{8,40}$/';
        if (!preg_match($pattern, $password)) {
            throw new ValidationException('The password does not fit the rules.');
        }
    }

    /**
     * Check the earlier passwords of the user.
     *
     * @param int $userId The userid
     * @param string $password The password
     *
     * @throws ValidationException
     *
     * @return void
     */
    private function checkPasswordEarliers(int $userId, string $password): void
    {
        $settings = $this->settings->get('auth');
        $now = new Chronos();
        $end = $now->toDateTimeString();
        $prev = $now->subDays($settings['earlier_pass_period_days']);
        $start = $prev->toDateTimeString();
        $secrets = $this->usersecretFinderRepository
            ->findUsersecretInperiod($userId, $start, $end, $settings['earlier_pass_max']);

        foreach ($secrets as &$secretData) {
            // $secretData->setSecret('azAZ09!?');
            if ($secretData->verifySecret($password)) {
                throw new ValidationException('The password has been used earlier.');
            }
        }
    }

    /**
     * Check password weakness.
     *
     * @param string $password Th epassword
     *
     * @return void
     */
    private function checkPasswordWeakness(string $password): void
    {
        // throw new ValidationException('The password is not strong enough');
    }

    /**
     * Invalidate the earlier passwords.
     *
     * @param int $userId The userid
     *
     * @return void
     */
    private function invalidateEarlierPasswords(int $userId): void
    {
        $start = new Chronos();
        $end = $start->addYears(1);
        $secrets = $this->usersecretFinderRepository
            ->findUsersecretInperiod($userId, $start->toDateTimeString(), $end->toDateTimeString(), 1000);

        foreach ($secrets as &$usersecretData) {
            $usersecretData->setInactive(true);
            $this->usersecretRepository->updateUsersecretInactive($usersecretData);
        }
    }

    /**
     * Store new password.
     *
     * @param int $userId The userid
     * @param string $password The new password
     *
     * @return void
     */
    private function storePassword(int $userId, string $password): void
    {
        $settings = $this->settings->get('auth');
        $now = new Chronos();
        $expire = $now->addDays($settings['pass_expire_days']);
        $usersecretData = new UsersecretData();
        $usersecretData->userid = $userId;
        $usersecretData->setSecret($password);
        $usersecretData->setExpire($expire->toDateTimeString());
        $usersecretData->setInactive(false);
        $this->usersecretRepository->insertUsersecret($usersecretData);
    }
}
