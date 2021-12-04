<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\User;

class Role extends Model
{
    use HasFactory;

    // User roles within system
    public const WORKER = 1;
    public const PROJECT_MANAGER = 2;
    public const EVENT_ORGANIZER = 3;
    public const HUMAN_RESOURCES = 4;
    public const ADMIN = 5;

    /**
     * @var string[]
     */
    protected $fillable = [
        'id',
        'name',
    ];

    public function users() {
        return $this->hasMany(User::class);
    }
}
