<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;

class User extends Authenticatable
{
    use Notifiable, SoftDeletes;

    const ROLE_MANAGER = 'manager';
    const ROLE_WAITER = 'waiter';

    /**
     * The accessors to append to the model's array form.
     *
     * @var array
     */
    protected $appends = [
        'full_name',
        'role_name'
    ];

    /**
     * The attributes that should be casted to native types.
     *
     * @var array
     */
    protected $casts = [
        'is_active' => 'boolean'
    ];

    /**
     * The attributes that should be mutated to dates.
     *
     * @var array
     */
    protected $dates = [
        'deleted_at'
    ];

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'email',
        'password',
        'first_name',
        'last_name',
        'is_active'
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password',
        'remember_token',
        'confirmation_token',
        'updated_at',
        'deleted_at'
    ];

    /**
     * Get a full name.
     *
     * @return string
     */
    public function getFullNameAttribute()
    {
        return $this->first_name . ' ' . $this->last_name;
    }

    /**
     * Get a role name.
     *
     * @return string
     */
    public function getRoleNameAttribute()
    {
        return $this->roles()[$this->role];
    }

    /**
     * Filter a query by role name.
     *
     * @param Builder $query
     * @param string  $roleName
     *
     * @return $this
     */
    public function scopeByRoleName(Builder $query, string $roleName)
    {
        return $query->where('role', constant(self::class . '::ROLE_' . strtoupper(snake_case($roleName))));
    }

    /**
     * Role list.
     *
     * @return array
     */
    public static function roles()
    {
        return [
            self::ROLE_MANAGER => 'Kitchen Manager',
            self::ROLE_WAITER  => 'Waiter'
        ];
    }

    /**
     * Determine if auth user role equals given role.
     *
     * @param string $roleName
     *
     * @return bool
     */
    public function hasRole(string $roleName)
    {
        return $this->role === strtolower($roleName);
    }
}
