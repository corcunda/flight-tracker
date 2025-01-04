<?php

return [

    /*
        Airline A
        'color_plane' => 'blue',
        'color_path' => 'lightblue',

        Airline B
        'color_plane' => 'red',
        'color_path' => 'pink',

        Airline C
        'color_plane' => 'green',
        'color_path' => 'lightgreen',

        Airline D
        'color_plane' => 'yellow',
        'color_path' => 'orange',

        Airline E
        'color_plane' => 'purple',
        'color_path' => 'violet',

        Airline F
        'color_plane' => 'cyan',
        'color_path' => 'lightcyan',

        Airline G
        'color_plane' => 'brown',
        'color_path' => 'tan',

        Airline H
        'color_plane' => 'magenta',
        'color_path' => 'lightpink',

        Airline I
        'color_plane' => 'darkblue',
        'color_path' => 'skyblue',

        Airline J
        'color_plane' => 'teal',
        'color_path' => 'aqua',
    */

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
            'airline' => 'Airline F',
            'origin' => 'New York',
            'destination' => 'Rome',
            'speed' => 800, // km/h
            'color_plane' => 'cyan',
            'color_path' => 'lightcyan',
            'origin_latitude' => 40.7128,
            'origin_longitude' => -74.0060,
            'destination_latitude' => 41.9028,
            'destination_longitude' => 12.4964,
            'specifics' => [ // example of a flight that will start at 30% completion of the route
                'type' => 'on_route',
                'value' => 30,
            ],
            'status' => 'in_progress', // On the route already
            'is_delayed' => false,
        ],
        [
            'flight_number' => 'FL007',
            'airline' => 'Airline G',
            'origin' => 'Curitiba',
            'destination' => 'São Paulo',
            'speed' => 700,
            'color_plane' => 'brown',
            'color_path' => 'tan',
            'origin_latitude' => -25.4284,
            'origin_longitude' => -49.2733,
            'destination_latitude' => -23.5505,
            'destination_longitude' => -46.6333,
            'specifics' => [ // example of a flight to depart in 1 minute
                'type' => 'future',
                'value' => 1,
            ],
            'status' => 'scheduled', 
            'is_delayed' => true,
        ],
        [
            'flight_number' => 'FL008',
            'airline' => 'Airline H',
            'origin' => 'Chicago',
            'destination' => 'Cancun',
            'speed' => 700,
            'color_plane' => 'magenta',
            'color_path' => 'lightpink',
            'origin_latitude' => 41.8781,
            'origin_longitude' => -87.6298,
            'destination_latitude' => 21.1619,
            'destination_longitude' => -86.8515,
            'specifics' => [], // Example of a flight that has mmediate departure
            'status' => 'scheduled', 
            'is_delayed' => false,
        ],
        [
            'flight_number' => 'FL009',
            'airline' => 'Airline I',
            'origin' => 'London',
            'destination' => 'Dublin',
            'speed' => 700,
            'color_plane' => 'darkblue',
            'color_path' => 'skyblue',
            'origin_latitude' => 51.5074,
            'origin_longitude' => -0.1278,
            'destination_latitude' => 53.3498,
            'destination_longitude' => -6.2603,
            'specifics' => [ // example of a flight that will start at 25% completion of the route
                'type' => 'on_route',
                'value' => 25,
            ],
            'status' => 'scheduled', 
            'is_delayed' => true,
        ],
        [
            'flight_number' => 'FL010',
            'airline' => 'Airline J',
            'origin' => 'London',
            'destination' => 'Paris',
            'speed' => 700,
            'color_plane' => 'teal',
            'color_path' => 'aqua',
            'origin_latitude' => 51.5074,
            'origin_longitude' => -0.1278,
            'destination_latitude' => 48.8566,
            'destination_longitude' => 2.3522,
            'specifics' => [ // example of a flight that will start at 60% completion of the route
                'type' => 'on_route',
                'value' => 60,
            ],
            'status' => 'scheduled', 
            'is_delayed' => true,
        ],
        [
            'flight_number' => 'FL011',
            'airline' => 'Airline A',
            'origin' => 'Tokyo',
            'destination' => 'Melbourne',
            'speed' => 800, // km/h
            'color_plane' => 'blue',
            'color_path' => 'lightblue',
            'origin_latitude' => 35.6895,
            'origin_longitude' => 139.6917,
            'destination_latitude' => -37.8136,
            'destination_longitude' => 144.9631,
            'specifics' => [ // example of a flight to depart in 3 minute
                'type' => 'future',
                'value' => 3,
            ],
            'status' => 'scheduled', 
            'is_delayed' => true,
        ],
        [
            'flight_number' => 'FL012',
            'airline' => 'Airline B',
            'origin' => 'Batangas City',
            'destination' => 'Silay',
            'speed' => 850, 
            'color_plane' => 'red',
            'color_path' => 'pink',
            'origin_latitude' => 13.7565,
            'origin_longitude' => 121.0583,
            'destination_latitude' => 10.7533,
            'destination_longitude' => 123.0048,
            'specifics' => [], // Example of a flight that has mmediate departure
            'status' => 'scheduled', 
            'is_delayed' => false,
        ],
        [
            'flight_number' => 'FL013',
            'airline' => 'Airline C',
            'origin' => 'Ciudad de México',
            'destination' => 'Havana',
            'speed' => 800, 
            'color_plane' => 'green',
            'color_path' => 'lightgreen',
            'origin_latitude' => 19.4326,
            'origin_longitude' => -99.1332,
            'destination_latitude' => 23.1136,
            'destination_longitude' => -82.3666,
            'specifics' => [],
            'status' => 'in_progress', // On the route already
            'is_delayed' => false,
        ],
        [
            'flight_number' => 'FL014',
            'airline' => 'Airline D',
            'origin' => 'Cape Town',
            'destination' => 'Recife',
            'speed' => 750,
            'color_plane' => 'yellow',
            'color_path' => 'orange',
            'origin_latitude' => -33.9249,
            'origin_longitude' => 18.4241,
            'destination_latitude' => -8.0476,
            'destination_longitude' => -34.8770,
            'specifics' => [ // example of a flight that will start at 32% completion of the route
                'type' => 'on_route',
                'value' => 32,
            ],
            'status' => 'in_progress', // On the route already
            'is_delayed' => false,
        ],
        [
            'flight_number' => 'FL015',
            'airline' => 'Airline E',
            'origin' => 'Buenos Aires',
            'destination' => 'Santiago',
            'speed' => 500, 
            'color_plane' => 'purple',
            'color_path' => 'violet',
            'origin_latitude' => -34.6037,
            'origin_longitude' => -58.3816,
            'destination_latitude' => -33.4489,
            'destination_longitude' => -70.6693,
            'specifics' => [], // Example of a flight that has mmediate departure
            'status' => 'scheduled',
            'is_delayed' => false,
        ],
        [
            'flight_number' => 'FL016',
            'airline' => 'Airline F',
            'origin' => 'New York',
            'destination' => 'Buenos Aires',
            'speed' => 800, // km/h
            'color_plane' => 'cyan',
            'color_path' => 'lightcyan',
            'origin_latitude' => 40.7128,
            'origin_longitude' => -74.0060,
            'destination_latitude' => -34.6037,
            'destination_longitude' => -70.6693,
            'specifics' => [ // example of a flight to depart in 5 minute
                'type' => 'future',
                'value' => 5,
            ],
            'status' => 'in_progress', // On the route already
            'is_delayed' => false,
        ],
        [
            'flight_number' => 'FL017',
            'airline' => 'Airline G',
            'origin' => 'Curitiba',
            'destination' => 'Recife',
            'speed' => 800,
            'color_plane' => 'brown',
            'color_path' => 'tan',
            'origin_latitude' => -25.4296,
            'origin_longitude' => -49.2717,
            'destination_latitude' => -8.0476,
            'destination_longitude' => -34.8770,
            'specifics' => [],
            'status' => 'scheduled', 
            'is_delayed' => false,
        ],
        [
            'flight_number' => 'FL018',
            'airline' => 'Airline H',
            'origin' => 'Cancun',
            'destination' => 'Paris',
            'speed' => 700,
            'color_plane' => 'magenta',
            'color_path' => 'lightpink',
            'origin_latitude' => 21.1619,
            'origin_longitude' => -86.8515,
            'destination_latitude' => 48.8566,
            'destination_longitude' => 2.3522,
            'specifics' => [ // example of a flight that will start at 40% completion of the route
                'type' => 'on_route',
                'value' => 40,
            ],
            'status' => 'scheduled', 
            'is_delayed' => true,
        ],
        [
            'flight_number' => 'FL019',
            'airline' => 'Airline I',
            'origin' => 'Lisboa',
            'destination' => 'Luanda',
            'speed' => 700,
            'color_plane' => 'darkblue',
            'color_path' => 'skyblue',
            'origin_latitude' => 38.7169,
            'origin_longitude' => -9.1395,
            'destination_latitude' => -8.8390,
            'destination_longitude' => 13.2894,
            'specifics' => [ // example of a flight that will start at 70% completion of the route
                'type' => 'on_route',
                'value' => 70,
            ],
            'status' => 'scheduled', 
            'is_delayed' => true,
        ],
        [
            'flight_number' => 'FL020',
            'airline' => 'Airline J',
            'origin' => 'Cape Town',
            'destination' => 'Antananarivo',
            'speed' => 700,
            'color_plane' => 'teal',
            'color_path' => 'aqua',
            'origin_latitude' => -33.9249,
            'origin_longitude' => 18.4241,
            'destination_latitude' => -18.8792,
            'destination_longitude' => 47.5079,
            'specifics' => [ // example of a flight to depart in 2 minutes
                'type' => 'future',
                'value' => 2,
            ],
            'status' => 'scheduled', 
            'is_delayed' => true,
        ],
        // [
        //     'flight_number' => 'FL021',
        //     'airline' => 'Airline A',
        //     'origin' => 'São Paulo',
        //     'destination' => 'Quito',
        //     'speed' => 800, // km/h
        //     'color_plane' => 'blue',
        //     'color_path' => 'lightblue',
        //     'origin_latitude' => -23.5505,
        //     'origin_longitude' => -46.6333,
        //     'destination_latitude' => -0.1807,
        //     'destination_longitude' => -78.4678,
        //     'specifics' => [],
        //     'status' => 'scheduled', 
        //     'is_delayed' => true,
        // ],
        // [
        //     'flight_number' => 'FL022',
        //     'airline' => 'Airline B',
        //     'origin' => 'New York',
        //     'destination' => 'Calgary',
        //     'speed' => 850, 
        //     'color_plane' => 'red',
        //     'color_path' => 'pink',
        //     'origin_latitude' => 40.7128,
        //     'origin_longitude' => -74.0060,
        //     'destination_latitude' => 51.0447,
        //     'destination_longitude' => -114.0719,
        //     'specifics' => [ // example of a flight to depart in 2 minutes
        //         'type' => 'future',
        //         'value' => 2,
        //     ],
        //     'status' => 'scheduled', 
        //     'is_delayed' => true,
        // ],
        // [
        //     'flight_number' => 'FL023',
        //     'airline' => 'Airline C',
        //     'origin' => 'Calgary',
        //     'destination' => 'London',
        //     'speed' => 800, 
        //     'color_plane' => 'green',
        //     'color_path' => 'lightgreen',
        //     'origin_latitude' => 51.0447,
        //     'origin_longitude' => -114.0719,
        //     'destination_latitude' => 51.5074,
        //     'destination_longitude' => -0.1278,
        //     'specifics' => [ // example of a flight that will start at 23% completion of the route
        //         'type' => 'on_route',
        //         'value' => 23,
        //     ],
        //     'status' => 'in_progress', // On the route already
        //     'is_delayed' => false,
        // ],
        // [
        //     'flight_number' => 'FL024',
        //     'airline' => 'Airline D',
        //     'origin' => 'London',
        //     'destination' => 'Rome',
        //     'speed' => 750,
        //     'color_plane' => 'yellow',
        //     'color_path' => 'orange',
        //     'origin_latitude' => 51.5074,
        //     'origin_longitude' => -0.1278,
        //     'destination_latitude' => 41.9028,
        //     'destination_longitude' => 12.4964,
        //     'specifics' => [ // example of a flight that will start at 78% completion of the route
        //         'type' => 'on_route',
        //         'value' => 78,
        //     ],
        //     'status' => 'in_progress', // On the route already
        //     'is_delayed' => false,
        // ],
        // [
        //     'flight_number' => 'FL025',
        //     'airline' => 'Airline E',
        //     'origin' => 'Tokyo',
        //     'destination' => 'Osaka',
        //     'speed' => 500, 
        //     'color_plane' => 'purple',
        //     'color_path' => 'violet',
        //     'origin_latitude' => 35.6762,
        //     'origin_longitude' => 139.6503,
        //     'destination_latitude' => 34.0522,
        //     'destination_longitude' => 135.5050,
        //     'specifics' => [], // Example of a flight that has mmediate departure
        //     'status' => 'scheduled',
        //     'is_delayed' => false,
        // ],
        // [
        //     'flight_number' => 'FL026',
        //     'airline' => 'Airline F',
        //     'origin' => 'Sydney',
        //     'destination' => 'Melbourne',
        //     'speed' => 800, // km/h
        //     'color_plane' => 'cyan',
        //     'color_path' => 'lightcyan',
        //     'origin_latitude' => -33.8688,
        //     'origin_longitude' => 151.2093,
        //     'destination_latitude' => -37.8136,
        //     'destination_longitude' => 144.9631,
        //     'specifics' => [ // example of a flight that will start at 30% completion of the route
        //         'type' => 'on_route',
        //         'value' => 30,
        //     ],
        //     'status' => 'in_progress', // On the route already
        //     'is_delayed' => false,
        // ],
        // [
        //     'flight_number' => 'FL027',
        //     'airline' => 'Airline G',
        //     'origin' => 'Rome',
        //     'destination' => 'Madrid',
        //     'speed' => 700,
        //     'color_plane' => 'brown',
        //     'color_path' => 'tan',
        //     'origin_latitude' => 41.9028,
        //     'origin_longitude' => 12.4964,
        //     'destination_latitude' => 40.4168,
        //     'destination_longitude' => -3.7038,
        //     'specifics' => [ // example of a flight that will start at 60% completion of the route
        //         'type' => 'on_route',
        //         'value' => 60,
        //     ],
        //     'status' => 'scheduled', 
        //     'is_delayed' => true,
        // ],
        // [
        //     'flight_number' => 'FL028',
        //     'airline' => 'Airline H',
        //     'origin' => 'London',
        //     'destination' => 'Amsterdam',
        //     'speed' => 700,
        //     'color_plane' => 'magenta',
        //     'color_path' => 'lightpink',
        //     'origin_latitude' => 51.5074,
        //     'origin_longitude' => -0.1278,
        //     'destination_latitude' => 52.3676,
        //     'destination_longitude' => 4.9041,
        //     'specifics' => [], // Example of a flight that has mmediate departure
        //     'status' => 'scheduled', 
        //     'is_delayed' => false,
        // ],
        // [
        //     'flight_number' => 'FL029',
        //     'airline' => 'Airline I',
        //     'origin' => 'Paris',
        //     'destination' => 'Zurich',
        //     'speed' => 700,
        //     'color_plane' => 'darkblue',
        //     'color_path' => 'skyblue',
        //     'origin_latitude' => 48.8566,
        //     'origin_longitude' => 2.3522,
        //     'destination_latitude' => 47.3769,
        //     'destination_longitude' => 8.5417,
        //     'specifics' => [ // example of a flight that will start at 45% completion of the route
        //         'type' => 'on_route',
        //         'value' => 45,
        //     ],
        //     'status' => 'scheduled', 
        //     'is_delayed' => true,
        // ],
        // [
        //     'flight_number' => 'FL030',
        //     'airline' => 'Airline J',
        //     'origin' => 'Berlin',
        //     'destination' => 'Vienna',
        //     'speed' => 700,
        //     'color_plane' => 'teal',
        //     'color_path' => 'aqua',
        //     'origin_latitude' => 52.5200,
        //     'origin_longitude' => 13.4050,
        //     'destination_latitude' => 48.2082,
        //     'destination_longitude' => 16.3738,
        //     'specifics' => [ // example of a flight that will start at 16% completion of the route
        //         'type' => 'on_route',
        //         'value' => 16,
        //     ],
        //     'status' => 'scheduled', 
        //     'is_delayed' => true,
        // ],
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