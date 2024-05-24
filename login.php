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
    <title>Login</title>
</head>

<body>
    <div class="row m-5">
        <div class="col-12">
            <div class="card">
                <div class="card-body text-center">
                    <h4>Login</h4>
                    <form action="login.php" method="POST">
                        <label for="" class="form-label">Email</label>
                        <input class="form-control" type="email" id="email" name="email" required>
                        <label for="password">Password:</label>
                        <input class="form-control" type="password" id="password" name="password" required>
                        <input class="btn btn-success mt-2" type="submit" value="Login">
                    </form>
                </div>
            </div>
        </div>
    </div>
</body>

</html>
<?php
try {

    include 'db_conn.php';

    // session_start();

    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        $email = trim($_POST['email']);
        $password = trim($_POST['password']);
        $errors = [];

        if (empty($email)) {
            $errors[] = "Email is required.";
        } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $errors[] = "Invalid email format.";
        }

        if (empty($password)) {
            $errors[] = "Password is required.";
        }

        if (empty($errors)) {
            $stmt = $pdo->prepare("SELECT * FROM users WHERE email = :email");
            $stmt->execute(['email' => $email]);
            $user = $stmt->fetch();

            if ($user && password_verify($password, $user['password'])) {
                $_SESSION['user_id'] = $user['id'];
                $_SESSION['name'] = $user['name'];
                $_SESSION['email'] = $user['email'];

                header("Location: products.php");
                exit;
            } else {
                $errors[] = "Invalid email or password.";
            }
        }

        foreach ($errors as $error) {
            echo "<p style='color:red;'>$error</p>";
        }
    }
} catch (\Exception $e) {
    echo "<p style='color:red;'>" . $e->getMessage() . "</p>";
}
?>