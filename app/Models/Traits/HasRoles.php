<?php

namespace App\Models\Traits;

/**
 * Trait HasRoles
 * 
 * This trait provides functionality for managing and validating user roles.
 */
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

    /**
     * Get the role name attribute.
     * 
     * This method returns the name of the role based on the role ID.
     * 
     * @return string
     */
    public function getRoleNameAttribute()
    {
        return $this->roleNames[$this->role] ?? 'unknown';
    }

    /**
     * Set the role by its text representation.
     * 
     * This method sets the role based on the role name provided.
     * 
     * @param string $roleName
     * @return bool
     */
    public function setRoleByText($roleName)
    {
        $roleId = array_search($roleName, $this->roleNames);

        if ($roleId !== false) {
            $this->role = $roleId;
            return true;
        }

        return false;
    }

    /**
     * Check if the user has the specified role.
     * 
     * This method checks if the user's role is greater than or equal to the specified role.
     * 
     * @param string|int $role
     * @return bool
     */
    public function isRole($role)
    {
        return $this->role >= static::roles[$role];
    }

    /**
     * Check if the user is an admin.
     * 
     * @return bool
     */
    public function isAdmin()
    {
        return $this->isRole('admin');
    }

    /**
     * Check if the user is a regular user.
     * 
     * @return bool
     */
    public function isUser()
    {
        return $this->isRole('user');
    }

    /**
     * Validate the role based on its security level.
     * 
     * This method validates the role and ensures it is within the allowed range.
     * 
     * @param string|int $role
     * @param bool $secure
     * @return int
     */
    public static function validateRole($role, $secure = true)
    {
        return min(max(static::roles[$role] ?? $role, static::MIN_ROLE), $secure ? static::MAX_SECURE_ROLE : static::MAX_ROLE);
    }
}
