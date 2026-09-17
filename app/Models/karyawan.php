<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Karyawan extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'email',
        'phone',
        'gender',
        'birth_date',
        'division',
        'position',
        'join_date',
        'status',
        'address',
        'photo',
    ];

    protected $casts = [
        'birth_date' => 'date',
        'join_date' => 'date',
    ];

    /**
     * Masa kerja dalam format "X tahun Y bulan".
     */
    public function getMasaKerjaAttribute(): string
    {
        $years = $this->join_date->diffInYears(now());
        $months = $this->join_date->diffInMonths(now()) % 12;

        if ($years < 1 && $months < 1) {
            return 'Kurang dari 1 bulan';
        }

        $parts = [];
        if ($years > 0) {
            $parts[] = $years . ' tahun';
        }
        if ($months > 0) {
            $parts[] = $months . ' bulan';
        }

        return implode(' ', $parts);
    }

    /**
     * Usia berdasarkan tanggal lahir.
     */
    public function getUsiaAttribute(): ?int
    {
        return $this->birth_date ? $this->birth_date->age : null;
    }
}