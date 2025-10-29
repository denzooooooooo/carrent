<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Spatie\Image\Enums\CropPosition;
use Spatie\MediaLibrary\Conversions\Manipulations;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;
use Spatie\MediaLibrary\MediaCollections\Models\Media;

class Carousel extends Model implements HasMedia
{
    use InteractsWithMedia;

    protected $fillable = [
        'title_fr',
        'title_en',
        'subtitle_fr',
        'subtitle_en',
        // 'image' et 'mobile_image' sont gérés par Spatie, pas besoin dans fillable
        'video_url',
        'link_url',
        'button_text_fr',
        'button_text_en',
        'order_position',
        'is_active',
        'start_date',
        'end_date',
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'start_date' => 'datetime',
        'end_date' => 'datetime',
        'order_position' => 'integer',
    ];

    /**
     * Définir les collections de médias pour le carrousel.
     * Note: Les champs 'image' et 'mobile_image' de la migration ne sont plus utilisés directement,
     * mais les noms des collections MediaLibrary les remplacent.
     */
    public function registerMediaCollections(): void
    {
        // Image de bureau (Desktop)
        $this->addMediaCollection('image_desktop')
            ->singleFile()
            ->useDisk('public');

        // Image mobile (Mobile)
        $this->addMediaCollection('image_mobile')
            ->singleFile()
            ->useDisk('public');
    }

    /**
     * Définir les conversions de médias (redimensionnement).
     */
    public function registerMediaConversions(?Media $media = null): void
    {
        // Conversion générale pour l'aperçu dans l'administration
        $this->addMediaConversion('thumb')
            ->width(300)
            ->height(200)
            ->crop(300, 200, CropPosition::Center)
            ->nonQueued();
    }
}
