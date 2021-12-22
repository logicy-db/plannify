<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * @mixin Builder
 */
class Profile extends Model
{
    use HasFactory;

    public const DEFAULT_IMAGE = self::IMAGE_FOLDER.'/default.png';
    public const IMAGE_FOLDER = 'images/avatars';

    public function user() {
        return $this->belongsTo(User::class);
    }

    /**
     * Get profile avatar URL.
     *
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\Routing\UrlGenerator|string
     */
    public function getAvatarUrl() {
        return is_file(public_path(sprintf('%s/%s',self::IMAGE_FOLDER, $this->avatar))) ?
            url(sprintf('%s/%s',self::IMAGE_FOLDER, $this->avatar)) :
            url(self::DEFAULT_IMAGE);
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
