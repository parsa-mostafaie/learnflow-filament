<?php

use Illuminate\Support\Facades\Auth;

/**
 * Check if the authenticated user has a specific role.
 * 
 * @param string $role
 * @return bool
 */
function isRole($role)
{
    /** @var \App\Models\User */
    $u = Auth::user();

    // Check if the user is authenticated and has the specified role
    return $u && $u->isRole($role);
}
