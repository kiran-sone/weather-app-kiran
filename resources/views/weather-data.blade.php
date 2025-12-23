<?php
// $jsonData = '{"location":{"city":"Shirahatti","country":"IN"},"temperature":{"current":27.01,"feels_like":26.43,"min":27.01,"max":27.01},"humidity":30,"weather":{"main":"Clear","description":"clear sky","icon":"01d"},"wind_speed":4.78,"timestamp":"2025-12-23 10:48:32"}';
// echo "<pre>SSS"; print_r($apiData);exit;

if (!empty($apiData)) {
    renderTable($apiData, $weather_type);
} else {
    echo '<div class="p-3 text-danger">No data available or Invalid JSON data provided!</div>';
}

function renderTable($array, $weather_type) {
    ?>
    <table class="table table-bordered table-hover nested-table">
    <?php
    if($weather_type == '1') {
        foreach ($array as $key => $value) {
            echo '<tr>';
            echo '<td class="table-key text-capitalize">' . str_replace('_', ' ', $key) . '</td>';
            echo '<td>';
            
            if (is_array($value)) {
                // If the value is an array/object, call the function recursively
                renderTable($value, $weather_type);
            } else {
                // If it's a leaf node, just display the value
                if (is_bool($value)) {
                    echo $value ? '<span class="badge bg-success">True</span>' : '<span class="badge bg-danger">False</span>';
                } elseif (is_null($value)) {
                    echo '<span class="text-muted">null</span>';
                } else {
                    echo htmlspecialchars($value);
                }
            }
            
            echo '</td>';
            echo '</tr>';
        }
    }
    else {
        // echo "<pre>"; print_r($array);exit;
        ?>
        <tr>
            <th>#</th>
            <th>Date/Time</th>
            <th>Temperature</th>
            <th>Humidity</th>
            <th>Weather</th>
        </tr>
        <?php
        foreach ($array as $key => $value) {
            ?>
            <tr>
                <td><?php if(is_numeric($key)) echo $key+1; ?></td>
                <td><?php echo $value['datetime']; ?></td>
                <td><?php echo $value['temp']; ?></td>
                <td><?php echo $value['humidity']; ?></td>
                <td><?php echo $value['weather']; ?></td>
            </tr>
            <?php
        }
    }
    ?>
    </table>
    <?php
}
?>