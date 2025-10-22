<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class District extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'regency_id',
        'code',
        'name',
    ];

    /**
     * Get the regency that owns the District
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function regency(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(Regency::class);
    }

    /**
     * Get all of the villages for the District
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function villages(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(Village::class);
    }
}
