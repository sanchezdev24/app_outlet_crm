<?php

namespace App\Infrastructure\Persistence\Eloquent\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Customer extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'name',
        'last_name',
        'email',
        'phone',
        'address',
        'type',
        'birth_date',
        'is_active',
    ];

    protected $casts = [
        'birth_date' => 'date',
        'is_active' => 'boolean',
    ];

    public function sales()
    {
        return $this->hasMany(Sale::class);
    }

    public function getFullNameAttribute()
    {
        return $this->name . ' ' . $this->last_name;
    }
}