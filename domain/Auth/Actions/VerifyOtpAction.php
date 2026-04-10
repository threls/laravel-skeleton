<?php

namespace Domain\Auth\Actions;

use Domain\Auth\Data\AuthResponseData;
use Domain\Auth\Data\OtpData;
use Domain\Auth\Exceptions\InvalidOtpException;
use Domain\Users\Models\User;
use Illuminate\Support\Facades\Cache;

class VerifyOtpAction
{
    public function execute(User $user, OtpData $data): AuthResponseData
    {
        $this->verify($user, $data);

        $user->markEmailAsVerified();

        Cache::forget('otp_' . $user->id);

        return AuthResponseData::from([
            'user' => $user,
            'token' => $user->createToken('auth_token')->plainTextToken,
            'message' => 'Email verified successfully via OTP.',
        ]);
    }

    protected function verify(User $user, OtpData $data): void
    {
        $cachedOtp = Cache::get('otp_' . $user->id);

        if (! $cachedOtp || $cachedOtp !== $data->otp) {
            throw new InvalidOtpException;
        }
    }
}
