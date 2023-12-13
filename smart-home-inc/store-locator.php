<!DOCTYPE html>
<html lang="en">

<head>

    <link rel="stylesheet" href="/smart-home-inc/assets/css/index.css" />
    <link rel="stylesheet" href="/smart-home-inc/assets/css/bootstrap.min.css" />

    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Smart Homes, Inc Store Locator</title>
</head>

<body>
    <?php include './components/nav.php'; ?>

    <div class="container">

        <h1>Store Locator</h1>

        <h3>Search by Zip Code and Radius</h3>

        <form action="results.php" method="post">
            <div class="input-group mb-3">
                <label class="input-group-text" for="zipCode">Enter Zip Code:</label>
                <input class="form-control" type="text" id="zipCode" name="zipCode" required>
            </div>

            <div class="input-group mb-3">
                <label class="input-group-text" for="radius">Search Radius (miles):</label>
                <input class="form-control" type="number" id="radius" name="radius" required>
            </div>

            <input class="btn btn-primary" type="submit" value="Search">
        </form>
        <script src="/smart-home-inc/assets/js/bootstrap.min.js"></script>

    </div>
</body>

</html>