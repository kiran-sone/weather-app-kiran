<?php
namespace App\Services;
use Illuminate\Support\Facades\Http;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class CustomServiceOpenWeather {
    protected string $apiUrl;
    protected string $apiKey;

    public function __construct(){
        $this->apiUrl = config('app.OPENWEATHER_BASE_URL');
        $this->apiKey = config('app.OPENWEATHER_API_KEY');
    }

    public function getCurrentWeather(Request $request) {
        $query = $this->buildQuery($request);

        $response = Http::get("{$this->apiUrl}/weather", array_merge($query, [
            'appid' => $this->apiKey,
            'units' => 'metric'
        ]));

        Log::info("OpenWeather API endpoint(current): " . $response->effectiveUri());

        if($response->failed()) {
            return response()->json([
                'error' => 'Unable to fetch weather data!'
            ], 400);
        }

        return response()->json(
            $this->getSimpleCurrentWeatherData($response->json())
        );
    }

    private function buildQuery(Request $request) {
        if($request->filled('city')) {
            return ['q' => $request->city];
        }

        if($request->filled(['lat', 'long'])) {
            return [
                'lat' => $request->lat,
                'long' => $request->long,
            ];
        }

        if($request->filled('zip')) {
            return ['zip' => $request->zip.',in'];
        }

        abort(422, 'Invalid location parameters!');
    }

    private function getSimpleCurrentWeatherData(array $data) {
        return array(
            'location' => [
                'city' => $data['name'],
                'country' => $data['sys']['country']
            ],
            'temperature' => [
                'current' => $data['main']['temp'],
                'feels_like' => $data['main']['feels_like'],
                'min' => $data['main']['temp_min'],
                'max' => $data['main']['temp_max'],
            ],
            'humidity' => $data['main']['humidity'],
            'weather' => [
                'main' => $data['weather'][0]['main'],
                'description' => $data['weather'][0]['description'],
                'icon' => $data['weather'][0]['icon'],
            ],
            'wind_speed' => $data['wind']['speed'],
            'timestamp' => now()->toDateTimeString(),
        );
    }

    public function getWeatherForecast(Request $request) {
        $query = $this->buildQuery($request);
        
        $response = Http::get("{$this->apiUrl}/forecast", array_merge($query,  [
            'appid' => $this->apiKey,
            'units' => 'metric'
        ]));

        Log::info("OpenWeather API endpoint(forecast): " . $response->effectiveUri());

        if($response->failed()) {
            return response()->json(['error' => 'Unable to fetch forecast data!']);
        }

        return response()->json(
            $this->getSimpleForecastData($response->json())
        );
    }

    private function getSimpleForecastData(array $data) {
        return collect($data['list'])
            ->take(8) // Next 24 hours (3h interval)
            ->map(fn ($item) => [
                'datetime' => $item['dt_txt'],
                'temp' => $item['main']['temp'],
                'humidity' => $item['main']['humidity'],
                'weather' => $item['weather'][0]['description'],
            ])
            ->values()
            ->all();
    }
}