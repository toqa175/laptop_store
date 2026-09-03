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
// Check cart item ID
// ===============================
if (!isset($_GET['id'])) {
    header("Location: index.php");
    exit;
}

$cart_item_id = (int) $_GET['id'];

if ($cart_item_id <= 0) {
    header("Location: index.php");
    exit;
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
// Delete item only if it belongs
// to this user's cart
// ===============================
$connection->query("
    DELETE FROM cart_items
    WHERE id = $cart_item_id
    AND cart_id = $cart_id
");


// ===============================
// Return to cart
// ===============================
header("Location: index.php");
exit;

?>

