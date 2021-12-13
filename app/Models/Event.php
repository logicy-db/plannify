<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;

class Event extends Model
{
    use HasFactory;

    public const DEFAULT_IMAGE = '/event_images/default.png';
    public const IMAGE_FOLDER = '/event_images';

    public const USER_GOING = 1;
    public const USER_CANCELED = 2;
    public const USER_QUEUED = 3;

    public const PARTICIPATION_TYPES = [
        self::USER_GOING => 'Going',
        self::USER_CANCELED => 'Canceled',
        self::USER_QUEUED => 'In queue',
    ];

    /**
     * The attributes that are mass assignable.
     *
     * @var string[]
     */
    protected $fillable = [
        'name',
        'description',
        'location',
        'status',
        'starting_date',
        'starting_time',
        'attendees_limit',
    ];

    /**
     * Get users related to the event.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function users() {
        return $this->belongsToMany(User::class)
            ->withTimestamps()
            ->withPivot(['participation_type_id']);
    }

    public function usersCanceled() {
        // TODO: looks sus, need to apply order by pivot updated_at value
        return $this->users()->wherePivot('participation_type_id', Event::USER_CANCELED)->orderBy('updated_at')->get();
    }

    public function usersGoing()
    {
        return $this->users()->wherePivot('participation_type_id', Event::USER_GOING)->orderBy('updated_at')->get();
    }

    public function usersQueued()
    {
        return $this->users()->wherePivot('participation_type_id', Event::USER_QUEUED)->orderBy('updated_at')->get();
    }

    public function isFull() {
        return $this->users()->where('participation_type_id', Event::USER_GOING)->count() >= $this->attendees_limit;
    }
}
