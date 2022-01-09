<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * @mixin Builder
 */
class Role extends Model
{
    // User roles within system
    public const WORKER = 1;
    public const EVENT_ORGANIZER = 2;
    public const HUMAN_RESOURCES = 3;
    public const ADMIN = 4;

    public const USER_ROLES = [
        self::WORKER => 'Worker',
        self::EVENT_ORGANIZER => 'Event organizer',
        self::HUMAN_RESOURCES => 'Human resources',
        self::ADMIN => 'Administrator',
    ];

    /**
     * @var string[]
     */
    protected $fillable = [
        'id',
        'name',
    ];

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function users() {
        return $this->hasMany(User::class);
    }
}
