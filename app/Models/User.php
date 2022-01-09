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
    use HasApiTokens, Notifiable;

    public const STATUS_DISABLED = 0;
    public const STATUS_ACTIVE = 1;

    /**
     * The attributes that are mass assignable.
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
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function role() {
        return $this->belongsTo(Role::class);
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasOne
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
     * Get user full name. If no profile is set, gets user email
     *
     * @return string
     */
    public function getFullname() {
        if ($this->profile) {
            return "{$this->profile->first_name} {$this->profile->last_name}";
        } else {
            return $this->email;
        }
    }

    /**
     * @return bool
     */
    public function hasSystemAccess() {
        return in_array($this->role_id, [Role::HUMAN_RESOURCES, Role::ADMIN]);
    }

    /**
     * @return string
     */
    public function getProfileUrl() {
        if ($this->profile) {
            return route('profiles.show', $this->profile);
        } else {
            return '';
        }
    }
}
