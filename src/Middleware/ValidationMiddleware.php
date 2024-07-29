<?php

declare(strict_types = 1);

namespace App\Middleware;

use App\Support\Validation\ValidationException;
use Psr\Http\Message\ResponseFactoryInterface;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\MiddlewareInterface;
use Psr\Http\Server\RequestHandlerInterface;

/**
 * Validation middelware catches the ValidationException.
 */
final class ValidationMiddleware implements MiddlewareInterface
{
    private ResponseFactoryInterface $responseFactory;

    /**
     * Constructor.
     *
     * @param ResponseFactoryInterface $responseFactory The response
     */
    public function __construct(ResponseFactoryInterface $responseFactory)
    {
        $this->responseFactory = $responseFactory;
    }

    /**
     * Process request.
     *
     * @param ServerRequestInterface $request The request
     * @param RequestHandlerInterface $handler The next handler
     *
     * @return ResponseInterface
     */
    public function process(
        ServerRequestInterface $request,
        RequestHandlerInterface $handler
    ): ResponseInterface {
        try {
            return $handler->handle($request);
        } catch (ValidationException $exception) {
            $response = $this->responseFactory->createResponse();
            $data = $this->transform($exception);
            $json = (string)json_encode($data, JSON_UNESCAPED_SLASHES | JSON_PARTIAL_OUTPUT_ON_ERROR);
            $response->getBody()->write($json);

            return $response
                ->withStatus(200)
                ->withHeader('Content-Type', 'application/json');
        }
    }

    /**
     * Transform the validation exception to response.
     *
     * @param ValidationException $exception The exceptio
     *
     * @return array
     */
    private function transform(ValidationException $exception): array
    {
        $error = [
            'error' => true,
            'message' => $exception->getMessage(),
        ];

        $errors = $exception->getErrors();
        if ($errors) {
            $error['details'] = $this->addErrors([], $errors);
        }

        return $error;
    }

    /**
     * Add formatted error.
     *
     * @param array $result The result
     * @param array $errors The errors
     * @param string $path The field path
     *
     * @return array
     */
    private function addErrors(array $result, array $errors, string $path = ''): array
    {
        foreach ($errors as $field => $error) {
            $oldPath = $path;
            $path .= ($path === '' ? '' : '.') . $field;
            $result = $this->addSubErrors($result, $error, $path);
            $path = $oldPath;
        }

        return $result;
    }

    /**
     * Add sub error for a path.
     *
     * @param array $result The result
     * @param array $error The errors
     * @param string $path The current path
     *
     * @return array
     */
    private function addSubErrors(array $result, array $error, string $path = ''): array
    {
        foreach ($error as $field2 => $errorMessage) {
            if (is_array($errorMessage)) {
                $result = $this->addErrors($result, [$field2 => $errorMessage], $path);
            } else {
                $result[] = [
                    'message' => $errorMessage,
                    'field' => $path,
                ];
            }
        }

        return $result;
    }
}
