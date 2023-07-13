<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Project extends Model
{
    protected $fillable = [
        'name',
        'user_id',
        'book_id',
        'book_title',
        'book_description',
        'book_creator',
        'book_subject',
        'book_publisher',
        'book_language',
        'book_width',
        'book_height',
        'book_webtoon_style',
    ];

    protected $casts = [
        'book_webtoon_style' => 'boolean',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function attachments(): HasMany
    {
        return $this->hasMany(Attachment::class);
    }

    public function books(): HasMany
    {
        return $this->hasMany(Book::class);
    }
}
