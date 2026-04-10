<?php

namespace Domain\Auth\Data;

use Domain\Users\Models\User;
use Spatie\LaravelData\Attributes\MapName;
use Spatie\LaravelData\Data;
use Spatie\LaravelData\Mappers\SnakeCaseMapper;

#[MapName(SnakeCaseMapper::class)]
class AuthResponseData extends Data
{
    public function __construct(
        public ?User $user = null,
        public ?string $token = null,
        public bool $otpRequired = false,
        public ?string $message = null,
    ) {}
}
