<?php

session_start();

require_once '../config/database.php';


// ===============================
// Check if user is logged in
// ===============================
if (!isset($_SESSION['user_id'])) {
    header("Location: ../auth/login.php");
    exit;
}

$user_id = (int) $_SESSION['user_id'];


// ===============================
// Check submitted data
// ===============================
if (!isset($_POST['id']) || !isset($_POST['quantity'])) {
    header("Location: index.php");
    exit;
}

$cart_item_id = (int) $_POST['id'];
$quantity = (int) $_POST['quantity'];


// ===============================
// Validate quantity
// ===============================
if ($cart_item_id <= 0) {
    header("Location: index.php");
    exit;
}

if ($quantity < 1) {
    $quantity = 1;
}


// ===============================
// Get user's cart
// ===============================
$cartResult = $connection->query("
    SELECT id
    FROM cart
    WHERE user_id = $user_id
    LIMIT 1
");

if (!$cartResult) {
    die("Error getting cart: " . $connection->error);
}

$cart = $cartResult->fetch_assoc();

if (!$cart) {
    header("Location: index.php");
    exit;
}

$cart_id = (int) $cart['id'];


// ===============================
// Update only item belonging
// to this user's cart
// ===============================
$connection->query("
    UPDATE cart_items
    SET quantity = $quantity
    WHERE id = $cart_item_id
    AND cart_id = $cart_id
");


// ===============================
// Return to cart
// ===============================
header("Location: index.php");
exit;

?>
