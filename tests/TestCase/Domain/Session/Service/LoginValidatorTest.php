<?php

namespace App\Test\TestCase\Domain\Session\Service;

use App\Domain\Session\Service\LoginValidator;
use App\Support\Validation\ValidationException;
use Fig\Http\Message\StatusCodeInterface;
use PHPUnit\Framework\TestCase;

/**
 * Test.
 */
class LoginValidatorTest extends TestCase
{
    protected LoginValidator $validator;

    protected array $errors;

    public const MESSAGE = 'Please check your input';

    /**
     * Set up.
     *
     * @return void
     */
    public function setUp(): void
    {
        $this->validator = new LoginValidator();
        $this->errors = [];
    }

    /**
     * Add error.
     *
     * @param string $field The field
     * @param string $rule The rule
     * @param string $message The message
     *
     * @return void
     */
    protected function addError(string $field, string $rule, string $message): void
    {
        $this->errors[$field] = [$rule => $message];
    }

    /**
     * Execute test with exception checking.
     *
     * @param callable $fn The tested function
     *
     * @return void
     */
    protected function executeTest(callable $fn): void
    {
        // $expected = new ValidationException(self::MESSAGE, $this->validationResult, StatusCodeInterface::STATUS_OK);
        try {
            $fn();
            $this->fail('Expected Exception has not been raised.');
        } catch (ValidationException $ex) {
            $this->assertEquals(self::MESSAGE, $ex->getMessage());
            $this->assertEquals(StatusCodeInterface::STATUS_UNPROCESSABLE_ENTITY, $ex->getCode());
            $this->assertEquals($this->errors, $ex->getErrors());
        }
    }

    /**
     * Test.
     *
     * @return void
     */
    public function testMissing(): void
    {
        $this->addError('login', '_required', 'This field is required');
        $this->addError('pass', '_required', 'This field is required');
        $this->executeTest(function () {
            $this->validator->validateLogin([]);
        });
    }

    /**
     * Test.
     *
     * @return void
     */
    public function testEmpty(): void
    {
        $this->addError('login', '_empty', 'Input required.');
        $this->addError('pass', '_empty', 'Input required.');
        $this->executeTest(function () {
            $this->validator->validateLogin(['login' => '', 'pass' => '']);
        });
    }

    /**
     * Test.
     *
     * @return void
     */
    public function testminLength(): void
    {
        $this->addError('pass', 'minLength', 'Password is too sort.');
        $this->executeTest(function () {
            $this->validator->validateLogin(['login' => 'a@b.hu', 'pass' => '1234']);
        });
    }

    /**
     * Test.
     *
     * @return void
     */
    public function testmaxLength(): void
    {
        $this->addError('pass', 'maxLength', 'Password is too long.');
        $this->executeTest(function () {
            $this->validator->validateLogin([
                'login' => 'a@b.hu',
                'pass' => '12345678901234567890123456789012345678901',
            ]);
        });
    }

    /**
     * Test.
     *
     * @return void
     */
    public function testEmail(): void
    {
        $this->addError('login', 'email', 'Email required.');
        $this->executeTest(function () {
            $this->validator->validateLogin(['login' => '1234', 'pass' => '1234567890']);
        });
    }
}
