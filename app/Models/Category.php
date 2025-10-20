<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Category extends Model
{
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
