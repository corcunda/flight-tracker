<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use App\DTO\WeatherDataDTO;

class WeatherController extends Controller
{
    private $apiKey;

    public function __construct()
    {
        $this->apiKey = env('OPENWEATHER_API_KEY', 'default_api_key');
    }

    /**
     * Get weather data for a given city.
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function getWeather(Request $request)
    {
        // Validate the city parameter
        $validated = $request->validate([
            'city' => 'required|string|max:100',
            'country' => 'nullable|string|size:2', // Country code (2 letters)
        ]);

        $city = $validated['city'];
        $country = $request->country;

        try {

            $query = $country ? "{$city},{$country}" : $city;

            $response = Http::get("https://api.openweathermap.org/data/2.5/weather", [
                'q' => $query,
                'units' => 'metric', // Fetch temperature in Celsius
                'appid' => $this->apiKey,
            ]);

            // Check if the response is successful
            if ($response->successful()) {
                // Extract useful data from the response
                $resp = $response->json();

                $data = new WeatherDataDTO([
                    'name' => $resp['name'],
                    'country' => $resp['sys']['country'], // Extract country from the API response
                    'temperature' => round($resp['main']['temp'], 1), // Round to 1 decimal
                    'description' => $resp['weather'][0]['description'],
                    'humidity' => $resp['main']['humidity'], // Added humidity for more detail
                    'wind_speed' => $resp['wind']['speed'], // Added wind speed
                    'icon' => 'https://openweathermap.org/img/wn/'.$resp['weather'][0]['icon'].'.png', // Weather icon code
                    'local_time' => date('H:i', time() + $resp['timezone']),
                ]);

                return Controller::APIJsonReturn($data, 'success', 200);

            }

            return Controller::APIJsonReturn(['errors' => $response->json()], 'error', $response->status());

        } catch (\Exception $e) {
            return Controller::APIJsonReturn(['errors' => $e->getMessage()], 'error', 500);
        }
    }
}
