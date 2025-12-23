<?php

namespace App\Http\Controllers;

use App\Services\CustomServiceOpenWeather;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class HomeController extends Controller
{
    public function __construct(private CustomServiceOpenWeather $service) {}
    
    public function index() {
        return view('home');
    }

    public function showCurrentWeather(Request $request) {
        Log::info("API request from Web");
        Log::info("Request data: ".json_encode($_REQUEST));
        $response['weather_type'] = $request->input('wtype');
        // $response['jsonData'] = '{"location":{"city":"Shirahatti","country":"IN"},"temperature":{"current":27.01,"feels_like":26.43,"min":27.01,"max":27.01},"humidity":30,"weather":{"main":"Clear","description":"clear sky","icon":"01d"},"wind_speed":4.78,"timestamp":"2025-12-23 10:48:32"}';
        if($response['weather_type'] == "1") {
            $apiReponse = $this->service->getCurrentWeather($request);
            $response['apiData'] = $apiReponse->getData(true);
        }
        else {
            $apiReponse = $this->service->getWeatherForecast($request);
            $response['apiData'] = $apiReponse->getData(true);
        }
        // echo "<pre>"; print_r($response);exit;
        return view('weather-data', $response);
    }
}
