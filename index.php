<?php
session_start();

if (isset($_SESSION['user_id'])) {
    header("Location: products.php");
    exit;
}
?>
<!doctype html>
<html lang="en">

<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">
  <title>Index</title>
</head>

<body>
  <div class="row m-5">
    <div class="col-6">
      <div class="card">
        <div class="card-body text-center">
          <h4>Welcome</h4>
        </div>
      </div>
    </div>
    <div class="col-6">
      <div class="card">
        <div class="card-body text-center">
          <a class="btn btn-secondary" href="register.php">Register</a>
          <a class="btn btn-primary" href="login.php">Login</a>

        </div>
      </div>
    </div>
  </div>
</body>

</html>