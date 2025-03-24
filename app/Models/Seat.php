<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Seat extends Model
{
    use HasFactory;

    protected $fillable = [
        'theater_id',
        'row',
        'number',
        'type', // 'regular', 'couple'
    ];

    public function theater()
    {
        return $this->belongsTo(Theater::class);
    }

    public function reservations()
    {
        return $this->belongsToMany(Reservation::class, 'reservation_seat');
    }
}