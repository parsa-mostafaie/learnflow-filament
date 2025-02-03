<?php

namespace App\Models\Traits;

use Illuminate\Support\Facades\Storage;

trait HasImage
{
    /**
     * The "booted" method of the model.
     *
     * @return void
     */
    protected static function bootHasImage()
    {
        static::deleted(function ($model) {
            $model->removeImage();
        });
    }

    public function getImageUrlAttribute(): string|null
    {
        if (!is_null($this->thumbnail)) {
            if (!parse_url($this->thumbnail, PHP_URL_HOST))
                return Storage::url($this->thumbnail);

            return $this->thumbnail;
        }

        return $this->getAlternativeImage();
    }

    public function removePreviousImage(): bool
    {
        if (!($original = $this->getOriginal('thumbnail'))) {
            return true;
        }

        return Storage::disk('public')->delete($original);
    }

    public function removeImage(): bool
    {
        if (!($path = $this->thumbnail)) {
            return true;
        }

        return Storage::disk('public')->delete($path);
    }

    public function getAlternativeImageSize(): string
    {
        return property_exists($this, 'alt_image_size') ? $this->alt_image_size : '300x200';
    }

    public function getAlternativeImage(): string|null
    {
        return "https://placehold.co/{$this->getAlternativeImageSize()}?text=No+Image+Found";
    }
}
