<?php
session_start();
if (!isset($_SESSION['user_id'])) {
    header("Location: index.php");
    exit;
}
?>
<!DOCTYPE html>
<html>

<head>
    <title>Create Product</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">
</head>

<body>
<header>
        <div class="row m-2">
            <div class="col d-flex justify-content-end">
                <a class="btn btn-primary me-2" href="cart.php">Cart</a>
                <a class="btn btn-danger" href="logout.php">Logout</a>
            </div>
        </div>
    </header>
    <div class="row m-5">
        <div class="col-6">
            <div class="card">
                <div class="card-header d-flex justify-content-between">
                    <h4>Add Product details</h4>
                    <a class="btn btn-primary" href="products.php">Back</a>
                </div>
                <div class="card-body text-center">
                    <form method="POST" action="product_create.php" class="form" enctype="multipart/form-data">
                        <label for="" class="form-label">Name</label>
                        <input class="form-control" type="text" name="name" required>
                        <label for="" class="form-label">Description</label>
                        <textarea class="form-control" name="description" id=""></textarea>
                        <label for="" class="form-label">Image</label>
                        <input class="form-control" type="file" name="image" accept="image/*">
                        <label for="" class="form-label">Price</label>
                        <input class="form-control" type="number" name="price" required step="0.01">
                        <button class="btn btn-primary mt-2" type="submit" name="save">Save</button>
                    </form>
                </div>
            </div>
        </div>
    </div>

</body>

</html>
<?php
include 'db_conn.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $name = trim($_POST['name']);
    $description = trim($_POST['description']);
    $price = trim($_POST['price']);
    $image = isset($_FILES['image']) ? $_FILES['image'] : null;

    $errors = [];

    if (empty($name)) {
        $errors[] = "Name is required.";
    }

    if (empty($price) || !is_numeric($price) || $price <= 0) {
        $errors[] = "Valid price is required.";
    }

    // Handle file upload
    if ($image && $image['error'] == UPLOAD_ERR_OK) {
        $allowed_types = ['image/jpeg', 'image/png', 'image/gif'];
        $file_type = mime_content_type($image['tmp_name']);

        if (in_array($file_type, $allowed_types)) {
            $extension = pathinfo($image['name'], PATHINFO_EXTENSION);
            $random_name = uniqid() . '.' . $extension;
            $image_path = 'imgs/' . $random_name;
            if (!move_uploaded_file($image['tmp_name'], $image_path)) {
                $errors[] = "Failed to upload image.";
            }
        } else {
            $errors[] = "Invalid image type. Only JPEG, PNG, and GIF are allowed.";
        }
    }


    if (empty($errors)) {
        $stmt = $pdo->prepare("INSERT INTO products (name, description, price, image) VALUES (:name, :description, :price, :image)");
        $stmt->execute([
            'name' => $name,
            'description' => $description,
            'price' => $price,
            'image' => $random_name ?? null
        ]);
        header("Location: products.php");
        exit;
    }
    foreach ($errors as $error) {
        echo "<p style='color:red;'>$error</p>";
    }
}
?>