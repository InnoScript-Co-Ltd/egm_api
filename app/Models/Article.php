<?php

namespace App\Models;

use App\Enums\GeneralStatusEnum;
use App\Traits\BasicAudit;
use App\Traits\SnowflakeID;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class Article extends Model
{
    use BasicAudit, HasFactory, SnowflakeID, SoftDeletes;

    protected $table = 'articles';

    protected $fillable = [
        'article_type_id',
        'language',
        'title',
        'description',
        'photos',
        'content',
        'status',
    ];

    protected $casts = [
        'photos' => 'array',
    ];

    public function scopeActive()
    {
        return $this->where('status', GeneralStatusEnum::ACTIVE->value);
    }

    public function comments(): HasMany
    {
        return $this->hasMany(Comment::class);
    }
}
