<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Profile extends Model
{
    use HasFactory;

    public function user() {
        return $this->belongsTo(User::class);
    }

    /**
     * Get profile avatar URL.
     *
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\Routing\UrlGenerator|string
     */
    public function getAvatarUrl() {
        return is_file(url('/avatars/'.$this->avatar)) ?
            url('/avatars/'.$this->avatar) :
            url('/avatars/default.jpg');
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
