<!DOCTYPE html>
<html lang="en">

<head>
  <link rel="stylesheet" href="/smart-home-inc/assets/css/index.css" />
  <link rel="stylesheet" href="/smart-home-inc/assets/css/bootstrap.min.css" />

  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Smart Homes, Inc.</title>
</head>

<body>
  <?php include './components/nav.php'; ?>

  <div class="container">

    <h1>Tweet About Our Products</h1>
    <h3>Join the club, all the kids are doing it!</h3>

    <form action="social-result.php" method="post">
      <div class="input-group mb-3 w100">
        <label class="input-group-text w100" for="tweet-text">Tweet Message</label>
      </div>

      <div class="input-group mb-3 w100">
        <input type="text" class="w100" id="tweet-text" name="tweet" placeholder="I really love smart homes inc devices">
        </input>
      </div>

      <div class=" input-group mb-3 w100">
        <button type="submit" class="btn btn-primary w100">SUBMIT</button>
      </div>
    </form>

    <script src="/smart-home-inc/assets/js/bootstrap.min.js"></script>

  </div>
</body>

</html>