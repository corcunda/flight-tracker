<?php
/**
 * Class UpdateFlightPosition
 * 
 * This class is responsible for simulating flight movements by manipulating 
 * flight data every 5 seconds. It calculates intermediate positions of flights 
 * between their origin and destination based on elapsed time and speed, updating 
 * the flight's progress in the database. It also broadcasts updated flight data 
 * for real-time tracking purposes.
 * 
 * Note: This job is designed solely for simulation purposes and does not handle 
 * actual flight data or real-world tracking. The repeated scheduling ensures 
 * the simulation runs continuously.
 */

namespace App\Jobs;

use App\Models\Flight;
use App\Models\FlightPosition;
use App\Events\FlightUpdated;
use App\Services\FlightService;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;
use Vinkla\Hashids\Facades\Hashids;
use App\DTO\FlightDataDTO;
use Spatie\DataTransferObject\Exceptions\UnknownProperties;

class UpdateFlightPosition implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        try {
            $flights = Flight::orderBy('id')->get();
            $broadcastData = [];

            foreach ($flights as $flight) {
                // Check if the flight is delayed and update status to in_progress
                if ($flight->status === 'scheduled' && now()->gte($flight->actual_departure)) {
                    $flight->update(['status' => 'in_progress']);
                    Log::info("Flight {$flight->flight_number} has started moving.");
                }

                // Calculate total distance between origin and destination
                $totalDistance = FlightService::calculateDistance(
                    $flight->origin_latitude,
                    $flight->origin_longitude,
                    $flight->destination_latitude,
                    $flight->destination_longitude
                );

                $timeElapsed = FlightService::getElapsedSeconds($flight);
                $flightDurationInSeconds = ($totalDistance / $flight->speed) * 3600;

                // Calculate progress ratio
                $progressRatio = min($timeElapsed / $flightDurationInSeconds, 1.0);

                // Retrieve the last recorded position before updating the flight's current position
                $lastPosition = FlightPosition::where('flight_id', $flight->id)
                    ->orderBy('id', 'desc')
                    ->first();

                // Calculate current position based on the last position or origin if no position exists
                $updatedCoordinates = FlightService::calculateIntermediatePosition(
                    $lastPosition->latitude ?? $flight->origin_latitude,
                    $lastPosition->longitude ?? $flight->origin_longitude,
                    $flight->destination_latitude,
                    $flight->destination_longitude,
                    $progressRatio
                );

                // Check the remaining distance and update the flight's status
                $distanceToDestination = FlightService::calculateDistance(
                    $updatedCoordinates['latitude'],
                    $updatedCoordinates['longitude'],
                    $flight->destination_latitude,
                    $flight->destination_longitude
                );

                if ($distanceToDestination <= 5 && $flight->status !== 'arrived') {
                    // Change status to 'arrived' if within 5km of the destination
                    $flight->update(['status' => 'arrived']);
                    Log::info("Flight {$flight->flight_number} is about to arrive.");
                }

                if ($distanceToDestination <= 1 && $flight->status !== 'completed') {
                    // Change status to 'completed' if within 1km of the destination
                    $flight->update(['status' => 'completed']);
                    Log::info("Flight {$flight->flight_number} has completed its journey.");

                    // Stop populating flight positions and set the final coordinates to the destination
                    FlightService::updateFlightPosition($flight, [
                        'latitude' => $flight->destination_latitude,
                        'longitude' => $flight->destination_longitude
                    ]);
                }

                // Update flight position if the flight is still in progress or arrived
                if ($flight->status === 'in_progress' || $flight->status === 'arrived' || now()->gte($flight->actual_departure)) {
                    FlightService::updateFlightPosition($flight, $updatedCoordinates);
                }

                // Calculate additional data for broadcasting
                $distanceCovered = FlightService::calculateDistance(
                    $flight->origin_latitude,
                    $flight->origin_longitude,
                    $lastPosition->latitude ?? $flight->origin_latitude,
                    $lastPosition->longitude ?? $flight->origin_longitude
                );

                $distanceRemaining = $totalDistance - $distanceCovered;
                $progressPercentage = ($totalDistance > 0)
                    ? round(($distanceCovered / $totalDistance) * 100, 2)
                    : 0;

                $estimatedTimeRemaining = ($flight->speed > 0) ? ($distanceRemaining / $flight->speed) * 3600 : 0;


                try {
                    // Create DTO for each flight
                    $flightDTO = new FlightDataDTO([
                        'id' => Hashids::encode($flight->id),
                        'flight_number' => $flight->flight_number,
                        'status' => $flight->status,
                        'color_plane' => $flight->color_plane,
                        'color_path' => $flight->color_path,
                        'airline' => $flight->airline,
                        'origin' => $flight->origin,
                        'destination' => $flight->destination,
                        'origin_latitude' => $flight->origin_latitude,
                        'origin_longitude' => $flight->origin_longitude,
                        'destination_latitude' => $flight->destination_latitude,
                        'destination_longitude' => $flight->destination_longitude,
                        'current_latitude' => $updatedCoordinates['latitude'],
                        'current_longitude' => $updatedCoordinates['longitude'],
                        'progress_percentage' => $progressPercentage . '%',
                    ]);
                    $broadcastData[] = $flightDTO->toArray();
                } catch (UnknownProperties $exception) {
                    exit;
                }
                

                // Prepare broadcast data
                // $broadcastData[] = [
                //     'id' => Hashids::encode($flight->id),
                //     'flight_number' => $flight->flight_number,
                //     'status' => $flight->status,
                //     'color_plane' => $flight->color_plane,
                //     'color_path' => $flight->color_path,
                //     'airline' => $flight->airline,
                //     'origin' => $flight->origin,
                //     'destination' => $flight->destination,
                //     'origin_latitude' => $flight->origin_latitude,
                //     'origin_longitude' => $flight->origin_longitude,
                //     'destination_latitude' => $flight->destination_latitude,
                //     'destination_longitude' => $flight->destination_longitude,
                //     'current_latitude' => $lastPosition->latitude ?? $flight->origin_latitude,
                //     'current_longitude' => $lastPosition->longitude ?? $flight->origin_longitude,
                //     // 'estimated_time_remaining' => FlightService::formatTime($estimatedTimeRemaining) ?: 'N/A',
                //     'progress_percentage' => $progressPercentage . '%',
                //     // 'time_elapsed' => FlightService::formatTime($timeElapsed),
                // ];
            }

            // Broadcast the collected data
            broadcast(new FlightUpdated($broadcastData))->toOthers();

            // Schedule the job to repeat every x seconds
            self::dispatch()->delay(now()->addSeconds(config('simulation.simulation_settings.update_interval')));
        } catch (\Exception $e) {
            Log::error("Error updating flight positions: " . $e->getMessage());
        }
    }



}
