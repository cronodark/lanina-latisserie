<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;

class Product extends Model implements HasMedia
{
    use InteractsWithMedia, HasFactory;

    protected $fillable = [
        'name',
        'description',
        'price',
        'expired_day',
        'production_estimate'
    ];

    protected $appends = [
        'image'
    ];

    public function getImageAttribute()
    {
        $media = $this->getFirstMedia(self::MEDIA_COLLECTION);
        return $media ? $media->getUrl() : null;
    }

    public const MEDIA_COLLECTION = 'product-image';

    public function registerMediaCollections(): void
    {
        $this->addMediaCollection(self::MEDIA_COLLECTION)->singleFile();
    }
}
