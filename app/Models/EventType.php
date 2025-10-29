<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;
use Spatie\MediaLibrary\MediaCollections\Models\Media;

class EventType extends Model implements HasMedia
{
    use HasFactory , InteractsWithMedia;
    protected $fillable = ['category_id', 'name_fr', 'name_en', 'slug', 'description', 'is_active'];

    public function category()
    {
        return $this->belongsTo(EventCategory::class, 'category_id');
    }

    public function events()
    {
        return $this->hasMany(Event::class, 'type_id');
    }

    /**
     * Définir la collection 'avatar' pour utiliser le disque public directement
     */
    public function registerMediaCollections(): void
    {
        $this->addMediaCollection('avatar')
            ->useDisk('avatars');  // ← disque défini dans config/filesystems.php
        //->singleFile();        // Garde un seul avatar par admin
    }


    public function registerMediaConversions(?Media $media = null): void
    {
        $this->addMediaConversion('small')
            ->width(368)
            ->nonQueued();

        $this->addMediaConversion('normal')
            ->width(800)
            ->nonQueued();
    }
}
