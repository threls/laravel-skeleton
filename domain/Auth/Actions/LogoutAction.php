<?php

namespace Domain\Auth\Actions;

use Domain\Users\Models\User;

class LogoutAction
{
    public function execute(User $user): void
    {
        $user->currentAccessToken()->delete();
    }
}
