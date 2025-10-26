<?php

namespace App\Traits;

trait HasImageUrl
{
    /**
     * Get the full URL for the image
     */
    public function getImageUrlAttribute()
    {
        if ($this->image && !str_starts_with($this->image, 'http')) {
            return asset('storage/' . $this->image);
        }

        return $this->image;
    }

    /**
     * Get the full URLs for gallery images
     */
    public function getGalleryUrlsAttribute()
    {
        if (!$this->gallery) {
            return [];
        }

        return collect($this->gallery)->map(function ($image) {
            if (!str_starts_with($image, 'http')) {
                return asset('storage/' . $image);
            }
            return $image;
        })->toArray();
    }
}
