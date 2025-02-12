<?php

namespace App\Models\Traits;

use App\Models\User;

/**
 * Trait HasAuthor
 * 
 * This trait provides functionality for associating an author (user) with a model.
 */
trait HasAuthor
{
    /**
     * Get the author of the model.
     * 
     * This method returns the user who is the author of the model.
     * 
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function author()
    {
        // A course belongs to one author (user)
        return $this->belongsTo(User::class, 'user_id');
    }

    /**
     * Get the user associated with the model.
     * 
     * This method is an alias for the author method.
     * 
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function user()
    {
        // Alias for author
        return $this->author();
    }
}
