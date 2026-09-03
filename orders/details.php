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
// Get order ID
// ===============================
$order_id = (int) ($_GET['id'] ?? 0);

if ($order_id <= 0) {
    header("Location: index.php");
    exit;
}


// ===============================
// Get order
// Only the logged-in user can
// view his own order
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
// Order doesn't exist
// or doesn't belong to user
// ===============================
if (!$order) {
    header("Location: index.php");
    exit;
}


// ===============================
// Get order items
// ===============================
$itemsResult = $connection->query("
    SELECT
        order_items.*,
        products.image
    FROM order_items
    LEFT JOIN products
        ON order_items.product_id = products.id
    WHERE order_items.order_id = $order_id
    ORDER BY order_items.id ASC
");

if (!$itemsResult) {
    die("Error getting order items: " . $connection->error);
}

$orderItems = $itemsResult->fetch_all(MYSQLI_ASSOC);

?>

<?php include_once '../shared/header.php'; ?>

<?php include_once '../shared/navbar.php'; ?>


<section class="py-5 bg-light">

    <div class="container py-4">

        <!-- Page Title -->

        <div class="d-flex justify-content-between align-items-center mb-4">

            <div>

                <h1 class="display-6 fw-bold text-dark mb-2">
                    Order #<?= htmlspecialchars($order['id']) ?>
                </h1>

                <p class="text-muted mb-0">
                    Order details and products
                </p>

            </div>


            <a
                href="index.php"
                class="btn btn-outline-dark rounded-3 fw-semibold"
            >
                <i class="bi bi-arrow-left me-1"></i>
                Back to Orders
            </a>

        </div>


        <div class="row g-4">

            <!-- Order Items -->

            <div class="col-lg-8">

                <div class="card border-0 shadow-sm rounded-4">

                    <div class="card-body p-4">

                        <h4 class="fw-bold text-dark mb-4">
                            Order Items
                        </h4>


                        <?php foreach ($orderItems as $item): ?>

                            <?php
                            $itemTotal = $item['price'] * $item['quantity'];
                            ?>


                            <div class="d-flex align-items-center gap-3 py-3 border-bottom">

                                <!-- Product Image -->

                                <?php if (!empty($item['image'])): ?>

                                    <img
                                        src="../uploads/<?= htmlspecialchars($item['image']) ?>"
                                        alt="<?= htmlspecialchars($item['product_name']) ?>"
                                        class="rounded-3"
                                        style="width: 90px; height: 90px; object-fit: cover;"
                                    >

                                <?php else: ?>

                                    <div
                                        class="bg-light rounded-3 d-flex align-items-center justify-content-center"
                                        style="width: 90px; height: 90px;"
                                    >
                                        <i class="bi bi-image text-muted fs-3"></i>
                                    </div>

                                <?php endif; ?>


                                <!-- Product Info -->

                                <div class="flex-grow-1">

                                    <h6 class="fw-bold text-dark mb-1">
                                        <?= htmlspecialchars($item['product_name']) ?>
                                    </h6>

                                    <p class="text-muted small mb-1">
                                        Quantity:
                                        <?= htmlspecialchars($item['quantity']) ?>
                                    </p>

                                    <p class="text-muted small mb-0">
                                        Price:
                                        $<?= number_format($item['price'], 2) ?>
                                    </p>

                                </div>


                                <!-- Item Total -->

                                <div>

                                    <span class="fw-bold text-dark">
                                        $<?= number_format($itemTotal, 2) ?>
                                    </span>

                                </div>

                            </div>

                        <?php endforeach; ?>


                        <!-- Total -->

                        <div class="d-flex justify-content-between align-items-center mt-4">

                            <span class="fw-bold text-dark fs-5">
                                Total
                            </span>

                            <span class="fw-bold text-primary fs-4">
                                $<?= number_format($order['total_amount'], 2) ?>
                            </span>

                        </div>

                    </div>

                </div>

            </div>


            <!-- Order Information -->

            <div class="col-lg-4">

                <div class="card border-0 shadow-sm rounded-4">

                    <div class="card-body p-4">

                        <h4 class="fw-bold text-dark mb-4">
                            Order Information
                        </h4>


                        <!-- Status -->

                        <div class="mb-3">

                            <span class="text-muted d-block mb-1">
                                Status
                            </span>


                            <?php

                            $statusClass = 'bg-warning text-dark';

                            if ($order['status'] === 'completed') {
                                $statusClass = 'bg-success';
                            } elseif ($order['status'] === 'cancelled') {
                                $statusClass = 'bg-danger';
                            }

                            ?>


                            <span class="badge <?= $statusClass ?>">
                                <?= htmlspecialchars($order['status']) ?>
                            </span>

                        </div>


                        <!-- Payment -->

                        <div class="mb-3">

                            <span class="text-muted d-block mb-1">
                                Payment Method
                            </span>

                            <span class="fw-semibold">
                                <?= htmlspecialchars($order['payment_method']) ?>
                            </span>

                        </div>


                        <!-- Phone -->

                        <div class="mb-3">

                            <span class="text-muted d-block mb-1">
                                Phone
                            </span>

                            <span class="fw-semibold">
                                <?= htmlspecialchars($order['phone']) ?>
                            </span>

                        </div>


                        <!-- Address -->

                        <div class="mb-3">

                            <span class="text-muted d-block mb-1">
                                Shipping Address
                            </span>

                            <span class="fw-semibold">
                                <?= htmlspecialchars($order['shipping_address']) ?>
                            </span>

                        </div>


                        <!-- Date -->

                        <div>

                            <span class="text-muted d-block mb-1">
                                Order Date
                            </span>

                            <span class="fw-semibold">
                                <?= date(
                                    'M d, Y',
                                    strtotime($order['created_at'])
                                ) ?>
                            </span>

                        </div>

                    </div>

                </div>

            </div>

        </div>

    </div>

</section>


<?php include_once '../shared/footer.php'; ?>


<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
