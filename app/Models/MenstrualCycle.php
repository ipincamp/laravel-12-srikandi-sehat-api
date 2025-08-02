<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MenstrualCycle extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'user_id',
        'start_date',
        'finish_date',
        'period_prolonged_notified_at',
        'cycle_irregularity_notified_at',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'start_date' => 'datetime',
        'finish_date' => 'datetime',
    ];

    /**
     * Get the user that owns the MenstrualCycle
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function user(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get all of the symptomLogs for the MenstrualCycle
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function symptomLogs(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(SymptomLog::class);
    }

    /**
     * Get all of the symptomEntries for the MenstrualCycle
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function symptomEntries(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(SymptomEntry::class);
    }
}
