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
// Get the user's cart
// ===============================
$cartResult = $connection->query("
    SELECT *
    FROM cart
    WHERE user_id = $user_id
    LIMIT 1
");

if (!$cartResult) {
    die("Error getting cart: " . $connection->error);
}

$cart = $cartResult->fetch_assoc();

$cartItems = [];


// ===============================
// Get cart items
// ===============================
if ($cart) {

    $cart_id = (int) $cart['id'];

    $itemsResult = $connection->query("
        SELECT 
            cart_items.*,
            products.name,
            products.image
        FROM cart_items
        INNER JOIN products
            ON cart_items.product_id = products.id
        WHERE cart_items.cart_id = $cart_id
        ORDER BY cart_items.id DESC
    ");

    if (!$itemsResult) {
        die("Error getting cart items: " . $connection->error);
    }

    $cartItems = $itemsResult->fetch_all(MYSQLI_ASSOC);
}

?>

<?php include_once '../shared/header.php'; ?>

<?php include_once '../shared/navbar.php'; ?>


<section class="py-5 bg-light">

    <div class="container py-4">

        <!-- Page Title -->

        <div class="text-center mb-5">

            <h1 class="display-5 fw-bold text-dark mb-2">
                Your Cart
            </h1>

            <p class="text-muted">
                Review the products you have added to your cart.
            </p>

        </div>


        <?php if (empty($cartItems)): ?>

            <!-- Empty Cart -->

            <div class="card border-0 shadow-sm rounded-4 p-5 text-center">

                <i class="bi bi-cart-x display-1 text-muted mb-3"></i>

                <h3 class="fw-bold text-dark">
                    Your cart is empty
                </h3>

                <p class="text-muted mb-4">
                    You haven't added any products yet.
                </p>

                <div>

                    <a
                        href="/laptop-store/products/index.php"
                        class="btn btn-primary px-4 py-2 rounded-3 fw-bold"
                    >
                        Continue Shopping
                        <i class="bi bi-arrow-right ms-1"></i>
                    </a>

                </div>

            </div>


        <?php else: ?>


            <div class="row g-4">

                <!-- Cart Items -->

                <div class="col-lg-8">

                    <?php foreach ($cartItems as $item): ?>

                        <?php
                        $itemTotal = $item['price'] * $item['quantity'];
                        ?>

                        <div class="card border-0 shadow-sm rounded-4 mb-3">

                            <div class="card-body p-4">

                                <div class="row align-items-center g-3">

                                    <!-- Product Image -->

                                    <div class="col-4 col-md-3">

                                        <img
                                            src="../uploads/<?= htmlspecialchars($item['image']) ?>"
                                            alt="<?= htmlspecialchars($item['name']) ?>"
                                            class="img-fluid rounded-3"
                                            style="height: 120px; width: 100%; object-fit: cover;"
                                        >

                                    </div>


                                    <!-- Product Information -->

                                    <div class="col-8 col-md-5">

                                        <h5 class="fw-bold text-dark mb-2">
                                            <?= htmlspecialchars($item['name']) ?>
                                        </h5>

                                        <p class="text-muted small mb-1">
                                            Price:
                                            $<?= number_format($item['price'], 2) ?>
                                        </p>


                                        <!-- Quantity -->

                                        <form
                                            action="/laptop-store/cart/update.php"
                                            method="POST"
                                            class="d-flex align-items-center gap-2 mt-2"
                                        >

                                            <input
                                                type="hidden"
                                                name="id"
                                                value="<?= $item['id'] ?>"
                                            >

                                            <span class="text-muted small">
                                                Quantity:
                                            </span>

                                            <input
                                                type="number"
                                                name="quantity"
                                                value="<?= $item['quantity'] ?>"
                                                min="1"
                                                class="form-control form-control-sm text-center"
                                                style="width: 70px;"
                                            >

                                            <button
                                                type="submit"
                                                class="btn btn-sm btn-dark rounded-2"
                                            >
                                                Update
                                            </button>

                                        </form>


                                        <!-- Remove -->

                                        <a
                                            href="/laptop-store/cart/delete.php?id=<?= $item['id'] ?>"
                                            class="btn btn-sm btn-outline-danger rounded-2 mt-2"
                                        >
                                            <i class="bi bi-trash"></i>
                                            Remove
                                        </a>

                                    </div>


                                    <!-- Total -->

                                    <div class="col-12 col-md-4 text-md-end">

                                        <h5 class="fw-bold text-dark mb-0">
                                            $<?= number_format($itemTotal, 2) ?>
                                        </h5>

                                    </div>

                                </div>

                            </div>

                        </div>

                    <?php endforeach; ?>

                </div>


                <!-- Cart Summary -->

                <div class="col-lg-4">

                    <?php

                    $grandTotal = 0;
                    $totalItems = 0;

                    foreach ($cartItems as $item) {

                        $grandTotal += $item['price'] * $item['quantity'];
                        $totalItems += $item['quantity'];

                    }

                    ?>


                    <div class="card border-0 shadow-sm rounded-4">

                        <div class="card-body p-4">

                            <h4 class="fw-bold text-dark mb-4">
                                Cart Summary
                            </h4>


                            <div class="d-flex justify-content-between mb-3">

                                <span class="text-muted">
                                    Total Items
                                </span>

                                <span class="fw-semibold">
                                    <?= $totalItems ?>
                                </span>

                            </div>


                            <hr>


                            <div class="d-flex justify-content-between align-items-center mb-4">

                                <span class="fw-bold text-dark">
                                    Total
                                </span>

                                <span class="fs-4 fw-bold text-primary">
                                    $<?= number_format($grandTotal, 2) ?>
                                </span>

                            </div>


                            <a
                                href="/laptop-store/checkout/index.php"
                                class="btn btn-dark w-100 py-2 rounded-3 fw-bold"
                            >
                                Checkout
                            </a>

                        </div>

                    </div>

                </div>

            </div>


        <?php endif; ?>

    </div>

</section>


<?php include_once '../shared/footer.php'; ?>


<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
