<?php

namespace App\Services;

use App\Models\Flight;
use App\Models\FlightPosition;
use Illuminate\Support\Carbon;

class FlightService
{

    /**
     * Creates a set of predefined flight routes with dynamic behaviors such as delays and progress on route.
     *
     * This method defines flight data including origin, destination, speed, and specific details like delays 
     * or the percentage of the route completed. Based on the flight's specific attributes, the method calculates 
     * and adjusts flight departure times and positions, then inserts the data into the database.
     * 
     * The flight creation process handles the following:
     * 1. Calculating the total distance between the origin and destination.
     * 2. Estimating the flight duration based on speed and distance.
     * 3. Applying adjustments to the departure time (e.g., for delays or already being on route).
     * 4. Inserting flight details into the database.
     * 5. Handling the calculation and insertion of the flight’s position when it's "on route."
     * 
     * Each flight route is inserted into the 'flights' table, and if the flight is "on route," the position is also 
     * tracked in the 'flight_positions' table for real-time tracking.
     * 
     * @return void
     */
    public static function createFlightRoutes()
    {
    
        // Define flight data with 'specifics' for dynamic behavior
        $flightsData = [
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
                'specifics' => [ // example of a flight that will start at 90% completion of the route
                    'type' => 'on_route',
                    'value' => 99,
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
        ];
    
        // For each flight route, adjust the departure times based on 'specifics'
        foreach ($flightsData as $flightData) {

            // Simulation start time (UTC)
            $simulationStartTime = now();

            // Calculate distance and estimated duration
            $totalDistance = self::calculateDistance(
                $flightData['origin_latitude'],
                $flightData['origin_longitude'],
                $flightData['destination_latitude'],
                $flightData['destination_longitude']
            );
            
            $estimatedDuration = ($totalDistance / $flightData['speed']) * 3600; // duration in seconds
    
            // Determine scheduled departure time (simulation start)
            $scheduledDeparture = $simulationStartTime->copy();
            
            // Apply specific actions based on 'specifics' type
            $actualDeparture = $simulationStartTime->copy();
            if (isset($flightData['specifics']['type'])) {
                switch ($flightData['specifics']['type']) {
                    case 'future':
                        // Delay by the specified value in minutes
                        $actualDeparture = $actualDeparture->addMinutes($flightData['specifics']['value']);
                        break;
    
                    case 'on_route':
                        // Set actual departure to simulate already being on the route
                        $percentage = $flightData['specifics']['value'];
                        $timeIntoRoute = ($estimatedDuration * $percentage) / 100;
                        $actualDeparture = $simulationStartTime->addSeconds($timeIntoRoute);
                        
                        // Calculate position based on percentage of the route
                        $latitude = self::calculateLatitudeAtPosition($flightData['origin_latitude'], $flightData['destination_latitude'], $percentage);
                        $longitude = self::calculateLongitudeAtPosition($flightData['origin_longitude'], $flightData['destination_longitude'], $percentage);
                        break;
    
                    default:
                        break;
                }
            }
    
            // Insert the flight into the database
            $flight = Flight::create([
                'flight_number' => $flightData['flight_number'],
                'airline' => $flightData['airline'],
                'origin' => $flightData['origin'],
                'destination' => $flightData['destination'],
                'speed' => $flightData['speed'],
                'color_plane' => $flightData['color_plane'],
                'color_path' => $flightData['color_path'],
                'origin_latitude' => $flightData['origin_latitude'],
                'origin_longitude' => $flightData['origin_longitude'],
                'destination_latitude' => $flightData['destination_latitude'],
                'destination_longitude' => $flightData['destination_longitude'],
                'scheduled_departure' => $scheduledDeparture,
                'scheduled_arrival' => $scheduledDeparture->copy()->addSeconds($estimatedDuration),
                'actual_departure' => $actualDeparture,
                'actual_arrival' => $actualDeparture->copy()->addSeconds($estimatedDuration),
                'status' => $flightData['status'],
                'is_delayed' => $flightData['is_delayed'],
                'distance' => $totalDistance,
                'estimated_duration' => $estimatedDuration,
            ]);

            // Manually update created_at after the flight is created. This is essential for the simulation logic used on @getElapsedSeconds.
            if (isset($flightData['specifics']['type']) && $flightData['specifics']['type'] === 'on_route' && $flightData['specifics']['value'] > 0) {
                $onRouteData = [
                    'speed' => $flightData['speed'],
                    'origin_latitude' => $flightData['origin_latitude'],
                    'origin_longitude' => $flightData['origin_longitude'],
                    'destination_latitude' => $flightData['destination_latitude'],
                    'destination_longitude' => $flightData['destination_longitude'],
                    'percentage' => $flightData['specifics']['value'],
                ];
                $flight->created_at =self::calculateCreatedAtForFlight($onRouteData);
            } else if (isset($flightData['specifics']['type']) && $flightData['specifics']['type'] === 'future' && $flightData['specifics']['value'] > 0) {
                $flight->created_at = $simulationStartTime->copy()->addMinutes($flightData['specifics']['value']);
            } else {
                $flight->created_at = $simulationStartTime->copy();
            }
            $flight->save();
    
            // After flight is created, insert position if the flight is on_route
            if (isset($flightData['specifics']['type']) && $flightData['specifics']['type'] === 'on_route') {
                $latitude = self::calculateLatitudeAtPosition($flightData['origin_latitude'], $flightData['destination_latitude'], $flightData['specifics']['value']);
                $longitude = self::calculateLongitudeAtPosition($flightData['origin_longitude'], $flightData['destination_longitude'], $flightData['specifics']['value']);
                
                // Insert into the flight_position
                FlightPosition::create([
                    'flight_id' => $flight->id,
                    'latitude' => $latitude,
                    'longitude' => $longitude,
                ]);
            }
        }
    }
    

    /**
     * Calculate the distance between two geographical points using the Haversine formula.
     * 
     * @param float $lat1 Latitude of the first point.
     * @param float $lon1 Longitude of the first point.
     * @param float $lat2 Latitude of the second point.
     * @param float $lon2 Longitude of the second point.
     * @return float The calculated distance in kilometers.
     */
    public static function calculateDistance($lat1, $lon1, $lat2, $lon2)
    {
        $earthRadius = 6371; // Earth's radius in kilometers

        $latDelta = deg2rad($lat2 - $lat1);
        $lonDelta = deg2rad($lon2 - $lon1);

        $a = sin($latDelta / 2) * sin($latDelta / 2) +
            cos(deg2rad($lat1)) * cos(deg2rad($lat2)) *
            sin($lonDelta / 2) * sin($lonDelta / 2);

        $c = 2 * atan2(sqrt($a), sqrt(1 - $a));

        return $earthRadius * $c;
    }


    /**
     * Retrieve the total elapsed time for a flight in seconds.
     * 
     * @param Flight $flight The flight instance being processed.
     * @return int The total elapsed time in seconds since the flight's first recorded position.
     */
    public static function getElapsedSeconds(Flight $flight)
    {
        // $firstPositionTime = FlightPosition::where('flight_id', $flight->id)
        //     ->orderBy('created_at', 'asc')
        //     ->value('created_at');

        // return $firstPositionTime
        //     ? now()->diffInSeconds($firstPositionTime)
        //     : 0;

        // If the flight is already in progress, use the 'created_at' of the flight to calculate the elapsed time
        $startTime = $flight->created_at; // Use the created_at of the flight to simulate the start of the journey

        // Calculate elapsed time in seconds
        return $startTime ? now()->diffInSeconds($startTime) : 0;

    }


    /**
     * Calculate the intermediate position of a flight based on its progress ratio.
     * 
     * @param float $lat1 The latitude of the origin.
     * @param float $lon1 The longitude of the origin.
     * @param float $lat2 The latitude of the destination.
     * @param float $lon2 The longitude of the destination.
     * @param float $progressRatio The progress ratio (0 to 1) of the flight.
     * @return array The calculated latitude and longitude of the flight's current position.
     */
    public static function calculateIntermediatePosition($lat1, $lon1, $lat2, $lon2, $progressRatio)
    {
        return [
            'latitude' => $lat1 + ($lat2 - $lat1) * $progressRatio,
            'longitude' => $lon1 + ($lon2 - $lon1) * $progressRatio,
        ];
    }


    /**
     * Update the current position of a flight in the database.
     * 
     * @param Flight $flight The flight instance being updated.
     * @param array $coordinates The new latitude and longitude for the flight.
     */
    public static function updateFlightPosition(Flight $flight, $coordinates)
    {
        FlightPosition::create([
            'flight_id' => $flight->id,
            'latitude' => $coordinates['latitude'],
            'longitude' => $coordinates['longitude'],
            'updated_at' => now(),
        ]);
    }


    /**
     * Format a time duration (in seconds) into a string in the format H:i:s.
     * 
     * @param int $time The time duration in seconds.
     * @return string The formatted time string.
     */
    public static function formatTime($time)
    {
        $hours = floor($time / 3600);
        $minutes = floor(($time % 3600) / 60);
        $seconds = $time % 60;

        return sprintf("%02d:%02d:%02d", $hours, $minutes, $seconds);
    }


    /**
     * Calculate the latitude at a specific position along a flight's route.
     * This method determines the intermediate latitude between the origin and destination
     * based on the given percentage of the route completed.
     *
     * @param float $originLatitude The latitude of the flight's origin.
     * @param float $destinationLatitude The latitude of the flight's destination.
     * @param float $percentage The percentage of the route completed (0 to 100).
     * 
     * @return float The calculated latitude at the given position along the route.
     */
    private static function calculateLatitudeAtPosition($originLatitude, $destinationLatitude, $percentage)
    {
        return $originLatitude + (($destinationLatitude - $originLatitude) * ($percentage / 100));
    }

    
    /**
     * Calculate the longitude at a specific position along a flight's route.
     * This method determines the intermediate longitude between the origin and destination
     * based on the given percentage of the route completed.
     *
     * @param float $originLongitude The longitude of the flight's origin.
     * @param float $destinationLongitude The longitude of the flight's destination.
     * @param float $percentage The percentage of the route completed (0 to 100).
     * 
     * @return float The calculated longitude at the given position along the route.
     */
    private static function calculateLongitudeAtPosition($originLongitude, $destinationLongitude, $percentage)
    {
        return $originLongitude + (($destinationLongitude - $originLongitude) * ($percentage / 100));
    }


    /**
     * Calculate the "created_at" timestamp for a flight based on its speed, origin, destination, and progress percentage
     * to simulate flights that starts at some point of the route.
     *
     * This method computes the elapsed time for a flight at a given progress percentage, adjusting it dynamically
     * according to the flight's distance and speed. The "created_at" timestamp is determined by reversing the
     * calculated elapsed time from the current time.
     *
     * Key Steps:
     * 1. Calculate the total distance between the origin and destination coordinates.
     * 2. Compute the total time it would take to cover the entire distance at the specified speed.
     * 3. Calculate the elapsed time based on the given progress percentage.
     * 4. Adjust the elapsed time using a scaling factor derived from the time it would take to complete 80% of the journey.
     * 5. Subtract the adjusted elapsed time from the current timestamp to estimate when the flight was created.
     *
     * @param array $flightData An array containing flight parameters:
     *                          - 'speed': The speed of the flight in km/h.
     *                          - 'origin_latitude': Latitude of the origin.
     *                          - 'origin_longitude': Longitude of the origin.
     *                          - 'destination_latitude': Latitude of the destination.
     *                          - 'destination_longitude': Longitude of the destination.
     *                          - 'percentage': The percentage of the flight completed (0-100%).
     *
     * @return \Carbon\Carbon The calculated "created_at" timestamp.
     */
    private static function calculateCreatedAtForFlight(array $flightData)
    {
        // Flight data
        $speed = $flightData['speed'];  // speed in km/h
        $originLatitude = $flightData['origin_latitude'];
        $originLongitude = $flightData['origin_longitude'];
        $destinationLatitude = $flightData['destination_latitude'];
        $destinationLongitude = $flightData['destination_longitude'];
        $progressPercentage = $flightData['percentage'];  // e.g. 50%, 80%

        // Calculate total distance between origin and destination (in km)
        $totalDistance = self::calculateDistance($originLatitude, $originLongitude, $destinationLatitude, $destinationLongitude);

        // Duration to travel the entire distance at the given speed (in seconds)
        $totalTimeInSeconds = ($totalDistance / $speed) * 3600;

        // Calculate the elapsed time based on the progress percentage
        $elapsedTimeInSeconds = ($progressPercentage / 100) * $totalTimeInSeconds;

        // Normalize the time so that for 80% completion, it gives the correct value (6 minutes for Berlin-to-Rome)
        // Calculate the elapsed time for 80% progress and adjust for the dynamic scaling.
        $elapsedTimeFor80Percent = ($totalTimeInSeconds * 0.8);
        $scaleFactor = 360 / $elapsedTimeFor80Percent;

        // Adjust the elapsed time based on the progress percentage
        $adjustedElapsedTimeInSeconds = $elapsedTimeInSeconds * $scaleFactor;

        // Subtract the adjusted elapsed time from the current time to get the "created_at" time
        $createdAt = Carbon::now()->subSeconds($adjustedElapsedTimeInSeconds);

        return $createdAt;
    }



}