<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Attachment extends Model
{
    protected $fillable = [
        'project_id',
        'filename',
        'path',
        'order_num',
    ];

    public function project(): BelongsTo
    {
        return $this->belongsTo(Project::class);
    }
}
