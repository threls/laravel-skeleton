<?php

declare(strict_types=1);

namespace Domain\Auth\Exceptions;

use Symfony\Component\HttpKernel\Exception\HttpException;

final class InvalidCredentialsException extends HttpException
{
    public function __construct(string $message = 'Invalid credentials.')
    {
        parent::__construct(401, $message);
    }
}
