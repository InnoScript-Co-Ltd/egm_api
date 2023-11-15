<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Spatie\Permission\Models\Role as SpatieRole;

class Role extends Model
{
    use HasFactory;

    protected $fillable = [
        "name", "description", "permissions"
    ];

    protected $hidden = [
        "guard_name"
    ];

    protected $casts = [
        'permissions' => 'array'
    ];

}
