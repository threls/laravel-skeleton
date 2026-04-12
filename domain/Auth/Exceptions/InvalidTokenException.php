<?php

declare(strict_types=1);

namespace Domain\Auth\Exceptions;

use Symfony\Component\HttpKernel\Exception\HttpException;

final class InvalidTokenException extends HttpException
{
    public function __construct(string $message = 'This password reset token is invalid or the email is incorrect.')
    {
        parent::__construct(400, $message);
    }
}
