<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class MenstrualCycle extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'user_id',
        'start_date',
        'end_date',
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
}
