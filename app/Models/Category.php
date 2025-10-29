<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;
use Spatie\MediaLibrary\MediaCollections\Models\Media;

class Category extends Model implements HasMedia
{
    use HasFactory, InteractsWithMedia;
    protected $fillable = [
        'name_fr',
        'name_en',
        'slug',
        'description_fr',
        'description_en',
        'icon',
        'image',
        'parent_id',
        'order_position',
        'is_active',
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'order_position' => 'integer',
    ];

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

    // Relation parent
    public function parent()
    {
        return $this->belongsTo(Category::class, 'parent_id');
    }

    // Relation enfants
    public function children()
    {
        return $this->hasMany(Category::class, 'parent_id');
    }

    // Relation événements
    public function events()
    {
        return $this->hasMany(Event::class);
    }

    // Relation packages
    public function packages()
    {
        return $this->hasMany(TourPackage::class);
    }
}
