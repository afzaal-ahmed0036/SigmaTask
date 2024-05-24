<?php
session_start();
include 'db_conn.php';

if (isset($_GET['id'])) {
    $cart_item_id = $_GET['id'];

    if (!isset($_SESSION['user_id'])) {
        echo "User not logged in.";
        exit;
    }
    $user_id = $_SESSION['user_id'];

    // Delete the cart item
    $stmt = $pdo->prepare("DELETE FROM carts WHERE id = :id AND user_id = :user_id");
    $stmt->execute(['id' => $cart_item_id, 'user_id' => $user_id]);

    header("Location: cart.php");
    exit;
} else {
    echo "Cart Item ID not provided.";
}
