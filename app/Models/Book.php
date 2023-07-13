<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Facades\Storage;

class Book extends Model
{
    protected $fillable = [
        'project_id',
        'name',
        'path',
    ];

    public static function boot(): void
    {
        parent::boot();

        static::deleting(function ($book) {
            Storage::delete($book->path);
        });
    }

    public function project(): BelongsTo
    {
        return $this->belongsTo(Project::class);
    }
}
