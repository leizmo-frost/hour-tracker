<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\{BelongsTo, HasMany};
use Illuminate\Database\Eloquent\Builder;

class employee extends Model
{
    protected $fillable = ['user_id', 'company_id', 'reports_to', 'hire_date', 'hourly_rate', 'leadership_level', 'employee_code'];
    protected $casts = ['hire_date' => 'date', 'hourly_rate' => 'decimal:2'];
    protected static function booted() {
        // Multi-tenancy global scope
        static::addGlobalScope('company', function (Builder $builder) {
            if (auth()->check()) $builder->where('company_id', auth()->user()->company_id);
        });
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
    public function manager(): BelongsTo
    {
        return $this->belongsTo(Employee::class, 'reports_to');
    }
    public function subordinates(): HasMany
    {
        return $this->hasMany(Employee::class, 'reports_to');
    }
    public function timeEntries(): HasMany
    {
        return $this->hasMany(TimeEntry::class);
    }
    public function timesheets(): HasMany
    {
        return $this->hasMany(Timesheet::class);
    }

    // Simple entity logic
    public function isCurrentlyClockedIn(): bool
    {
        return $this->timeEntries()->whereDate('clock_in', today())->whereNull('clock_out')->exists();
    }
    public function getCurrentShift(): ?TimeEntry
    {
        return $this->timeEntries()->whereDate('clock_in', today())->whereNull('clock_out')->latest('clock_in')->first();
    }

}
