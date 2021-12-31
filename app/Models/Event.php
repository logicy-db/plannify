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
    use HasFactory;

    public const IMAGE_FOLDER = 'event_previews';
    public const DEFAULT_IMAGE = self::IMAGE_FOLDER.'/default.jpg';

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

    public function eventStatus() {
        return $this->belongsTo(EventStatus::class);
    }

    public function usersCanceled() {
        return $this->users()->wherePivot('participation_type_id', Event::USER_CANCELED)
            ->orderByPivot('updated_at')->get();
    }

    public function usersGoing()
    {
        return $this->users()->wherePivot('participation_type_id', Event::USER_GOING)
            ->orderByPivot('updated_at')->get();
    }

    public function usersQueued()
    {
        return $this->users()->wherePivot('participation_type_id', Event::USER_QUEUED)
            ->orderByPivot('updated_at')->get();
    }

    public function isFull() {
        return $this->users()->where('participation_type_id', Event::USER_GOING)->count() >= $this->attendees_limit;
    }

    public function getPreviewUrl() {
        $storage = Storage::disk('public');

        if ($storage->exists($this->preview)) {
            return $storage->url($this->preview);
        } else {
            return $storage->url(self::DEFAULT_IMAGE);
        }
    }
}
