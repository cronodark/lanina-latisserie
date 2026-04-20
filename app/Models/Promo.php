<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;

class Promo extends Model implements HasMedia
{
    use HasFactory, InteractsWithMedia;

    public const MEDIA_COLLECTION = 'promo_images';

    protected $fillable = [
        'name',
        'description',
        'price',
        'date_until',
        'status',
        'actual_price',
    ];

    protected $appends = [
        'percentage',
        'image'
    ];

    public function getPercentageAttribute()
    {
        if ($this->actual_price > 0) {
            return round((($this->actual_price - $this->price) / $this->actual_price) * 100);
        }
        return 0;
    }

    public function getImageAttribute()
    {
        $media = $this->getFirstMedia(self::MEDIA_COLLECTION);
        return $media ? $media->getUrl() : null;
    }

    protected $casts = [
        'price' => 'integer',
        'actual_price' => 'integer',
        'date_until' => 'date',
    ];

    public function registerMediaCollections(): void
    {
        $this->addMediaCollection(self::MEDIA_COLLECTION)->singleFile();
    }

    public function preOrdersDetail()
    {
        return $this->hasMany(PreOrderDetail::class);
    }
}
