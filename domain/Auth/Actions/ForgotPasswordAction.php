<?php

namespace Domain\Auth\Actions;

use Domain\Auth\Data\AuthResponseData;
use Domain\Auth\Exceptions\InvalidTokenException;
use Illuminate\Support\Facades\Password;

class ForgotPasswordAction
{
    public function execute(string $email): AuthResponseData
    {
        $status = Password::sendResetLink(['email' => $email]);

        if ($status !== Password::RESET_LINK_SENT) {
            throw new InvalidTokenException(
                $status === Password::INVALID_USER
                    ? 'We can\'t find a user with that email address.'
                    : 'This password reset token is invalid.'
            );
        }

        return AuthResponseData::from([
            'message' => 'We have emailed your password reset link!',
        ]);
    }
}
