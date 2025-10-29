<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;
use Spatie\MediaLibrary\MediaCollections\Models\Media;

class EventCategory extends Model implements HasMedia
{
    use HasFactory, InteractsWithMedia;
    protected $fillable = ['name_fr', 'name_en', 'slug', 'description', 'is_active'];

    public function types()
    {
        return $this->hasMany(EventType::class, 'category_id');
    }

    public function events()
    {
        return $this->hasMany(Event::class, 'category_id');
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

