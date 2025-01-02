<?php

namespace App\DTO;

use Spatie\DataTransferObject\DataTransferObject;

class FlightDataDTO extends DataTransferObject
{
    public string $id;
    public string $flight_number;
    public string $status;
    public string $color_plane;
    public string $color_path;
    public string $airline;
    public string $origin;
    public string $destination;
    public float $origin_latitude;
    public float $origin_longitude;
    public float $destination_latitude;
    public float $destination_longitude;
    public float $current_latitude;
    public float $current_longitude;
    public string $progress_percentage;
}