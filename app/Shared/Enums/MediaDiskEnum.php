<?php

namespace App\Shared\Enums;

enum MediaDiskEnum: string
{
    case LOCAL = 'local';

    case PUBLIC = 'public';

    case DO_PRIVATE = 'do_private';

    case DO_PUBLIC = 'do_public';

    case AWS_S3 = 's3';

    public function hasTemporaryUrl(): bool
    {
        return match ($this) {
            self::DO_PRIVATE => true ,
            self::LOCAL, self::DO_PUBLIC, self::PUBLIC, self::AWS_S3 => false
        };
    }
}
