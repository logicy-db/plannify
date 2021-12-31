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

    public const INVITE_STATUS = [
        self::PENDING => 'Pending',
        self::ACCEPTED => 'Accepted',
        self::EXPIRED => 'Expired',
    ];

    public const INVITE_STATUS_CSS = [
        self::PENDING => 'alert',
        self::ACCEPTED => 'success',
        self::EXPIRED => 'disabled',
    ];

    protected $fillable = [
        'email', 'invitation_token', 'registered_at'
    ];

    public function getRegistrationLink()
    {
        return urldecode(route('registration') . '?invitation_token=' . $this->invitation_token);
    }

    public function role() {
        return $this->belongsTo(Role::class);
    }

    public function inviter() {
        return $this->belongsTo(User::class, 'invited_by');
    }

    public function getStatus()
    {
        return self::INVITE_STATUS[$this->status];
    }

    /**
     * Is used to style invite status button on the invite listing page.
     */
    public function getStatusCssClass()
    {
        return self::INVITE_STATUS_CSS[$this->status];
    }
}
