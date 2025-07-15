<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Symptom extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'category',
        'description',
        'recommendation',
    ];

    /**
     * Get all of the symptomLogs for the Symptom
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function symptomLogs(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(SymptomLog::class);
    }

    /**
     * The entries that belong to the Symptom
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function entries(): \Illuminate\Database\Eloquent\Relations\BelongsToMany
    {
        return $this->belongsToMany(SymptomEntry::class, 'entry_symptom');
    }
}
