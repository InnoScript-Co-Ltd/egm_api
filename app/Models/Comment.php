<?php

namespace App\Models;

use App\Enums\GeneralStatusEnum;
use App\Traits\BasicAudit;
use App\Traits\SnowflakeID;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

class Comment extends Model
{
    use BasicAudit, HasFactory, SnowflakeID, SoftDeletes;

    protected $table = 'comments';

    protected $fillable = [
        'article_type_id',
        'article_id',
        'comment',
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

    public function article(): BelongsTo
    {
        return $this->belongsTo(Article::class);
    }
}
