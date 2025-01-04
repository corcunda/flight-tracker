<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Default flight routes
    |--------------------------------------------------------------------------
    |
    | These routes will be used to simulate the respective flights that
    | will be populated on database for flight tracking.
    |
    */
    'flight_routes' => [
        [
            'flight_number' => 'FL001',
            'airline' => 'Airline A',
            'origin' => 'New York',
            'destination' => 'London',
            'speed' => 800, // km/h
            'color_plane' => 'blue',
            'color_path' => 'lightblue',
            'origin_latitude' => 40.7128,
            'origin_longitude' => -74.0060,
            'destination_latitude' => 51.5074,
            'destination_longitude' => -0.1278,
            'specifics' => [ // example of a flight to depart in 1 minute
                'type' => 'future',
                'value' => 1,
            ],
            'status' => 'scheduled', 
            'is_delayed' => true,
        ],
        [
            'flight_number' => 'FL002',
            'airline' => 'Airline B',
            'origin' => 'San Francisco',
            'destination' => 'Tokyo',
            'speed' => 850, 
            'color_plane' => 'red',
            'color_path' => 'pink',
            'origin_latitude' => 37.7749,
            'origin_longitude' => -122.4194,
            'destination_latitude' => 35.6762,
            'destination_longitude' => 139.6503,
            'specifics' => [ // example of a flight to depart in 2 minutes
                'type' => 'future',
                'value' => 2,
            ],
            'status' => 'scheduled', 
            'is_delayed' => true,
        ],
        [
            'flight_number' => 'FL003',
            'airline' => 'Airline C',
            'origin' => 'Los Angeles',
            'destination' => 'Paris',
            'speed' => 800, 
            'color_plane' => 'green',
            'color_path' => 'lightgreen',
            'origin_latitude' => 34.0522,
            'origin_longitude' => -118.2437,
            'destination_latitude' => 48.8566,
            'destination_longitude' => 2.3522,
            'specifics' => [ // example of a flight that will start at 50% completion of the route
                'type' => 'on_route',
                'value' => 50,
            ],
            'status' => 'in_progress', // On the route already
            'is_delayed' => false,
        ],
        [
            'flight_number' => 'FL004',
            'airline' => 'Airline D',
            'origin' => 'Berlin',
            'destination' => 'Rome',
            'speed' => 750,
            'color_plane' => 'yellow',
            'color_path' => 'orange',
            'origin_latitude' => 52.52,
            'origin_longitude' => 13.4050,
            'destination_latitude' => 41.9028,
            'destination_longitude' => 12.4964,
            'specifics' => [ // example of a flight that will start at 95% completion of the route
                'type' => 'on_route',
                'value' => 95,
            ],
            'status' => 'in_progress', // On the route already
            'is_delayed' => false,
        ],
        [
            'flight_number' => 'FL005',
            'airline' => 'Airline E',
            'origin' => 'Dubai',
            'destination' => 'Mumbai',
            'speed' => 900, 
            'color_plane' => 'purple',
            'color_path' => 'violet',
            'origin_latitude' => 25.276987,
            'origin_longitude' => 55.296249,
            'destination_latitude' => 19.0760,
            'destination_longitude' => 72.8777,
            'specifics' => [], // Example of a flight that has mmediate departure
            'status' => 'scheduled',
            'is_delayed' => false,
        ],
        [
            'flight_number' => 'FL006',
            'airline' => 'Airline A',
            'origin' => 'New York',
            'destination' => 'Rome',
            'speed' => 800, // km/h
            'color_plane' => 'blue',
            'color_path' => 'lightblue',
            'origin_latitude' => 40.7128,
            'origin_longitude' => -74.0060,
            'destination_latitude' => 41.9028,
            'destination_longitude' => 12.4964,
            'specifics' => [ // example of a flight that will start at 95% completion of the route
                'type' => 'on_route',
                'value' => 30,
            ],
            'status' => 'in_progress', // On the route already
            'is_delayed' => false,
        ],
    ],


    /*
    |--------------------------------------------------------------------------
    | Default weather cities
    |--------------------------------------------------------------------------
    |
    | Cities that will be fetched the weather from.
    |
    */
    'weather_cities' => [
        'Dublin', 
        'London', 
        'New York', 
        'Paris', 
        'Tokyo', 
        'Los Angeles', 
        'Berlin', 
        'Sydney', 
        'Moscow', 
        'Rome', 
        'Istanbul', 
        'Cape Town', 
        'Dubai', 
        'Shanghai', 
        'Toronto', 
        'São Paulo', 
        'Mexico City', 
        'Mumbai', 
        'Seoul', 
        'Hong Kong',
    ],


    /*
    |--------------------------------------------------------------------------
    | Flight simulation settings
    |--------------------------------------------------------------------------
    |
    | These settings will be applied into the flight simulation.
    |
    */
    'simulation_settings' => [
        'update_interval' => 5, // seconds between each simulation step
    ],
    
];