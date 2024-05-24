<?php
session_start();
if (!isset($_SESSION['user_id'])) {
    header("Location: index.php");
    exit;
}
include 'db_conn.php';

$results_per_page = 3;

$stmt = $pdo->prepare("SELECT COUNT(id) AS total FROM products");
$stmt->execute();
$row = $stmt->fetch();
$total_results = $row['total'];

$total_pages = ceil($total_results / $results_per_page);

$page = isset($_GET['page']) && is_numeric($_GET['page']) ? (int)$_GET['page'] : 1;

$starting_limit = ($page - 1) * $results_per_page;

$stmt = $pdo->prepare("SELECT * FROM products ORDER BY id DESC LIMIT :starting_limit, :results_per_page");
$stmt->bindParam(':starting_limit', $starting_limit, PDO::PARAM_INT);
$stmt->bindParam(':results_per_page', $results_per_page, PDO::PARAM_INT);
$stmt->execute();
$products = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>
<!DOCTYPE html>
<html>

<head>
    <title>Products</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">
    <style>
        .pagination {
            margin-top: 20px;
            float: right;
        }

        .pagination a {
            margin: 0 5px;
            text-decoration: none;
            color: #000;
            border: 1px solid #ddd;
            padding: 5px 10px;
        }

        .pagination a.active {
            font-weight: bold;
            background-color: #ddd;
        }

        .pagination a:hover {
            background-color: #ddd;
        }
    </style>
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
    <hr>
    <div class="row m-5">
        <div class="col-12">
            <div class="card">
                <div class="card-header d-flex justify-content-between">
                    <h4>Products</h4>
                    <a class="btn btn-primary" href="product_create.php">Add new</a>
                </div>
                <div class="card-body text-center">
                    <div class="row">
                        <?php if (count($products) > 0) : ?>
                            <?php foreach ($products as $product) : ?>
                                <div class="col-4 mb-2">
                                    <div class="card">
                                        <div class="card-body">
                                            <?php
                                            $image_path = "imgs/" . htmlspecialchars($product['image']);
                                            if (!file_exists($image_path) || $product['image'] == null) {
                                                $image_path = "imgs/default.png";
                                            }
                                            ?>
                                            <img src="<?php echo $image_path; ?>" alt="Product Image" style="width: 200px; height: 175px">
                                            <br>
                                            <strong><?php echo htmlspecialchars($product['name']); ?></strong>
                                            <p><?php echo htmlspecialchars($product['description']); ?></p>
                                            <div class="d-flex justify-content-between ">
                                                <span class="text-info">PKR: <?php echo htmlspecialchars($product['price']); ?></span>
                                                <a class="btn btn-sm btn-secondary" href="add_cart.php?id=<?php echo $product['id']; ?>">Add to cart</a>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            <?php endforeach; ?>
                        <?php else : ?>
                            <h5 class="text-danger">No Products available</h5>
                        <?php endif; ?>
                    </div>
                    <div class="pagination">
                        <?php if ($page > 1) : ?>
                            <a href="products.php?page=<?php echo $page - 1; ?>">Previous</a>
                        <?php endif; ?>

                        <?php for ($i = 1; $i <= $total_pages; $i++) : ?>
                            <a href="products.php?page=<?php echo $i; ?>" <?php if ($page == $i) echo 'class="active"'; ?>><?php echo $i; ?></a>
                        <?php endfor; ?>

                        <?php if ($page < $total_pages) : ?>
                            <a href="products.php?page=<?php echo $page + 1; ?>">Next</a>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
</body>

</html>