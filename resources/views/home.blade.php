<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Weather App</title>

    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">

    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">

    <style>
        body {
            min-height: 100vh;
            background: radial-gradient(circle at top, #4e54c8 0, #1b1f3a 45%, #0b0c1a 100%);
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 1.5rem;
            font-family: system-ui, -apple-system, BlinkMacSystemFont, "Segoe UI", sans-serif;
        }

        .weather-card {
            max-width: 600px;
            width: 100%;
            background: #ffffff;
            border-radius: 1.25rem;
            box-shadow: 0 24px 60px rgba(0, 0, 0, 0.35);
            padding: 2.25rem 2.5rem;
            position: relative;
            overflow: hidden;
        }

        .weather-card::before {
            content: "";
            position: absolute;
            inset: 0;
            background: radial-gradient(circle at top right, rgba(78, 84, 200, 0.13), transparent 55%);
            pointer-events: none;
        }

        .weather-header-icon {
            width: 3rem;
            height: 3rem;
            border-radius: 0.85rem;
            background: linear-gradient(135deg, #4e54c8, #8f94fb);
            display: inline-flex;
            align-items: center;
            justify-content: center;
            color: #fff;
            font-size: 1.4rem;
            margin-bottom: 0.75rem;
        }

        .weather-title {
            font-weight: 600;
            letter-spacing: 0.03em;
        }

        .subtitle {
            font-size: 0.9rem;
            font-weight: bold;
            color: #eb6e4b;
        }

        .form-section-label {
            font-size: 0.9rem;
            text-transform: uppercase;
            letter-spacing: 0.12em;
            /* color: #6c757d; */
            font-weight: bold;
            margin-bottom: 0.25rem;
        }

        .form-section-span {
            font-size: 0.7rem;
        }

        .pill-group {
            background: #f5f6ff;
            border-radius: 999px;
            padding: 0.45rem 0.5rem;
            display: inline-flex;
            gap: 0.25rem;
        }

        .pill-group .form-check {
            margin: 0;
            padding: 0.15rem 0.75rem;
            border-radius: 999px;
            cursor: pointer;
            transition: background 0.2s ease, color 0.2s ease;
        }

        .pill-group .form-check-input {
            display: none;
        }

        .pill-group .form-check-label {
            cursor: pointer;
            font-size: 0.9rem;
            color: #4b4f63;
            margin: 0;
        }

        .pill-group .form-check-input:checked + .form-check-label {
            color: #eb6e4b;
        }

        .pill-group .form-check-input:checked ~ .form-check-label,
        .pill-group .form-check-input:checked + .form-check-label {
            color: #eb6e4b;
        }

        .pill-group .form-check-input:checked + .form-check-label,
        .pill-group .form-check-input:checked + .form-check-label::before {
            /* background applied via parent */
        }

        .pill-group .form-check-input:checked + .form-check-label {
            position: relative;
        }

        .pill-group .form-check-input:checked + .form-check-label::before {
            content: "";
            position: absolute;
            inset: -0.35rem -0.8rem;
            border-radius: 999px;
            background: linear-gradient(135deg, #4e54c8, #8f94fb);
            z-index: -1;
        }

        .form-control {
            border-radius: 0.9rem;
            padding-left: 2.6rem;
            border-color: #dde1f3;
            transition: box-shadow 0.15s ease, border-color 0.15s ease, transform 0.1s ease;
        }

        .form-control:focus {
            border-color: #4e54c8;
            box-shadow: 0 0 0 0.16rem rgba(78, 84, 200, 0.25);
            transform: translateY(-1px);
        }

        .input-icon-wrapper {
            position: relative;
        }

        .input-icon-wrapper .input-icon {
            position: absolute;
            inset-block: 0;
            left: 0.9rem;
            display: flex;
            align-items: center;
            color: #7b819a;
            pointer-events: none;
        }

        .form-text {
            font-size: 0.8rem;
            color: #868e96;
        }

        .btn-primary {
            border-radius: 999px;
            font-weight: 600;
            letter-spacing: 0.04em;
            text-transform: uppercase;
            font-size: 0.85rem;
            padding-block: 0.9rem;
            box-shadow: 0 14px 30px rgba(63, 81, 181, 0.35);
            border: none;
            background: linear-gradient(135deg, #4e54c8, #8f94fb);
            transition: transform 0.1s ease, box-shadow 0.1s ease;
        }

        .btn-primary:hover {
            transform: translateY(-1px);
            box-shadow: 0 18px 40px rgba(63, 81, 181, 0.45);
        }

        .btn-primary:active {
            transform: translateY(0);
            box-shadow: 0 10px 22px rgba(63, 81, 181, 0.35);
        }
    </style>
</head>

<body>

    <div class="weather-card">
        <div class="mb-4 text-center">
            <div class="weather-header-icon">
                <i class="fa-solid fa-cloud-sun"></i>
            </div>
            <h2 class="weather-title mb-1">Weather App By Kiran Sone</h2>
        </div>

        <form id="weatherForm" action="#" method="GET">
            <!-- Radio: current vs forecast -->
            <div class="mb-4">
                <div class="form-section-label">Weather type</div>
                <div class="pill-group">
                    <label class="form-check">
                        <input class="form-check-input" type="radio" name="wtype" id="currentWeather" value="1" checked>
                        <span class="form-check-label">
                            <i class="fa-solid fa-temperature-high me-1"></i> Current
                        </span>
                    </label>
                    <label class="form-check">
                        <input class="form-check-input" type="radio" name="wtype" id="forecastWeather" value="2">
                        <span class="form-check-label">
                            <i class="fa-solid fa-cloud-moon-rain me-1"></i> Forecast
                        </span>
                    </label>
                </div>
            </div>

            <!-- City / coordinates input -->
            <div class="mb-4">
                <div class="form-section-label">Location
                    <div class="pill-group">
                        <label class="form-check">
                            <input class="ltype" type="radio" name="ltype" data-id="city" value="city" checked>
                            <span class="form-section-span">City</span>
                        </label>
                        <label class="form-check">
                            <input class="ltype" type="radio" name="ltype" data-id="zip" value="zip">
                            <span class="form-section-span">Pincode</span>
                        </label>
                    </div>
                </div>
                <!-- <label for="location" class="form-label">City name or coordinates</label> -->
                <div class="input-icon-wrapper">
                    <span class="input-icon">
                        <i class="fa-solid fa-location-dot"></i>
                    </span>
                    <input
                        type="text"
                        class="form-control"
                        id="location"
                        name="city"
                        placeholder="e.g. Mumbai"
                        required
                    >
                </div>
                <div class="form-text">
                    Type a city name (like <strong>Mumbai</strong>) or a pincode.
                </div>
            </div>

            <!-- Submit button -->
            <div class="d-grid">
                <button type="submit" class="btn btn-primary btn-lg">
                    <i class="fa-solid fa-magnifying-glass-location me-2"></i>
                    Get weather
                </button>
            </div>
        </form>

        <div class="m-2 text-center">
            <p class="subtitle mb-0">A simplified weather app built on top of OpenWeather API using Laravel</p>
        </div>
    </div>

    <div class="modal" tabindex="-1" id="weatherDataModal">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="weatherResponseTitle">Modal title</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body" id="weatherDataModalBody">
                
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-primary" data-bs-dismiss="modal">Close</button>
            </div>
            </div>
        </div>
    </div>

    <!-- jQuery (compatible with Bootstrap 5) -->
    <script
        src="https://code.jquery.com/jquery-3.7.1.min.js"
        integrity="sha256-/JqT3SQfawRcv/BIHPThkBvs0OEvtFFmqPF/lYI/Cxo="
        crossorigin="anonymous">
    </script>

    <!-- Bootstrap JS (optional, for components that need JS) -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous">
    </script>

    <script>
        $(document).ready(function() {
            // 1. Initialize the Bootstrap modal object once
            const weatherModal = new bootstrap.Modal('#weatherDataModal');

            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });

            $(document).on('change', 'input[name="ltype"]', function() {
                let selection = $(this).data('id'); // 'city' or 'zip'
                let inputField = $('#location');

                if (selection === 'city') {
                    inputField.attr('name', 'city');
                    inputField.attr('type', 'text');
                    inputField.val('');
                    inputField.attr('placeholder', 'Enter city name');
                } else {
                    inputField.attr('name', 'zip');
                    inputField.attr('type', 'number');
                    inputField.val('');
                    inputField.attr('placeholder', 'Enter pincode/zipcode');
                }
            });

            let form = $("#weatherForm");
            form.submit(function(e) {
                e.preventDefault();
                
                $.ajax({
                    method: 'GET',
                    data: form.serialize(),
                    url: "{{ url('/getweatherdata') }}",
                    success: function(response) {
                        if(response) {
                            if($('input[name="wtype"]:checked').val()=='1') {
                                $("#weatherResponseTitle").text("Current Weather Data for " + $('#location').val());
                            }
                            else {
                                $("#weatherResponseTitle").text("Weather Forecast Data for " + $('#location').val());
                            }
                            // Update the body content
                            $("#weatherDataModalBody").html(response);
                            
                            // 2. Use the BS5 instance to show the modal
                            weatherModal.show();
                        }
                    },
                    error: function(xhr) {
                        console.error("Error fetching weather data", xhr.responseText);
                    }
                });
            });
        });
    </script>
</body>

</html>