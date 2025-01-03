<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class ConfigController extends Controller
{

    /**
     * Get the necessary configuration for the simulation.
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function getConfig(Request $request)
    {
        try {

            $data = [
                'weather_cities' => config('simulation.weather_cities'),
            ];

            return Controller::APIJsonReturn($data, 'success', 200);

        } catch (\Exception $e) {
            return Controller::APIJsonReturn(['errors' => $e->getMessage()], 'error', 500);
        }
    }
}
