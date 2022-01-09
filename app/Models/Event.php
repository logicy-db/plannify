<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;

/**
 * @mixin Builder
 */
class Event extends Model
{
    public const IMAGE_FOLDER = 'event_previews';
    public const DEFAULT_IMAGE = self::IMAGE_FOLDER.'/default.png';

    public const USER_GOING = ParticipationType::USER_GOING;
    public const USER_CANCELED = ParticipationType::USER_CANCELED;
    public const USER_QUEUED = ParticipationType::USER_QUEUED;

    /**
     * The attributes that are mass assignable.
     *
     * @var string[]
     */
    protected $fillable = [
        'name',
        'description',
        'location',
        'meeting_point',
        'status',
        'preview',
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

    /**
     * @return \Illuminate\Database\Eloquent\Collection
     */
    public function usersCanceled() {
        return $this->users()->wherePivot('participation_type_id', self::USER_CANCELED)
            ->orderByPivot('updated_at')->get();
    }

    /**
     * @return \Illuminate\Database\Eloquent\Collection
     */
    public function usersGoing()
    {
        return $this->users()->wherePivot('participation_type_id', self::USER_GOING)
            ->orderByPivot('updated_at')->get();
    }

    /**
     * @return \Illuminate\Database\Eloquent\Collection
     */
    public function usersQueued()
    {
        return $this->users()->wherePivot('participation_type_id', self::USER_QUEUED)
            ->orderByPivot('updated_at')->get();
    }

    /**
     * @return bool
     */
    public function isFull() {
        return $this->users()->where(
            'participation_type_id', self::USER_GOING
            )->count() >= $this->attendees_limit;
    }

    /**
     * @return string
     */
    public function getPreviewUrl() {
        $storage = Storage::disk('public');

        if ($storage->exists($this->preview)) {
            return $storage->url($this->preview);
        } else {
            return $storage->url(self::DEFAULT_IMAGE);
        }
    }

    /**
     * If event is happening in the future.
     *
     * @return bool
     */
    public function isPlanned() {
        return strtotime($this->starting_time) > strtotime(now());
    }
}
