<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\User;

class Role extends Model
{
    use HasFactory;

    // User roles within system
    public const DEVELOPER = 1;
    public const MARKETING = 2;
    public const QUALITY_ASSURANCE = 3;
    public const PROJECT_MANAGER = 4;
    public const EVENT_ORGANIZER = 5;
    public const HUMAN_RESOURCES = 6;
    public const ADMIN = 7;

    public const USER_ROLES = [
        self::DEVELOPER => 'Developer',
        self::MARKETING => 'Marketing',
        self::QUALITY_ASSURANCE => 'Quality assurance',
        self::PROJECT_MANAGER => 'Project manager',
        self::EVENT_ORGANIZER => 'Event organizer',
        self::HUMAN_RESOURCES => 'Human resources',
        self::ADMIN => 'Administrator',
    ];

    /**
     * @var string[]
     */
    protected $fillable = [
        'id',
        'name',
        'access_level'
    ];

    public function users() {
        return $this->hasMany(User::class);
    }
}
