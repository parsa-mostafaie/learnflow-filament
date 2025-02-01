<?php

namespace App\Models\Traits;

trait HasRoles
{
    const MAX_ROLE = 1, MAX_SECURE_ROLE = 1, MIN_ROLE = 0;

    const roles = [
        0,
        1,
        2,
        'user' => 0,
        'admin' => 1,
        'developer' => 2
    ];

    public function getRoleNameAttribute()
    {
        return $this->roleNames[$this->role] ?? 'unknown';
    }

    public function setRoleByText($roleName)
    {
        $roleId = array_search($roleName, $this->roleNames);

        if ($roleId !== false) {
            $this->role = $roleId;
            return true;
        }

        return false;
    }

    public function isRole($role)
    {
        return $this->role >= static::roles[$role];
    }

    public function isAdmin()
    {
        return $this->isRole('admin');
    }

    public function isUser()
    {
        return $this->isRole('user');
    }

    public function validateRole($role, $secure = true)
    {
        return min(max(static::roles[$role], static::MIN_ROLE), $secure ? static::MAX_SECURE_ROLE : static::MAX_ROLE);
    }
}
