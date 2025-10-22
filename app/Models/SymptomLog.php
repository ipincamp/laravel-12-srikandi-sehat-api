<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SymptomLog extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'user_id',
        'symptom_id',
        'menstrual_cycle_id',
        'log_date',
        'notes',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'log_date' => 'datetime',
    ];

    /**
     * Get the user that owns the SymptomLog
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function user(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get the symptom that owns the SymptomLog
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function symptom(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(Symptom::class);
    }

    /**
     * Get the menstrualCycle that owns the SymptomLog
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function menstrualCycle(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(MenstrualCycle::class);
    }
}
