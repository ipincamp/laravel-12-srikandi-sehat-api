<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class District extends Model
{
    protected $fillable = ['name'];

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
