<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Jabatan extends Model
{
    protected $fillable = [
        'golongan_id',
        'department_id',
        'name',
        'created_by',
        'is_active',
    ];

    protected $table = 'jabatan';

    public function users(): HasMany
    {
        return $this->hasMany(User::class);
    }

    public function golongan()
    {
        return $this->belongsTo(Golongan::class, 'golongan_id');
    }

    public function department()
    {
        return $this->belongsTo(Department::class, 'department_id');
    }
}