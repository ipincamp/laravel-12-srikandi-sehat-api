<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Classification extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
    ];

    /**
     * Get all of the villages for the Classification
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function villages(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(Village::class);
    }
}
