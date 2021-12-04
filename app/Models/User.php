<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

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
    protected $attributes = [
        'active' => 1,
        'role_id' => Role::WORKER,
    ];

    /**
     * Get user role.
     */
    public function role() {
        return $this->belongsTo(Role::class);
    }

    /**
     * Get user full name.
     *
     * @return string
     */
    public function getFullname() {
        return isset($this->middle_name) ?
            sprintf('%s %s %s', $this->first_name, $this->middle_name, $this->last_name) :
            sprintf('%s %s', $this->first_name, $this->last_name);
    }
}
