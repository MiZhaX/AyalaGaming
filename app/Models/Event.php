<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Event extends Model
{
    use HasFactory;

    protected $table = 'events';

    protected $fillable = [
        'name',
        'inPersonAssistance',
        'virtualAssistance',
        'speaker_id'
    ];

    public function schedule()
    {
        return $this->hasOne(Schedule::class); 
    }

    public function registrations()
    {
        return $this->hasMany(Registration::class);
    }
}
