<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Carbon;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;

class Promo extends Model implements HasMedia
{
    use HasFactory, InteractsWithMedia;

    public const MEDIA_COLLECTION = 'promo_images';
    public const STATUS_ACTIVE = 'active';
    public const STATUS_SCHEDULED = 'scheduled';
    public const STATUS_INACTIVE = 'inactive';

    protected $fillable = [
        'name',
        'description',
        'price',
        'date_until',
        'date_start',
        'stok',
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

        $products = $this->relationLoaded('promoDetails')
            ? $this->promoDetails
            : $this->promoDetails()->with('product')->get();

        if ($products->count() <= 1) {
            $firstProduct = $products->first()?->product;

            return $firstProduct?->image ?? $media?->getUrl();
        }

        if ($media) {
            return $media->getUrl();
        }

        $firstProduct = $products->first()?->product;

        return $firstProduct?->image;
    }

    protected $casts = [
        'price' => 'integer',
        'actual_price' => 'integer',
        'date_start' => 'date',
        'date_until' => 'date',
    ];

    public static function resolveStatus(?string $dateStart, string $dateUntil, ?Carbon $referenceDate = null): string
    {
        $today = ($referenceDate ?? now())->startOfDay();
        $startDate = $dateStart ? Carbon::parse($dateStart)->startOfDay() : null;
        $endDate = Carbon::parse($dateUntil)->startOfDay();

        if ($endDate->lt($today)) {
            return self::STATUS_INACTIVE;
        }

        if ($startDate && $startDate->gt($today)) {
            return self::STATUS_SCHEDULED;
        }

        return self::STATUS_ACTIVE;
    }

    public static function synchronizeStatuses(?Carbon $referenceDate = null): void
    {
        $today = ($referenceDate ?? now())->toDateString();

        static::query()
            ->where('date_until', '<', $today)
            ->update(['status' => self::STATUS_INACTIVE]);

        static::query()
            ->where(function ($query) use ($today) {
                $query->whereNull('date_start')
                    ->orWhere('date_start', '<=', $today);
            })
            ->where('date_until', '>=', $today)
            ->update(['status' => self::STATUS_ACTIVE]);

        static::query()
            ->where('date_start', '>', $today)
            ->where('date_until', '>=', $today)
            ->update(['status' => self::STATUS_SCHEDULED]);
    }

    public function registerMediaCollections(): void
    {
        $this->addMediaCollection(self::MEDIA_COLLECTION)->singleFile();
    }

    public function preOrdersDetail()
    {
        return $this->hasMany(PreOrderDetail::class);
    }

    public function promoDetails()
    {
        return $this->hasMany(PromoDetail::class);
    }
}
