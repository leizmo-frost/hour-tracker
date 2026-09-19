<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class company extends Model
{
    protected $fillable = ['name', 'strings', 'status'];
    protected $casts = ['settings' => 'array'];

    public function users ()
    {
        return $this->hasMany(User::class);
    }

    public function employees ()
    {
        return $this->hasMany(Employee::class);
    }

    //Helper for missed punch threshold (default 3)
    public function getMissedPunchThresholdAttribute()
    {
        return $this->settings['missed_punch_threshold'] ?? 3;
    }
}
