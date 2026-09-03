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
// Get data from checkout form
// ===============================
$shipping_address = trim($_POST['shipping_address'] ?? '');
$phone = trim($_POST['phone'] ?? '');
$payment_method = trim($_POST['payment_method'] ?? '');


// ===============================
// Validate data
// ===============================
if (
    empty($shipping_address) ||
    empty($phone) ||
    empty($payment_method)
) {
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


// ===============================
// Cart doesn't exist
// ===============================
if (!$cart) {
    header("Location: ../cart/index.php");
    exit;
}

$cart_id = (int) $cart['id'];


// ===============================
// Get cart items
// ===============================
$itemsResult = $connection->query("
    SELECT
        cart_items.product_id,
        cart_items.quantity,
        cart_items.price,
        products.name AS product_name
    FROM cart_items
    INNER JOIN products
        ON cart_items.product_id = products.id
    WHERE cart_items.cart_id = $cart_id
");

if (!$itemsResult) {
    die("Error getting cart items: " . $connection->error);
}

$cartItems = $itemsResult->fetch_all(MYSQLI_ASSOC);


// ===============================
// Cart is empty
// ===============================
if (empty($cartItems)) {
    header("Location: ../cart/index.php");
    exit;
}


// ===============================
// Calculate total
// ===============================
$grandTotal = 0;

foreach ($cartItems as $item) {
    $grandTotal += $item['price'] * $item['quantity'];
}


// ===============================
// Escape checkout data
// ===============================
$shipping_address = $connection->real_escape_string($shipping_address);
$phone = $connection->real_escape_string($phone);
$payment_method = $connection->real_escape_string($payment_method);


// ===============================
// Insert order
// ===============================
$orderQuery = "
    INSERT INTO orders
    (
        user_id,
        total_amount,
        status,
        shipping_address,
        phone,
        payment_method,
        created_at
    )
    VALUES
    (
        $user_id,
        $grandTotal,
        'pending',
        '$shipping_address',
        '$phone',
        '$payment_method',
        NOW()
    )
";

if (!$connection->query($orderQuery)) {
    die("Error creating order: " . $connection->error);
}


// ===============================
// Get new order ID
// ===============================
$order_id = $connection->insert_id;


// ===============================
// Insert order items
// ===============================
foreach ($cartItems as $item) {

    $product_name = $connection->real_escape_string(
        $item['product_name']
    );

    $product_id = (int) $item['product_id'];
    $quantity = (int) $item['quantity'];
    $price = (float) $item['price'];

    $itemQuery = "
        INSERT INTO order_items
        (
            order_id,
            product_id,
            product_name,
            quantity,
            price,
            created_at
        )
        VALUES
        (
            $order_id,
            $product_id,
            '$product_name',
            $quantity,
            $price,
            NOW()
        )
    ";

    if (!$connection->query($itemQuery)) {
        die("Error creating order item: " . $connection->error);
    }
}


// ===============================
// Clear user's cart
// ===============================
$connection->query("
    DELETE FROM cart_items
    WHERE cart_id = $cart_id
");


// ===============================
// Redirect to success page
// ===============================
header("Location: success.php?order_id=$order_id");
exit;

?>

