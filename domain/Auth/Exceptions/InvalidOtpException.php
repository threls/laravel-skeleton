<?php

namespace Domain\Auth\Exceptions;

use Symfony\Component\HttpKernel\Exception\HttpException;

class InvalidOtpException extends HttpException
{
    public function __construct(string $message = 'The provided OTP is invalid or expired.')
    {
        parent::__construct(400, $message);
    }
}
