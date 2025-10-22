<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use Spatie\Permission\Traits\HasRoles;

class User extends Authenticatable
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasApiTokens, HasFactory, HasRoles, HasUuids, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    /**
     * Get the profile associated with the User
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasOne
     */
    public function profile(): \Illuminate\Database\Eloquent\Relations\HasOne
    {
        return $this->hasOne(UserProfile::class);
    }

    /**
     * Get all of the menstrualCycles for the User
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function menstrualCycles(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(MenstrualCycle::class);
    }

    /**
     * Get all of the symptomEntries for the User
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function symptomEntries(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(SymptomEntry::class);
    }

    /**
     * Get the activeCycle associated with the User
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasOne
     */
    public function activeCycle(): \Illuminate\Database\Eloquent\Relations\HasOne
    {
        return $this->hasOne(MenstrualCycle::class)
            ->whereNull('finish_date')
            ->latest('start_date');
    }

    /**
     * Get the current cycle number for the User.
     *
     * Accessor untuk mendapatkan nomor siklus saat ini.
     * Nama fungsi ini akan menjadi atribut 'current_cycle_number'.
     *
     * @return int|null
     */
    public function getCurrentCycleNumberAttribute(): ?int
    {
        // Jika tidak ada siklus yang aktif, kembalikan null.
        if (!$this->activeCycle) {
            return null;
        }

        // Jika ada, nomor siklus adalah total siklus yang pernah dimiliki user.
        return $this->menstrualCycles()->count();
    }
}
