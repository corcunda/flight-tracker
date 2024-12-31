<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class FlightPosition extends Model
{
    use HasFactory;

    protected $table = 'flight_positions';

    // Define the fillable attributes for mass assignment
    protected $fillable = [
        'flight_id',
        'latitude',
        'longitude',
        'updated_at',
    ];

    public function flight()
    {
        return $this->belongsTo(Flight::class);
    }

}
