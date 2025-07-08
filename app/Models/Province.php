<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Province extends Model
{
    protected $fillable = ['code', 'name'];

    /**
     * Get all of the regencies for the Province
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function regencies(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(Regency::class);
    }
}
