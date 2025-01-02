<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Flight;
use App\Models\FlightPosition;
use App\Services\FlightService;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use App\Jobs\UpdateFlightPosition;

class FlightController extends Controller
{

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request     $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $request->validate($this->rules());

        try {

            $flight = Flight::create([
                'flight_number' => trim($request->flight_number),
                'airline' => trim($request->airline),
                'origin' => trim($request->origin),
                'destination' => trim($request->destination),
                'speed' => $request->speed,
                'color_plane' => $request->color_plane,
                'color_path' => $request->color_path,
                'origin_latitude' => $request->origin_latitude,
                'origin_longitude' => $request->origin_longitude,
                'destination_latitude' => $request->destination_latitude,
                'destination_longitude' => $request->destination_longitude,
                'scheduled_departure' => $request->scheduled_departure,
                'scheduled_arrival' => $request->scheduled_arrival,
                'status' => $request->status,
            ]);

            // Prepare response data
            $data = [
                'flight' => $flight,
            ];

            return Controller::APIJsonReturn($data, 'success', 201);

        } catch (\Exception $e) {
            return Controller::APIJsonReturn(['errors' => $e->getMessage()], 'error', 500);
        }
    }


    /**
     * 
     */
    public static function simulateStart()
    {
        // 1 - Stop running queue workers and truncate the jobs table
        try {
            // Gracefully stop all running queue workers
            exec('pkill -f "php artisan queue:work"');

            // Truncate the jobs table to clear pending jobs
            DB::table('jobs')->truncate();

            // Clear any started or stuck jobs from the `failed_jobs` table
            if (Schema::hasTable('failed_jobs')) {
                DB::table('failed_jobs')->truncate();
            }
        } catch (\Exception $e) {
            return response()->json(['error' => 'Failed to stop jobs or truncate job tables: ' . $e->getMessage()], 500);
        }

        // 2 - Update all flight statuses to 'in_progress'
        try {
            // DB::table('flights')->update(['status' => 'in_progress']);
            DB::table('flights')->truncate();
        } catch (\Exception $e) {
            return response()->json(['error' => 'Failed to truncate flight statuses: ' . $e->getMessage()], 500);
        }

        // 3 - Truncate the flight_positions table
        try {
            DB::table('flight_positions')->truncate();
        } catch (\Exception $e) {
            return response()->json(['error' => 'Failed to truncate flight_positions table: ' . $e->getMessage()], 500);
        }

        FlightService::createFlightRoutes();

        // 4 - Dispatch the UpdateFlightPosition job
        try {
            UpdateFlightPosition::dispatch();
        } catch (\Exception $e) {
            return response()->json(['error' => 'Failed to dispatch UpdateFlightPosition job: ' . $e->getMessage()], 500);
        }

        // 5 - Restart the queue workers to process new jobs
        try {
            exec('php artisan queue:work --daemon --tries=3 > /dev/null 2>&1 &');
        } catch (\Exception $e) {
            return response()->json(['error' => 'Failed to restart queue workers: ' . $e->getMessage()], 500);
        }

        return response()->json(['success' => 'Simulation started successfully.']);
    }


    /**
     * Validation rules
     */
    public function rules()
    {
        return [
            'flight_number' => 'required|string|max:255',
            'airline' => 'nullable|string|max:255',
            'origin' => 'required|string|max:255',
            'destination' => 'required|string|max:255',
            'speed' => 'required|numeric',
            'origin_latitude' => 'required|numeric',
            'origin_longitude' => 'required|numeric',
            'destination_latitude' => 'required|numeric',
            'destination_longitude' => 'required|numeric',
            'scheduled_departure' => 'nullable|date',
            'scheduled_arrival' => 'nullable|date',
        ];
    }

}
