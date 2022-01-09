<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * @mixin Builder
 */
class ParticipationType extends Model
{
    // Participation types
    public const USER_GOING = 1;
    public const USER_CANCELED = 2;
    public const USER_QUEUED = 3;

    public const PARTICIPATION_TYPES = [
        self::USER_GOING => 'Going',
        self::USER_CANCELED => 'Canceled',
        self::USER_QUEUED => 'In queue',
    ];

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function users() {
        return $this->belongsToMany(User::class);
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function events() {
        return $this->belongsToMany(Event::class);
    }
}
