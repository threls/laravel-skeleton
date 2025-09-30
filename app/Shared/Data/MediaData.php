<?php

namespace App\Shared\Data;

use App\Shared\Enums\MediaDiskEnum;
use Illuminate\Support\Carbon;
use Spatie\LaravelData\Attributes\MapName;
use Spatie\LaravelData\Attributes\Validation\Prohibited;
use Spatie\LaravelData\Data;
use Spatie\LaravelData\Mappers\SnakeCaseMapper;
use Spatie\LaravelData\Optional;
use Spatie\MediaLibrary\MediaCollections\Models\Media;

#[MapName(SnakeCaseMapper::class)]
class MediaData extends Data
{
    public function __construct(
        #[Prohibited]
        public int $id,
        #[Prohibited]
        public string $name,
        #[Prohibited]
        public string $fileName,
        #[Prohibited]
        public Optional|string $url,
        #[Prohibited]
        public Optional|string $extension,
        #[Prohibited]
        public Optional|array $conversions,
        #[Prohibited]
        public Optional|array $customProperties,
        #[Prohibited]
        public int $size,
    ) {}

    public static function fromModel(Media $media, ?bool $temporaryUrl = true): self
    {
        return new self(
            id: $media->id,
            name: $media->name,
            fileName: $media->file_name,
            url: self::getUrl($media, '', $temporaryUrl),
            extension: $media->extension,
            conversions: self::getConversions($media, $temporaryUrl),
            customProperties: $media->custom_properties ?? [],
            size: $media->size,
        );
    }

    protected static function getConversions(Media $media, bool $temporaryUrl = false): array
    {
        $conversions = [];

        if ($media->getGeneratedConversions()->isNotEmpty()) {
            collect($media->getMediaConversionNames())->each(function ($conversion) use (&$conversions, $media, $temporaryUrl) {
                $conversions[$conversion] = self::getUrl($media, $conversion, $temporaryUrl);
            });
        }

        return $conversions;
    }

    protected static function getUrl(Media $media, string $conversion = '', ?bool $temporaryUrl = true): string
    {
        if ($temporaryUrl) {
            return MediaDiskEnum::from($media->disk)->hasTemporaryUrl() ? $media->getTemporaryUrl(Carbon::now()->addMinutes(5), $conversion) : $media->getUrl($conversion);
        }

        return MediaDiskEnum::from($media->disk)->hasTemporaryUrl() ? $media->getTemporaryUrl(Carbon::now()->addMinutes(120), $conversion) : $media->getUrl($conversion);

    }
}
