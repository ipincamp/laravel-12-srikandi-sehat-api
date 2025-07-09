<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Village extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'district_id',
        'classification_id',
        'code',
        'name',
    ];

    /**
     * Get the district that owns the Village
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function district(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(District::class);
    }

    /**
     * Get the classification that owns the Village
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function classification(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(Classification::class);
    }
}
