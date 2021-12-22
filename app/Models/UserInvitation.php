<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * @mixin Builder
 */
class UserInvitation extends Model
{
    use HasFactory;

    public const PENDING = 1;
    public const ACCEPTED = 2;
    public const EXPIRED = 3;

    public const USER_ROLES = [
        self::PENDING => 'Pending',
        self::ACCEPTED => 'Accepted',
        self::EXPIRED => 'Expired',
    ];

    protected $fillable = [
        'email', 'invitation_token', 'registered_at'
    ];

    public function getRegistrationLink()
    {
        return urldecode(route('registration') . '?invitation_token=' . $this->invitation_token);
    }
}
