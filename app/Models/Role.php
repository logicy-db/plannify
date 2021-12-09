<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\User;

class Role extends Model
{
    use HasFactory;

    // User access levels
    public const LEVEL_WORKER = 1;
    public const LEVEL_PROJECT_MANAGER = 2;
    public const LEVEL_EVENT_MANAGER = 3;
    public const LEVEL_HUMAN_RESOURCES = 4;
    public const LEVEL_ADMIN = 5;

    // User roles within system
    public const DEVELOPER = 1;
    public const MARKETING = 2;
    public const QUALITY_ASSURANCE = 3;
    public const PROJECT_MANAGER = 4;
    public const EVENT_ORGANIZER = 5;
    public const HUMAN_RESOURCES = 6;
    public const ADMIN = 7;

    public const USER_ROLES = [
        self::DEVELOPER => ['name' => 'Developer', 'access_level' => self::LEVEL_WORKER],
        self::MARKETING => ['name' => 'Quality assurance', 'access_level' => self::LEVEL_WORKER],
        self::QUALITY_ASSURANCE => ['name' => 'Marketing', 'access_level' => self::LEVEL_WORKER],
        self::PROJECT_MANAGER => ['name' => 'Project manager', 'access_level' => self::LEVEL_PROJECT_MANAGER],
        self::EVENT_ORGANIZER => ['name' => 'Event organizer', 'access_level' => self::LEVEL_EVENT_MANAGER],
        self::HUMAN_RESOURCES => ['name' => 'Human resources', 'access_level' => self::LEVEL_HUMAN_RESOURCES],
        self::ADMIN => ['name' => 'Administrator', 'access_level' => self::LEVEL_ADMIN],
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
