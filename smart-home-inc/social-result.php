<!DOCTYPE html>
<html lang="en">

<head>

    <link rel="stylesheet" href="/smart-home-inc/assets/css/index.css" />
    <link rel="stylesheet" href="/smart-home-inc/assets/css/bootstrap.min.css" />

    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Smart Homes Inc, Social</title>
</head>

<body>
    <?php include './components/nav.php'; ?>

    <div class="container">

        <h1>Thank you for your tweet!</h1>

        <?php

        if ($_SERVER["REQUEST_METHOD"] == "POST") {
            // Retrieve user input
            $tweet = $_POST["tweet"];

            echo "<script>console.log({tweet: '$tweet'});</script>";
            require_once './vendor/autoload.php';

            $request = new HTTP_Request2();
            $request->setUrl('https://api.twitter.com/2/tweets');
            $request->setMethod(HTTP_Request2::METHOD_POST);
            $request->setConfig(array(
                'follow_redirects' => TRUE
            ));
            $request->setHeader(array(
                'Content-Type' => 'application/json',
                'Authorization' => 'OAuth oauth_consumer_key="eHpvWHp6c3BKUFZPRm1uUF9ITms6MTpjaQ",oauth_token="1718297822418644992-H1Er39gD7h7b0XZyU7yTWd5s2nG17y",oauth_signature_method="HMAC-SHA1",oauth_timestamp="1698526612",oauth_nonce="OmaCEVyJETb",oauth_version="1.0",oauth_signature="lSnw0zmz7YfV7qSLilNiiC2QFgY%3D"'
            ));
            $request->setBody("{\n    \"text\": \"$tweet\"\n}");
            try {
                $response = $request->send();
                if ($response->getStatus() == 200) {
                    echo "<h2>Tweet Submitted</h2>";
                } else {
                    echo "<h2>Tweet Submitted</h2>";
                    echo 'Unexpected HTTP status: ' . $response->getStatus() . ' ' .
                        $response->getReasonPhrase();
                }
            } catch (HTTP_Request2_Exception $e) {
                echo 'Error: ' . $e->getMessage();
            }
        }

        ?>

    </div>


    <script src="/smart-home-inc/assets/js/bootstrap.min.js"></script>
</body>

</html>