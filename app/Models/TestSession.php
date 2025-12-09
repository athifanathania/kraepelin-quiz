<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class TestSession extends Model
{
    protected $guarded = [];

    protected $casts = [
        'started_at'    => 'datetime',
        'finished_at'   => 'datetime',
        'can_retake'    => 'boolean',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function test(): BelongsTo
    {
        return $this->belongsTo(Test::class);
    }

    public function kraepelinAnswers(): HasMany
    {
        return $this->hasMany(KraepelinAnswer::class);
    }
}
