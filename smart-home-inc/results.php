<!DOCTYPE html>
<html lang="en">

<head>

    <link rel="stylesheet" href="/smart-home-inc/assets/css/index.css" />
    <link rel="stylesheet" href="/smart-home-inc/assets/css/bootstrap.min.css" />

    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Smart Homes, Store Results</title>
</head>

<body>
    <?php include './components/nav.php'; ?>

    <div class="container">


        <!-- Start Google Maps -->

        <?php



        if ($_SERVER["REQUEST_METHOD"] == "POST") {
            // Retrieve user input
            $zipCode = trim($_POST["zipCode"]);
            $radius = $_POST["radius"];
            // Remove all spaces from the zip code
            $zipCode = str_replace(' ', '', $zipCode);

            echo "<script>console.log({ radius: $radius, typeofradius: typeof $radius});</script>";

            // Validate zip code
            if (!isValidZipCode($zipCode)) {
                echo "Invalid Zip Code. Please enter a valid 5-digit zip code.";
                // You might want to redirect back to the form or handle the error in another way
                exit;
            }

            // Validate radius
            if (!isValidRadius($radius)) {
                echo "Invalid Radius. Please enter a valid positive number for the search radius.";
                // You might want to redirect back to the form or handle the error in another way
                exit;
            }




            // TODO: Perform the search based on $zipCode and $radius
            // Might want to use APIs like Google Maps API for geocoding and distance calculation

            // For now, let's just display the entered values
            echo "<h1>List of Stores within $radius miles</h1>";

            echo '<div class="mt-3"> </div>';

            // Dummy data for demonstration purposes for smart homes store locations
            $stores = [
                ['name' => 'Smart Homes Inc Store 1', 'latitude' => 37.7749, 'longitude' => -122.4194],
                ['name' => 'Smart Homes Inc Store 2', 'latitude' => 34.0522, 'longitude' => -118.2437],
                // Add more stores as needed
            ];

            // Display search results in a table
            echo '<table class="table">
            <tr>
                <th>Store Name</th>
                <th>Distance (miles)</th>
            </tr>';

            $storesFound = 0;

            foreach ($stores as $store) {
                // TODO: Calculate distance using the haversine formula or a suitable method
                $distance = calculateDistance($zipCode, $radius, $store['latitude'], $store['longitude']);

                if ($distance !== null) {
                    // Display the row only if distance is within the specified radius
                    echo "<tr>
                <td>{$store['name']}</td>
                <td>$distance miles</td>
                </tr>";
                    // Increase the value of stores found
                    $storesFound = $storesFound + 1;
                }
            }

            if ($storesFound == 0) {
                echo "<tr>
                <td>No Stores Found within the provided radius</td>
                <td>$radius miles</td>
                </tr>";
            }

            echo "</table>";


            // Adding Space (Style)
            echo '<div class="mt-5"> </div>';
            echo "<h3>Map of Store Locations</h3>";

            // Display the map with store locations
            echo "<div id='map' style='height: 400px; border-radius: 20px;'></div>";
            echo "<script>
            function initMap() {
                var map = new google.maps.Map(document.getElementById('map'), {
                    center: {lat: 37.7749, lng: -122.4194}, // Set a default center
                    zoom: 5
                });

                var infowindow = new google.maps.InfoWindow();

                // Add markers for each store
                var stores = " . json_encode($stores) . ";
                stores.forEach(function(store) {
                    var marker = new google.maps.Marker({
                        position: {lat: store.latitude, lng: store.longitude},
                        map: map,
                        title: store.name
                    });

                    // Add info window with store details
                    marker.addListener('click', function() {
                        infowindow.setContent('<strong>' + store.name + '</strong><br>Distance: ' + calculateDistance($zipCode, $radius, store.latitude, store.longitude) + ' miles');
                        infowindow.open(map, marker);
                    });
                });
            }
          </script>";

            // Include Google Maps API script
            echo "<script async defer
            src='https://maps.googleapis.com/maps/api/js?key=AIzaSyCthkQpM5CZHdCAXWhaV2cP_0VjisL6j4o&callback=initMap'>
          </script>";
        }

        // function calculateDistance($zipCode, $radius, $storeLat, $storeLng)
        // {

        //     // TODO: Calculate the Distance
        //     // First Step: What is the Lat/Lng for the center of our $zipCode variable
        //     // Find out if the $zipCode latitude is within $radius distance of the $storeLat
        //     // Find out if the $zipCode longitude is within $radius distance of the $storeLng
        //     // If both conditions above are True, return the distance between the zip code and the store
        //     // https://maps.googleapis.com/maps/api/geocode/json?key=AIzaSyCthkQpM5CZHdCAXWhaV2cP_0VjisL6j4o&address=32137

        //     return rand($radius - 5, $radius + 5);
        // }

        function calculateDistance($zipCode, $radius, $storeLat, $storeLng)
        {
            // Step 1: Get the Latitude and Longitude of the $zipCode using Google Maps API
            $zipCoordinates = getCoordinatesFromGoogleMapsAPI($zipCode);
            echo "<script>console.log('" . json_encode($zipCoordinates) . "');</script>";


            if (!$zipCoordinates) {
                // Handle the case where geocoding fails
                return null;
            }

            // Step 2: Calculate the distance between the two sets of coordinates (in kilometers)
            $distance = calculateDistanceBetweenPoints(
                $zipCoordinates['lat'],
                $zipCoordinates['lng'],
                $storeLat,
                $storeLng
            );

            // Step 3: Check if the distance is within the specified radius
            // Convert the Radius from miles to kilometers

            $radiusInKilometers = 1.60934 * $radius;

            if ($distance <= $radiusInKilometers) {
                // Returns the distance in miles
                // Rounds the miles up
                $distanceInMiles = round($distance * 0.621371, 2);
                return $distanceInMiles;
            } else {
                // If outside the radius, return null or any appropriate value
                return null;
            }
        }

        function getCoordinatesFromGoogleMapsAPI($address)
        {
            // Use Google Maps Geocoding API to get coordinates
            // My API key only works for localhost
            $apiUrl = "https://maps.googleapis.com/maps/api/geocode/json?address=$address&key=AIzaSyCthkQpM5CZHdCAXWhaV2cP_0VjisL6j4o";
            $response = file_get_contents($apiUrl);
            echo "<script>console.log({ response: $response, typeofresponse: typeof $response});</script>";
            $data = json_decode($response, true);

            if ($data['status'] === 'OK' && isset($data['results'][0]['geometry']['location'])) {
                $location = $data['results'][0]['geometry']['location'];
                return ['lat' => $location['lat'], 'lng' => $location['lng']];
            } else {
                return null;
            }
        }

        function calculateDistanceBetweenPoints($lat1, $lng1, $lat2, $lng2)
        {
            // Calculate the distance between two sets of coordinates
            $earthRadius = 6371; // Earth's radius in kilometers

            $dLat = deg2rad($lat2 - $lat1);
            $dLng = deg2rad($lng2 - $lng1);

            $a = sin($dLat / 2) * sin($dLat / 2) + cos(deg2rad($lat1)) * cos(deg2rad($lat2)) * sin($dLng / 2) * sin($dLng / 2);
            $c = 2 * atan2(sqrt($a), sqrt(1 - $a));

            $distance = $earthRadius * $c; // Distance in kilometers

            return $distance;
        }

        // Function to validate zip code format
        function isValidZipCode($zipCode)
        {
            // Add your zip code validation logic here
            // For example, you can use a regular expression to check the format
            return preg_match('/^\d{5}$|^[A-Za-z]\d[A-Za-z]\d[A-Za-z]\d$/', $zipCode);
        }


        // Function to validate radius as a positive number
        function isValidRadius($radius)
        {
            // Check if $radius is a positive number
            return is_numeric($radius) && $radius > 0;
        }



        ?>

    </div>
    <!-- End Google Maps -->

    <script src="/smart-home-inc/assets/js/bootstrap.min.js"></script>
</body>

</html>