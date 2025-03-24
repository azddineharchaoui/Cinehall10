<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Theater extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'type', // 'Normal', 'VIP'
        'rows',
        'seats_per_row',
    ];

    public function sessions()
    {
        return $this->hasMany(Session::class);
    }

    public function seats()
    {
        return $this->hasMany(Seat::class);
    }
}