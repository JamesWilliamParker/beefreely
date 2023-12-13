<!DOCTYPE html>
<html lang="en">

<head>

    <link rel="stylesheet" href="/smart-home-inc/assets/css/index.css" />
    <link rel="stylesheet" href="/smart-home-inc/assets/css/bootstrap.min.css" />

    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Smart Homes, Inc Currency Converter</title>
</head>

<body>
    <?php include './components/nav.php'; ?>

    <div class="container">

        <h1>Currency Converter</h1>

        <body>
            <h6>USD to Your Currency</h6>
            <form action="currency-converter.php" method="post">
                <label for="amount">Amount:</label>
                <input type="text" id="amount" name="amount" required>

                <label for="currency">To Currency:</label>
                <input type="text" id="currency" name="currency" required>

                <input type="submit" value="Convert">
            </form>

            <!-- Display converted amount here -->

            <?php
            if ($_SERVER["REQUEST_METHOD"] == "POST") {
                $apiKey = 'bac1a71934d474d8c81cc3a2';
                $amount = $_POST['amount'];
                $toCurrency = $_POST['currency'];

                $url = "https://open.er-api.com/v6/latest";
                $response = file_get_contents($url);
                $data = json_decode($response, true);

                // Check if the currency is valid
                if (isset($data['rates'][$toCurrency])) {
                    $conversionRate = $data['rates'][$toCurrency];
                    $convertedAmount = $amount * $conversionRate;
                    echo "<p>Converted Amount: $convertedAmount $toCurrency</p>";
                } else {
                    echo "<p>Invalid currency code. Please enter a valid currency code.</p>";
                }
            }
            ?>


            <script src="/smart-home-inc/assets/js/bootstrap.min.js"></script>

    </div>
</body>

</html>