<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Spatie\Permission\Models\Role as SpatieRole;

class Role extends SpatieRole
{
    use HasFactory;

    protected $connection;

    public function __construct()
    {
        $this->connection = env('GSCEXPORT_DATABASE');
    }

    protected $fillable = [
        'name', 'description', 'permissions', 'is_merchant',
    ];

    protected $hidden = [
        'guard_name',
    ];

    protected $casts = [
        'permissions' => 'array',
        'is_merchant' => 'boolean',
    ];
}
