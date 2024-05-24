<?php
session_start();
if (!isset($_SESSION['user_id'])) {
    header("Location: index.php");
    exit;
}
include 'db_conn.php';

if (isset($_SESSION['user_id'])) {
    $user_id = $_SESSION['user_id'];

    $stmt = $pdo->prepare("SELECT * FROM carts WHERE user_id = :user_id");
    $stmt->execute(['user_id' => $user_id]);
    $cart_data = $stmt->fetchAll();
} else {
    echo "User not logged in.";
}
?>
<!DOCTYPE html>
<html>

<head>
    <title>Cart</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">
</head>

<body>
    <header>
        <div class="row m-2">
            <div class="col d-flex justify-content-end">
                <a class="btn btn-primary me-2" href="products.php">Products</a>
                <a class="btn btn-danger" href="logout.php">Logout</a>
            </div>
        </div>
    </header>
    <hr>
    <div class="row m-5">
        <div class="col-12">
            <div class="card">
                <div class="card-header d-flex justify-content-between">
                    <h4>Cart Data</h4>
                </div>
                <div class="card-body text-center">
                    <div class="row">
                        <?php if (count($cart_data) > 0) : ?>
                            <table class="table table-stripped">
                                <thead>
                                    <tr>
                                        <th>Product Image</th>
                                        <th>Product Name</th>
                                        <th>Qty</th>
                                        <th>Product Price</th>
                                        <th>Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php foreach ($cart_data as $cart_item) : ?>
                                        <?php
                                        $product_id = $cart_item["product_id"];
                                        $stmt = $pdo->prepare("SELECT * FROM products WHERE id = :product_id");
                                        $stmt->execute(['product_id' => $product_id]);
                                        $product = $stmt->fetch();
                                        $image_path = "imgs/" . htmlspecialchars($product['image']);
                                        if (!file_exists($image_path) || $product['image'] == null) {
                                            $image_path = "imgs/default.png";
                                        }
                                        ?>
                                        <tr>
                                            <td>
                                                <img src="<?php echo $image_path; ?>" alt="Product Image" style="width: 75px; height: 75px">
                                            </td>
                                            <td>
                                                <?php echo htmlspecialchars($product['name']); ?>
                                            </td>
                                            <td>1</td>
                                            <td>
                                                <?php echo htmlspecialchars($product['price']); ?>
                                            </td>
                                            <td>
                                                <a class="btn btn-sm btn-danger" href="delete_cart.php?id=<?php echo $cart_item['id']; ?>">Delete</a>
                                            </td>
                                        </tr>
                                    <?php endforeach; ?>
                                </tbody>
                            </table>
                            <div class="row">
                                <div class="col d-flex justify-content-end">
                                    <a class="btn btn-success" href="checkout.php">Check Out</a>
                                </div>
                            </div>
                        <?php else : ?>
                            <h5 class="text-danger">No Product in the cart</h5>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
</body>

</html>