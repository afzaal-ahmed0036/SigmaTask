<?php
session_start();
if (isset($_SESSION['user_id'])) {
    header("Location: products.php");
    exit;
}
?>
<!DOCTYPE html>
<html>

<head>
    <title>Register</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">
</head>

<body>
    <div class="row m-5">
        <div class="col-6">
            <div class="card">
                <div class="card-body text-center">
                    <h4>Register</h4>
                    <form method="POST" action="register.php" class="form">
                        <label for="" class="form-label">Name</label>
                        <input class="form-control" type="text" name="name" required>
                        <label for="" class="form-label">Email</label>
                        <input class="form-control" type="email" name="email" required>
                        <label for="" class="form-label">Password</label>
                        <input class="form-control" type="password" name="password" required>
                        <label for="" class="form-label">Confirm Password</label>
                        <input class="form-control" type="password" name="confirm_password" required>
                        <button class="btn btn-primary mt-2" type="submit" name="register">Register</button>
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

    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        $name = trim($_POST['name']);
        $email = trim($_POST['email']);
        $password = trim($_POST['password']);
        $confirm_password = trim($_POST['confirm_password']);
        $errors = [];

        if (empty($name)) {
            $errors[] = "Name is required.";
        } elseif (strlen($name) < 5) {
            $errors[] = "Name must be at least 5 characters long.";
        }

        if (empty($email)) {
            $errors[] = "Email is required.";
        } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $errors[] = "Invalid email format.";
        }

        if (empty($password)) {
            $errors[] = "Password is required.";
        } elseif (strlen($password) < 8) {
            $errors[] = "Password must be at least 8 characters long.";
        }

        if ($password !== $confirm_password) {
            $errors[] = "Passwords do not match.";
        }

        if (empty($errors)) {
            $stmt = $pdo->prepare("SELECT * FROM users WHERE name = :name OR email = :email");
            $stmt->execute(['name' => $name, 'email' => $email]);
            $user = $stmt->fetch();

            if ($user) {
                if ($user['name'] === $name) {
                    $errors[] = "name already exists.";
                }
                if ($user['email'] === $email) {
                    $errors[] = "Email already exists.";
                }
            } else {
                $hashed_password = password_hash($password, PASSWORD_BCRYPT);
                $stmt = $pdo->prepare("INSERT INTO users (name, email, password) VALUES (:name, :email, :password)");
                $stmt->execute([
                    'name' => $name,
                    'email' => $email,
                    'password' => $hashed_password
                ]);
                header("Location: login.php");
                exit;
            }
        }
        foreach ($errors as $error) {
            echo "<p style='color:red;'>$error</p>";
        }
    }
} catch (\Exception $e) {
    echo "<p style='color:red;'>" . $e->getMessage() . "</p>";
}
