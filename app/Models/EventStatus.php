<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * @mixin Builder
 */
class EventStatus extends Model
{
    use HasFactory;

    public const STATUS_ACTIVE = 1;
    public const STATUS_CANCELED = 2;
    public const STATUS_HAPPENED = 3;

    public const EVENT_STATUSES = [
        self::STATUS_ACTIVE => 'Active',
        self::STATUS_CANCELED => 'Canceled',
        self::STATUS_HAPPENED => 'Happened',
    ];

    public function events() {
        return $this->hasMany(Event::class);
    }
}
