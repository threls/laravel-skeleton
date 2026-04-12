<?php

declare(strict_types=1);

namespace Domain\Auth\Data;

use Spatie\LaravelData\Attributes\MapName;
use Spatie\LaravelData\Data;
use Spatie\LaravelData\Mappers\SnakeCaseMapper;

#[MapName(SnakeCaseMapper::class)]
final class LoginData extends Data
{
    public function __construct(
        public string $email,
        public string $password,
    ) {}
}
