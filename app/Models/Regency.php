<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Regency extends Model
{
    protected $fillable = ['province_id', 'code', 'name'];

    /**
     * Get the province that owns the Regency
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function province(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(Province::class);
    }

    /**
     * Get all of the districts for the Regency
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function districts(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(District::class);
    }
}
