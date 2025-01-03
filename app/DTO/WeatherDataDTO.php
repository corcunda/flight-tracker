<?php

namespace App\DTO;

use Spatie\DataTransferObject\DataTransferObject;

class WeatherDataDTO extends DataTransferObject
{
    public string $name;
    public string $country;
    public string $temperature;
    public string $description;
    public string $humidity;
    public string $wind_speed;
    public string $icon;
    public string $local_time;
}