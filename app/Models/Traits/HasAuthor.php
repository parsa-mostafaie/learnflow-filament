<?php

namespace App\Models\Traits;

use App\Models\User;
trait HasAuthor {
    public function author()
    {
        return $this->belongsTo(User::class, 'user_id'); // A course belongs to one author (user)
    }

    public function user()
    {
        return $this->author(); // alias for author
    }
}
