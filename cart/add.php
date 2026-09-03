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
// Make sure product ID exists
// ===============================
if (!isset($_GET['product_id'])) {
    header("Location: ../index.php");
    exit;
}

$product_id = (int) $_GET['product_id'];

if ($product_id <= 0) {
    header("Location: ../index.php");
    exit;
}


// ===============================
// Make sure logged-in user exists
// ===============================
$userResult = $connection->query("
    SELECT id
    FROM users
    WHERE id = $user_id
    LIMIT 1
");

if (!$userResult || $userResult->num_rows === 0) {

    // User ID in session no longer exists
    session_unset();
    session_destroy();

    header("Location: ../auth/login.php");
    exit;
}


// ===============================
// Get product information
// ===============================
$productResult = $connection->query("
    SELECT id, price
    FROM products
    WHERE id = $product_id
    LIMIT 1
");

if (!$productResult) {
    header("Location: ../index.php");
    exit;
}

$product = $productResult->fetch_assoc();

if (!$product) {
    header("Location: ../index.php");
    exit;
}


// ===============================
// Check if user already has a cart
// ===============================
$cartResult = $connection->query("
    SELECT id
    FROM cart
    WHERE user_id = $user_id
    LIMIT 1
");

if (!$cartResult) {
    die("Error checking cart.");
}

$cart = $cartResult->fetch_assoc();


// ===============================
// Create cart if it doesn't exist
// ===============================
if (!$cart) {

    $connection->query("
        INSERT INTO cart (user_id, created_at)
        VALUES ($user_id, NOW())
    ");

    $cart_id = $connection->insert_id;

} else {

    $cart_id = (int) $cart['id'];
}


// ===============================
// Check if product already exists
// ===============================
$itemResult = $connection->query("
    SELECT id, quantity
    FROM cart_items
    WHERE cart_id = $cart_id
    AND product_id = $product_id
    LIMIT 1
");

if (!$itemResult) {
    die("Error checking cart item.");
}

$item = $itemResult->fetch_assoc();


// ===============================
// Increase quantity if product exists
// ===============================
if ($item) {

    $newQuantity = (int) $item['quantity'] + 1;
    $item_id = (int) $item['id'];

    $connection->query("
        UPDATE cart_items
        SET quantity = $newQuantity
        WHERE id = $item_id
    ");

} else {

    // ===============================
    // Add new product to cart
    // ===============================
    $price = (float) $product['price'];

    $connection->query("
        INSERT INTO cart_items
        (cart_id, product_id, quantity, price, created_at)
        VALUES
        ($cart_id, $product_id, 1, $price, NOW())
    ");
}


// ===============================
// Return to cart
// ===============================
header("Location: index.php");
exit;
?>
