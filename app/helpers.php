<?php

use Illuminate\Support\Facades\Auth;

function isRole($role)
{
    /** @var \App\Models\User */
    $u = Auth::user();

    return $u && $u->isRole($role);
}
