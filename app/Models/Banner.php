<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;
use Spatie\MediaLibrary\MediaCollections\Models\Media;
use Spatie\Image\Enums\Fit;

class Banner extends Model implements HasMedia
{
    use HasFactory;
    use InteractsWithMedia;

    protected $fillable = [
        // "name",
        "type",
        "category",
        "ad_position",
        "height",
        "url",
        "sort",
        "status"
    ];

    const INNER_URL = "inner_url";
    const OUTER_URL = "outer_url";
    const MPP = "mpp";
    static public function typeOptions()
    {
        return [
            self::INNER_URL => ___(self::INNER_URL),
            self::OUTER_URL => ___(self::OUTER_URL),
            self::MPP => ___(self::MPP)
        ];
    }

    const BANNER = "banner";
    const AD = "ad";
    static public function categoryOptions()
    {
        return [
            self::BANNER => __(self::BANNER),
            self::AD => __(self::AD)
        ];
    }

    public function registerMediaConversions(Media $media = null): void
    {
        $this
            ->addMediaConversion('preview')
            ->fit(Fit::Contain, 480, 480)
            ->nonQueued();
    }

    public function attachments()
    {
        return $this->morphMany(Media::class, "model");
    }

    public function info()
    {
        $data = $this->toArray();
        if ($icon = $this->getMedia("image")->first()) {
            $data['image'] = $icon->getUrl('preview');
        }

        return $data;
    }
}
