<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;
use Spatie\MediaLibrary\MediaCollections\Models\Media;
use Spatie\Image\Enums\Fit;

class App extends Model implements HasMedia
{
    use HasFactory;
    use InteractsWithMedia;

    protected $fillable = [
        "name",
        "type",
        "category",
        "appid",
        "url",
        "sort",
        "status"
    ];

    const WEB = "web";
    const MPP = "mpp";
    static public function typeOptions()
    {
        return [
            self::WEB => __(self::WEB),
            self::MPP => __(self::MPP)
        ];
    }

    const APP = "app";
    const TOOL = "tool";
    static public function categoryOptions()
    {
        return [
            self::APP => __(self::APP),
            self::TOOL => __(self::TOOL)
        ];
    }

    public function registerMediaConversions(Media $media = null): void
    {
        $this
            ->addMediaConversion('thumb')
            ->fit(Fit::Contain, 128, 128)
            ->nonQueued();
    }

    public function attachments()
    {
        return $this->morphMany(Media::class, "model");
    }

    public function info()
    {
        $data = $this->toArray();
        if ($icon = $this->getMedia("icon")->first()) {
            $data['icon'] = $icon->getUrl('thumb');
        }

        return $data;
    }
}
