<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Session extends Model
{
    use HasFactory;

    protected $table = 'movie_sessions';

    protected $fillable = [
        'movie_id',
        'theater_id',
        'start_time',
        'language',
        'type', // 'Normal', 'VIP'
    ];

    protected $casts = [
        'start_time' => 'datetime',
    ];

    public function movie()
    {
        return $this->belongsTo(Movie::class);
    }

    public function theater()
    {
        return $this->belongsTo(Theater::class);
    }

    public function reservations()
    {
        return $this->hasMany(Reservation::class);
    }
}