<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class OvertimeRule extends Model
{
    protected $fillable = ['name', 'period_type', 'threshold_minutes', 'multiplier', 'is_active'];

    protected function casts(): array
    {
        return [
            'threshold_minutes' => 'integer',
            'multiplier' => 'decimal:2',
            'is_active' => 'boolean',
        ];
    }
}
