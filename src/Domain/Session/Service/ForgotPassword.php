<?php

declare(strict_types = 1);

namespace App\Domain\Session\Service;

use App\Domain\User\Data\UserData;
use App\Domain\User\Data\UserpassresetData;
use App\Domain\User\Repository\UserpassresetRepository;
use App\Domain\User\Service\UserFinder;
use App\Support\Mailer;
use App\Support\TemplateEngine;
use App\Support\UrlHelper;
use App\Support\Validation\ValidationException;
use Cake\Chronos\Chronos;

/**
 * Service.
 */
final class ForgotPassword
{
    private UserFinder $userFinder;

    private UserpassresetRepository $userpassresetRepository;

    private Mailer $mailer;

    private TemplateEngine $templateEngine;

    public const MESSAGE = 'Please check your input';

    /**
     * The constructor.
     *
     * @param UserFinder $userFinder The user repo
     * @param UserpassresetRepository $usersessionRepository The session repo
     * @param Mailer $mailer The maile sender
     * @param TemplateEngine $templateEngine The template engine
     */
    public function __construct(
        UserFinder $userFinder,
        UserpassresetRepository $usersessionRepository,
        Mailer $mailer,
        TemplateEngine $templateEngine
    ) {
        $this->userFinder = $userFinder;
        $this->userpassresetRepository = $usersessionRepository;
        $this->mailer = $mailer;
        $this->templateEngine = $templateEngine;
    }

    /**
     * Forgot password based form data.
     *
     * @param array $data The data
     *
     * @throws ValidationException
     *
     * @return void
     */
    public function forgot(array $data): void
    {
        if (!isset($data['email'])) {
            $errors = ['email' => ['_require' => 'This field is required']];
            throw new ValidationException(self::MESSAGE, $errors);
        }

        $this->forgotPassword($data['email']);
    }

    /**
     * Forgot password.
     *
     * @param string $email The email to send resetcode
     *
     * @throws ValidationException
     *
     * @return void
     */
    public function forgotPassword(string $email): void
    {
        if (trim($email) == '') {
            $errors = ['email' => ['email' => 'Missing email.']];
            throw new ValidationException(self::MESSAGE, $errors);
        }

        $users = $this->userFinder->findUsersByEmail($email);
        if (count($users) !== 1) {
            $errors = ['email' => ['email' => 'Unknown email.']];
            throw new ValidationException(self::MESSAGE, $errors);
        }

        $userId = (int)$users[0]->userData->userid;
        $userpassresetData = $this->loadResetCode($userId) ?? $this->generateResetCode($userId);
        $this->sendMail($users[0]->userData, $userpassresetData);
    }

    /**
     * Load userpassdata if it exists, delete it if it is expired.
     *
     * @param int $userId The userid
     *
     * @return UserpassresetData|null
     */
    private function loadResetCode(int $userId): ?UserpassresetData
    {
        if ($this->userpassresetRepository->existsUserpassresetId($userId)) {
            $userpassresetData = $this->userpassresetRepository->getUserpassresetById($userId);
            if ($userpassresetData->isExpired()) {
                $this->userpassresetRepository->deleteUserpassresetById($userId);
            } else {
                return $userpassresetData;
            }
        }

        return null;
    }

    /**
     * Generate and store new reset code.
     *
     * @param int $userId The userid
     *
     * @return UserpassresetData
     */
    private function generateResetCode(int $userId): UserpassresetData
    {
        $reset_code = uuid_create();
        $now = new Chronos();
        $next = $now->addHours(2 * 24);
        $expire = $next->toDateTimeString();
        $userpassresetData = new UserpassresetData([
            'userid' => $userId,
            'reset_code' => $reset_code,
            'expire' => $expire,
        ]);
        $this->userpassresetRepository->insertUserpassreset($userpassresetData);

        return $userpassresetData;
    }

    /**
     * Send mail from template.
     *
     * @param UserData $userData The userdata
     * @param UserpassresetData $userpassResetData The passreset data
     *
     * @return bool
     */
    private function sendmail(UserData $userData, UserpassresetData $userpassResetData): bool
    {
        $subject = 'Reset password';
        $link = UrlHelper::urlBase('?reset_code=' . $userpassResetData->reset_code);
        $message = $this->getPasswordResetMessage($subject, $userData, $link);
        $text = "Your password reset link: $link";
        $result = $this->mailer->sendMail([$userData->email], $subject, $text, $message);

        return false == $result;
    }

    /**
     * Make message from template.
     *
     * @param string $title Page title
     * @param UserData $userData The user datat
     * @param string $link The reset link
     *
     * @return string
     */
    private function getPasswordResetMessage(string $title, UserData $userData, string $link): string
    {
        $message = $this->templateEngine->renderValueMap('system/pwdreset', [
            'title' => $title,
            'link' => $link,
            'fullname' => $userData->name,
            'email' => $userData->email,
        ]);

        return $message;
    }
}
