<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Services\CustomServiceOpenWeather;
use Illuminate\Support\Facades\Log;

class WeatherController extends Controller
{
    public function __construct(private CustomServiceOpenWeather $service) {
        Log::info("API request from other clients (Non web)");
        Log::info("Request data: ".json_encode($_REQUEST));
    }

    public function getCurrentWeather(Request $request)
    {
        $data = $this->service->getCurrentWeather($request);

        return response()->json($data);
    }

    public function getWeatherForecast(Request $request)
    {
        $data = $this->service->getWeatherForecast($request);

        return response()->json($data);
    }

}
