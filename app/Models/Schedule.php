<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Schedule extends Model
{
    use HasFactory;

    protected $table = 'schedule';

    protected $fillable = [
        'day',
        'time',
        'event_type',
        'event_id'
    ];

    public function event()
    {
        return $this->belongsTo(Event::class);
    }
}
