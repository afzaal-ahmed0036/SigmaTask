<?php
session_start();
include 'db_conn.php';

if (isset($_GET['id'])) {
    $product_id = $_GET['id'];

    if (!isset($_SESSION['user_id'])) {
        echo "User not logged in.";
        exit;
    }
    $user_id = $_SESSION['user_id'];
    // Check if the product is already in the cart
    $stmt = $pdo->prepare("SELECT COUNT(*) FROM carts WHERE user_id = :user_id AND product_id = :product_id");
    $stmt->execute([
        'user_id' => $user_id,
        'product_id' => $product_id
    ]);
    $result = $stmt->fetchColumn();

    if ($result > 0) {
        header("Location: cart.php");
        exit;
    }

    $stmt = $pdo->prepare("INSERT INTO carts (user_id, product_id) VALUES (:user_id, :product_id)");
    $stmt->execute([
        'user_id' => $user_id,
        'product_id' => $product_id
    ]);

    header("Location: cart.php");
    exit;
} else {
    echo "Product ID not provided.";
}
