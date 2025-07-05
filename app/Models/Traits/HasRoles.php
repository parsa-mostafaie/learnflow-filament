<?php

namespace App\Models\Traits;

use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Support\Facades\Cache;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Traits\HasRoles as SpatieHasRoles;

/**
 * Trait HasRoles
 * 
 * This trait provides functionality for managing and validating user roles.
 */
trait HasRoles
{
    use SpatieHasRoles;

    public const ROLES = [
        'user',
        'instructor',
        'manager',
        'admin',
        'developer'
    ];

    protected static function bootHasRoles()
    {
        static::saving(function ($model) {
            if ($model->getRoleNames()->count() == 0) {
                $model->setRole('user');
            }
        });
    }

    /**
     * Get the role name attribute.
     * 
     * This method returns the name of the role based on the role ID.
     * 
     * @return string
     */
    protected function roleName(): Attribute
    {
        return Attribute::make(
            get: fn() => $this->getRoleNames()[0] ?? 'user'
        )->withoutObjectCaching();
    }

    /**
     * Set the role by its text representation.
     * 
     * This method sets the role based on the role name provided.
     * 
     * @param string $roleName
     */
    public function setRole($roleName)
    {
        if (!$this->getRoleNamesCollection()->contains($roleName = $this->sanitizeRole($roleName))) {
            return false;
        }

        return $this->syncRoles([$roleName]);
    }

    public static function getRoleNamesCollection()
    {
        return Cache::remember('role-names-collection', now()->addMinutes(60), function () {
            return collect(Role::orderBy('id')->pluck('name'));
        });
    }

    public static function getNextRole($roleName, $null_if_not_found = false)
    {
        return static::getRoleNamesCollection()->after(static::sanitizeRole($roleName)) ?? ($null_if_not_found ? null : $roleName);
    }

    public static function getPreviousRole($roleName, $null_if_not_found = false)
    {
        return static::getRoleNamesCollection()->before(static::sanitizeRole($roleName)) ?? ($null_if_not_found ? null : $roleName);
    }

    public static function sanitizeRole($roleName)
    {
        $roleName = strtolower($roleName);

        return static::getRoleNamesCollection()->contains($roleName) ? $roleName : null;
    }

    protected function nextRoleName(): Attribute
    {
        return Attribute::make(
            get: fn() => static::getNextRole($this->role_name)
        )->withoutObjectCaching();
    }

    protected function previousRoleName(): Attribute
    {
        return Attribute::make(
            get: fn() => static::getPreviousRole($this->role_name)
        )->withoutObjectCaching();
    }

    public static function getRoleNumber($roleName)
    {
        $roleName = static::sanitizeRole($roleName);

        return $roleName ? static::getRoleNamesCollection()->search($roleName) : null;
    }

    public static function getAllRolesUntil($roleName)
    {
        $next = static::getNextRole($roleName, true);

        return static::getRoleNamesCollection()->takeWhile(function ($role) use ($next) {
            return $role != $next;
        });
    }

    public static function getAllRolesAfter($roleName)
    {
        return static::getRoleNamesCollection()->skipUntil(function ($role) use ($roleName) {
            return $role == $roleName; // Start including from $roleName
        });
    }

    public static function diffRoles($first, $second)
    {
        return static::getRoleNumber($first) - static::getRoleNumber($second);
    }
}
