<?php

declare(strict_types=1);

namespace Domain\Auth\Actions;

use Domain\Auth\Data\AuthResponseData;
use Domain\Auth\Data\LoginData;
use Domain\Auth\Exceptions\InvalidCredentialsException;
use Domain\Auth\Notifications\OtpNotification;
use Domain\Users\Models\User;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Hash;
use Random\RandomException;

final class LoginAction
{
    public function execute(LoginData $data): AuthResponseData
    {
        $user = $this->authenticate($data);

        if (config('auth_features.email_otp')) {
            return $this->sendOtp($user);
        }

        return AuthResponseData::from([
            'user' => $user,
            'token' => $user->createToken('auth_token')->plainTextToken,
            'message' => 'Logged in successfully.',
        ]);
    }

    private function authenticate(LoginData $data): User
    {
        $user = User::where('email', $data->email)->first();

        if (! $user || ! Hash::check($data->password, $user->password)) {
            throw new InvalidCredentialsException;
        }

        return $user;
    }

    /**
     * @throws RandomException
     */
    private function sendOtp(User $user): AuthResponseData
    {
        $otp = (string) random_int(100000, 999999);
        $expiry = config('auth_features.otp_expiry', 10);

        Cache::put('otp_'.$user->id, $otp, now()->addMinutes($expiry));

        $user->notify(new OtpNotification($otp, $expiry));

        return AuthResponseData::from([
            'user' => $user,
            'otp_required' => true,
            'message' => 'OTP sent to your email.',
        ]);
    }
}
