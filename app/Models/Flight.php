<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Carbon;

class Flight extends Model
{
    use HasFactory;

    protected $table = 'flights';

    protected $fillable = [
        'flight_number',
        'airline',
        'origin',
        'destination',
        'speed',
        'color_plane',
        'color_path',
        'origin_latitude',
        'origin_longitude',
        'destination_latitude',
        'destination_longitude',
        'scheduled_departure',
        'scheduled_arrival',
        'actual_departure',
        'actual_arrival',
        'status',               // scheduled | in_progress | arrived | delayed | cancelled | completed
        'is_delayed',
        'distance',
        'estimated_duration',
    ];

    // Define the relationship with FlightPosition (One to Many)
    public function positions()
    {
        return $this->hasMany(FlightPosition::class);
    }

}
