<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Facades\Storage;

class Attachment extends Model
{
    protected $fillable = [
        'project_id',
        'filename',
        'path',
        'order_num',
    ];

    public static function boot(): void
    {
        parent::boot();

        static::deleting(function ($attachment) {
            Storage::delete($attachment->path);
        });
    }

    public function project(): BelongsTo
    {
        return $this->belongsTo(Project::class);
    }
}
