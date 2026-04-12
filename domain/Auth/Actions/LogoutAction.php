<?php

declare(strict_types=1);

namespace Domain\Auth\Actions;

use Domain\Users\Models\User;

final class LogoutAction
{
    public function execute(User $user): void
    {
        $user->currentAccessToken()->delete();
    }
}
