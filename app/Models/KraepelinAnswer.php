<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class KraepelinAnswer extends Model
{
    use HasFactory;

    protected $guarded = [];

    protected $casts = [
        'top_number'    => 'integer',
        'bottom_number' => 'integer',
        'user_answer'   => 'integer',
        'is_correct'    => 'boolean',
        'answered_at'   => 'datetime',
    ];

    public function session()
    {
        return $this->belongsTo(TestSession::class, 'test_session_id');
    }
}
