<?php

namespace App\Models\Traits;

use Illuminate\Support\Facades\Storage;

/**
 * Trait HasImage
 * 
 * This trait provides functionality for managing images associated with a model.
 */
trait HasImage
{
    /**
     * The "booted" method of the model.
     * 
     * This method is called when the model is booted. It sets up a callback to remove the image when the model is deleted.
     *
     * @return void
     */
    protected static function bootHasImage()
    {
        static::deleted(function ($model) {
            $model->removeImage();
        });
    }

    /**
     * Get the URL of the image.
     * 
     * This method returns the URL of the image. If the image URL is not valid, it returns a storage URL. 
     * If no image is found, it returns an alternative placeholder image.
     *
     * @return string|null
     */
    public function getImageUrlAttribute(): string|null
    {
        if (!is_null($this->thumbnail)) {
            if (!parse_url($this->thumbnail, PHP_URL_HOST)) {
                return Storage::url($this->thumbnail);
            }
            return $this->thumbnail;
        }
        return $this->getAlternativeImage();
    }

    /**
     * Remove the previous image.
     * 
     * This method deletes the previous image associated with the model from the storage.
     *
     * @return bool
     */
    public function removePreviousImage(): bool
    {
        if (!($original = $this->getOriginal('thumbnail'))) {
            return true;
        }
        return Storage::disk('public')->delete($original);
    }

    /**
     * Remove the current image.
     * 
     * This method deletes the current image associated with the model from the storage.
     *
     * @return bool
     */
    public function removeImage(): bool
    {
        if (!($path = $this->thumbnail)) {
            return true;
        }
        return Storage::disk('public')->delete($path);
    }

    /**
     * Get the size of the alternative image.
     * 
     * This method returns the size of the alternative image. It defaults to '300x200' if not specified.
     *
     * @return string
     */
    public function getAlternativeImageSize(): string
    {
        return property_exists($this, 'alt_image_size') ? $this->alt_image_size : '300x200';
    }

    /**
     * Get the alternative image URL.
     * 
     * This method returns the URL of an alternative placeholder image if no image is found.
     *
     * @return string|null
     */
    public function getAlternativeImage(): string|null
    {
        return "https://placehold.co/{$this->getAlternativeImageSize()}?text=No+Image+Found";
    }
}
