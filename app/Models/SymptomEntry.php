<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SymptomEntry extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'user_id',
        'menstrual_cycle_id',
        'log_date',
        'notes',
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
     * Get the MenstrualCycle that owns the SymptomEntry
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function menstrualCycle(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(MenstrualCycle::class);
    }

    /**
     * The symptoms that belong to the SymptomEntry
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function symptoms(): \Illuminate\Database\Eloquent\Relations\BelongsToMany
    {
        return $this->belongsToMany(Symptom::class, 'entry_symptom');
    }
}
