<?php

declare(strict_types=1);

namespace Domain\Auth\Actions;

use Domain\Auth\Data\AuthResponseData;
use Domain\Auth\Data\RegisterData;
use Domain\Auth\Notifications\OtpNotification;
use Domain\Users\Models\User;
use Illuminate\Auth\Events\Registered;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Hash;

final class RegisterAction
{
    public function execute(RegisterData $data): AuthResponseData
    {
        $user = $this->createUser($data);

        $this->handlePostRegistration($user);

        if (config('auth_features.email_otp')) {
            return AuthResponseData::from([
                'user' => $user,
                'otp_required' => true,
                'message' => 'User registered. OTP sent to your email.',
            ]);
        }

        return AuthResponseData::from([
            'user' => $user,
            'token' => $user->createToken('auth_token')->plainTextToken,
            'message' => 'User registered successfully.',
        ]);
    }

    private function createUser(RegisterData $data): User
    {
        return User::create([
            'name' => $data->name,
            'email' => $data->email,
            'password' => Hash::make($data->password),
        ]);
    }

    private function handlePostRegistration(User $user): void
    {
        if (config('auth_features.email_verification')) {
            event(new Registered($user));
        }

        if (config('auth_features.email_otp')) {
            $this->sendOtp($user);
        }
    }

    private function sendOtp(User $user): void
    {
        $otp = (string) rand(100000, 999999);
        $expiry = config('auth_features.otp_expiry', 10);

        Cache::put('otp_'.$user->id, $otp, now()->addMinutes($expiry));

        $user->notify(new OtpNotification($otp, $expiry));
    }
}
