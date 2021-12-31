<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

/**
 * @mixin Builder
 */
class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * TODO: Update attributes based on User migration
     *
     * @var string[]
     */
    protected $fillable = [
        'email',
        'password',
        'first_name',
        'last_name',
        'role_id',
        'profile_id',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    /**
     * Default attribute values (if not specified).
     *
     * @var array
     */
    protected $attributes = [];

    /**
     * Get user role.
     */
    public function role() {
        return $this->belongsTo(Role::class);
    }

    /**
     * Get profile.
     */
    public function profile() {
        return $this->hasOne(Profile::class);
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function events() {
        return $this->belongsToMany(Event::class)->withTimestamps();
    }

    /**
     * Get user full name.
     *
     * @return string
     */
    public function getFullname() {
        if ($this->profile) {
            return sprintf('%s %s', $this->profile->first_name, $this->profile->last_name);
        } else {
            return 'missing';
        }
    }

    /**
     * @return bool
     */
    public function hasSystemAccess() {
        return in_array($this->role_id, [Role::HUMAN_RESOURCES, Role::ADMIN]);
    }

    public function getProfileUrl() {
        if ($this->profile) {
            return route('profiles.show', $this->profile);
        } else {
            return '';
        }
    }
}
