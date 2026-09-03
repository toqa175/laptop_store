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
// Get Order ID
// ===============================
$order_id = (int) ($_GET['order_id'] ?? 0);

if ($order_id <= 0) {
    header("Location: ../index.php");
    exit;
}


// ===============================
// Get order information
// Only allow the logged-in user
// to see his own order
// ===============================
$orderResult = $connection->query("
    SELECT
        id,
        total_amount,
        status,
        shipping_address,
        phone,
        payment_method,
        created_at
    FROM orders
    WHERE id = $order_id
    AND user_id = $user_id
    LIMIT 1
");

if (!$orderResult) {
    die("Error getting order: " . $connection->error);
}

$order = $orderResult->fetch_assoc();


// ===============================
// Order doesn't exist or doesn't
// belong to the logged-in user
// ===============================
if (!$order) {
    header("Location: ../orders/index.php");
    exit;
}

?>

<?php include_once '../shared/header.php'; ?>

<?php include_once '../shared/navbar.php'; ?>


<section class="py-5 bg-light">

    <div class="container py-5">

        <div class="card border-0 shadow-sm rounded-4">

            <div class="card-body text-center p-5">

                <!-- Success Icon -->

                <i class="bi bi-check-circle-fill text-success display-1 mb-4"></i>


                <!-- Message -->

                <h1 class="fw-bold text-dark mb-3">
                    Order Placed Successfully!
                </h1>

                <p class="text-muted mb-4">
                    Thank you for your order.
                    Your order has been received successfully.
                </p>


                <!-- Order Information -->

                <div class="bg-light rounded-4 p-4 mb-4">

                    <div class="d-flex justify-content-between mb-3">

                        <span class="text-muted">
                            Order ID
                        </span>

                        <span class="fw-bold">
                            #<?= htmlspecialchars($order['id']) ?>
                        </span>

                    </div>


                    <div class="d-flex justify-content-between mb-3">

                        <span class="text-muted">
                            Total
                        </span>

                        <span class="fw-bold text-primary">
                            $<?= number_format($order['total_amount'], 2) ?>
                        </span>

                    </div>


                    <div class="d-flex justify-content-between mb-3">

                        <span class="text-muted">
                            Status
                        </span>

                        <span class="badge bg-warning text-dark">
                            <?= htmlspecialchars($order['status']) ?>
                        </span>

                    </div>


                    <div class="d-flex justify-content-between">

                        <span class="text-muted">
                            Payment Method
                        </span>

                        <span class="fw-semibold">
                            <?= htmlspecialchars($order['payment_method']) ?>
                        </span>

                    </div>

                </div>


                <!-- Buttons -->

                <div class="d-flex justify-content-center gap-3">

                    <a
                        href="/laptop-store/index.php"
                        class="btn btn-dark px-4 py-2 rounded-3 fw-bold"
                    >
                        Continue Shopping
                    </a>

                    <a
                        href="/laptop-store/orders/index.php"
                        class="btn btn-outline-dark px-4 py-2 rounded-3 fw-bold"
                    >
                        My Orders
                    </a>

                </div>

            </div>

        </div>

    </div>

</section>


<?php include_once '../shared/footer.php'; ?>


<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

