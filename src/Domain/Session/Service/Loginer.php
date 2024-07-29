<?php

declare(strict_types = 1);

namespace App\Domain\Session\Service;

use App\Domain\Session\Session\SessionInterface;
use App\Domain\User\Data\UserfailData;
use App\Domain\User\Data\UsersessionData;
use App\Domain\User\Repository\UserfailFinderRepository;
use App\Domain\User\Repository\UserfailRepository;
use App\Domain\User\Repository\UsersecretFinderRepository;
use App\Domain\User\Repository\UsersessionRepository;
use App\Domain\User\Service\UserFinder;
use App\Support\SettingsInterface;
use App\Support\Validation\ValidationException;
use Cake\Chronos\Chronos;
use Selective\ArrayReader\ArrayReader;

/**
 * Service.
 */
final class Loginer
{
    private SettingsInterface $settings;

    private SessionInterface $session;

    private LoginValidator $validator;

    private UserFinder $userFinder;

    private UsersecretFinderRepository $usersecretFinderRepository;

    private UserfailFinderRepository $userfailFinderRepository;

    private UserfailRepository $userfailRepository;

    private UsersessionRepository $usersessionRepository;

    /**
     * The constructor.
     *
     * @param SettingsInterface $settings The settings
     * @param SessionInterface $session The session
     * @param LoginValidator $validator The login validator
     * @param UserFinder $userFinder The user repository
     * @param UsersecretFinderRepository $usersecretFinderRepository The secret repo
     * @param UserfailFinderRepository $userfailFinderRepository The fail finder
     * @param UserfailRepository $userfailRepository The fail repo
     * @param UsersessionRepository $usersessionRepository The session repo
     */
    public function __construct(
        SettingsInterface $settings,
        SessionInterface $session,
        LoginValidator $validator,
        UserFinder $userFinder,
        UsersecretFinderRepository $usersecretFinderRepository,
        UserfailFinderRepository $userfailFinderRepository,
        UserfailRepository $userfailRepository,
        UsersessionRepository $usersessionRepository
    ) {
        $this->settings = $settings;
        $this->session = $session;
        $this->validator = $validator;
        $this->userFinder = $userFinder;
        $this->usersecretFinderRepository = $usersecretFinderRepository;
        $this->userfailFinderRepository = $userfailFinderRepository;
        $this->userfailRepository = $userfailRepository;
        $this->usersessionRepository = $usersessionRepository;
    }

    /**
     * Log in a user.
     *
     * @param array $data The login cedentals data [login, pass]
     *
     * @return void
     */
    public function login(array $data): void
    {
        // Input validation
        [$login, $pass] = $this->validateInput($data);

        // find user
        $userId = $this->findUserId($login);

        // check too many fails
        $this->checkFails($userId);

        // check password
        $this->checkPassword($userId, $pass);

        // delete fails
        $this->deleteFails($userId);

        // set last login
        $this->setLastLogin($userId);

        // start session
        $this->startSession($userId);
    }

    /**
     * Validate input.
     *
     * @param array $data The input data
     *
     * @throws ValidationException
     *
     * @return array
     */
    private function validateInput(array $data): array
    {
        $this->validator->validateLogin($data);

        $reader = new ArrayReader($data);
        $login = $reader->findString('login');
        $pass = $reader->findString('pass');

        return [$login, $pass];
    }

    /**
     * Find user id by login.
     *
     * @param string $email The login
     *
     * @throws ValidationException
     *
     * @return int The userid
     */
    private function findUserId(string $email): int
    {
        $users = $this->userFinder->findUsersByEmail($email);
        if (count($users) !== 1) {
            throw new ValidationException(sprintf('User not found: %s', $email));
        }

        return (int)$users[0]->userData->userid;
    }

    /**
     * Check password.
     *
     * @param int $userId The userid
     * @param string $pass The password
     *
     * @throws ValidationException
     *
     * @return void
     */
    private function checkPassword(int $userId, string $pass): void
    {
        $secrets = $this->usersecretFinderRepository->findUsersecret($userId);
        if (count($secrets) !== 1) {
            $this->addFail($userId);
            throw new ValidationException('Password is expired.');
        }
        if (!$secrets[0]->verifySecret($pass)) {
            $this->addFail($userId);
            throw new ValidationException('Password is wrong.');
        }
    }

    /**
     * Check fail logins.
     *
     * @param int $userId The userId
     *
     * @throws \DomainException
     *
     * @return void
     */
    private function checkFails(int $userId): void
    {
        $settings = $this->settings->get('auth');
        $now = new Chronos();
        $max = $now->toDateTimeString();
        $prev = $now->subMinutes($settings['fail_interval_min']);
        $min = $prev->toDateTimeString();
        $fails = $this->userfailFinderRepository->findUserfail($userId, $min, $max);
        if (count($fails) >= $settings['fail_attempt_max']) {
            throw new ValidationException('Too many attempt.');
        }
    }

    /**
     * Add a fail login.
     *
     * @param int $userId The userid
     *
     * @return void
     */
    private function addFail(int $userId): void
    {
        $userfail = new UserfailData(['fail_userid' => $userId]);
        $this->userfailRepository->insertUserfail($userfail);
    }

    /**
     * Delete all fail login at the given userid.
     *
     * @param int $userId The userId
     *
     * @return void
     */
    private function deleteFails(int $userId): void
    {
        $this->userfailRepository->deleteUserfailById($userId);
    }

    /**
     * Set last login datetime.
     *
     * @param int $userId The userid
     *
     * @return void
     */
    private function setLastLogin(int $userId): void
    {
        $settings = $this->settings->get('auth');
        $now = new Chronos();
        $lastlogin = $now->toDateTimeString();
        $next = $now->addMinutes($settings['ses_timeout_min']);
        $expire = $next->toDateTimeString();
        $usersessionData = new UsersessionData([
            'ses_userid' => $userId,
            'ses_lastlogin' => $lastlogin,
            'ses_lastactive' => $lastlogin,
            'ses_expire' => $expire,
        ]);
        if ($this->usersessionRepository->existsUsersessionId($userId)) {
            $this->usersessionRepository->updateUsersession($usersessionData);
        } else {
            $this->usersessionRepository->insertUsersession($usersessionData);
        }
    }

    /**
     * Start a session.
     *
     * @param int $userId The userid
     *
     * @return void
     */
    private function startSession(int $userId): void
    {
        if ($this->session->isStarted()) {
            $this->session->regenerateId();
        } else {
            $this->session->start();
        }
        $this->session->set('userId', $userId);
        $this->session->save();
    }
}
