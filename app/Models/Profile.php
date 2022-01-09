<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;

/**
 * @mixin Builder
 */
class Profile extends Model
{
    public const DEFAULT_IMAGE = self::IMAGE_FOLDER.'/default.png';
    public const IMAGE_FOLDER = 'user_avatars';

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function user() {
        return $this->belongsTo(User::class);
    }

    /**
     * Get profile avatar URL.
     *
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\Routing\UrlGenerator|string
     */
    public function getAvatarUrl() {
        $storage = Storage::disk('public');

        if ($storage->exists($this->avatar)) {
            return $storage->url($this->avatar);
        } else {
            return $storage->url(self::DEFAULT_IMAGE);
        }
    }

    /**
     * Get user job name.
     *
     * @return mixed
     */
    public function getJobName() {
        return $this->user->role->name;
    }
}
