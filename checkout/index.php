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

$cartItems = [];
$grandTotal = 0;
$totalItems = 0;


if ($cart) {

    $cart_id = (int) $cart['id'];


    // ===============================
    // Get cart items
    // ===============================

    $itemsResult = $connection->query("
        SELECT
            cart_items.id,
            cart_items.product_id,
            cart_items.quantity,
            cart_items.price,
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


    // ===============================
    // Calculate totals
    // ===============================

    foreach ($cartItems as $item) {

        $grandTotal += $item['price'] * $item['quantity'];

        $totalItems += $item['quantity'];

    }

}

?>


<?php include_once '../shared/header.php'; ?>

<?php include_once '../shared/navbar.php'; ?>


<section class="py-5 bg-light">

    <div class="container py-4">


        <!-- ===============================
             Page Title
        ================================ -->

        <div class="text-center mb-5">

            <h1 class="display-5 fw-bold text-dark mb-2">
                Checkout
            </h1>

            <p class="text-muted">
                Review your order before placing it.
            </p>

        </div>


        <?php if (empty($cartItems)): ?>


            <!-- ===============================
                 Empty Cart
            ================================ -->

            <div class="card border-0 shadow-sm rounded-4 p-5 text-center">

                <i class="bi bi-cart-x display-1 text-muted mb-3"></i>

                <h3 class="fw-bold text-dark">
                    Your cart is empty
                </h3>

                <p class="text-muted mb-4">
                    Add some products before checking out.
                </p>

                <a
                    href="/laptop-store/products/index.php"
                    class="btn btn-dark px-4 py-2 rounded-3 fw-bold"
                >
                    Continue Shopping
                    <i class="bi bi-arrow-right ms-1"></i>
                </a>

            </div>


        <?php else: ?>


            <!-- ===============================
                 Checkout Content
            ================================ -->

            <div class="row g-4">


                <!-- ===============================
                     Order Items
                ================================ -->

                <div class="col-lg-8">

                    <div class="card border-0 shadow-sm rounded-4">

                        <div class="card-body p-4">

                            <h4 class="fw-bold text-dark mb-4">
                                Your Order
                            </h4>


                            <?php foreach ($cartItems as $item): ?>

                                <?php

                                $itemTotal =
                                    $item['price'] * $item['quantity'];

                                ?>


                                <div class="d-flex align-items-center gap-3 py-3 border-bottom">


                                    <!-- Product Image -->

                                    <div
                                        class="flex-shrink-0"
                                        style="width: 90px; height: 90px;"
                                    >

                                        <img
                                            src="../uploads/<?= htmlspecialchars($item['image']) ?>"
                                            alt="<?= htmlspecialchars($item['name']) ?>"
                                            class="rounded-3 w-100 h-100"
                                            style="object-fit: cover;"
                                            onerror="this.onerror=null; this.src='../assets/images/no-image.png';"
                                        >

                                    </div>


                                    <!-- Product Info -->

                                    <div class="flex-grow-1">

                                        <h6 class="fw-bold text-dark mb-1">
                                            <?= htmlspecialchars($item['name']) ?>
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

                                    <div class="text-end">

                                        <span class="fw-bold text-dark">

                                            $<?= number_format($itemTotal, 2) ?>

                                        </span>

                                    </div>


                                </div>


                            <?php endforeach; ?>


                        </div>

                    </div>

                </div>



                <!-- ===============================
                     Order Summary
                ================================ -->

                <div class="col-lg-4">

                    <div class="card border-0 shadow-sm rounded-4">

                        <div class="card-body p-4">


                            <h4 class="fw-bold text-dark mb-4">
                                Order Summary
                            </h4>


                            <!-- Items -->

                            <div class="d-flex justify-content-between mb-3">

                                <span class="text-muted">
                                    Items
                                </span>

                                <span class="fw-semibold">
                                    <?= $totalItems ?>
                                </span>

                            </div>


                            <hr>


                            <!-- Total -->

                            <div class="d-flex justify-content-between align-items-center mb-4">

                                <span class="fw-bold text-dark">
                                    Total
                                </span>

                                <span class="fs-4 fw-bold text-primary">

                                    $<?= number_format($grandTotal, 2) ?>

                                </span>

                            </div>



                            <!-- ===============================
                                 Shipping Information
                            ================================ -->

                            <div class="mt-4">

                                <h5 class="fw-bold text-dark mb-3">
                                    Shipping Information
                                </h5>


                                <form
                                    action="place_order.php"
                                    method="POST"
                                >


                                    <!-- Shipping Address -->

                                    <div class="mb-3">

                                        <label
                                            for="shipping_address"
                                            class="form-label fw-semibold"
                                        >
                                            Shipping Address
                                        </label>


                                        <textarea
                                            name="shipping_address"
                                            id="shipping_address"
                                            class="form-control bg-light border-0 rounded-3"
                                            rows="3"
                                            placeholder="Enter your shipping address"
                                            required
                                        ></textarea>

                                    </div>



                                    <!-- Phone -->

                                    <div class="mb-3">

                                        <label
                                            for="phone"
                                            class="form-label fw-semibold"
                                        >
                                            Phone
                                        </label>


                                        <input
                                            type="tel"
                                            name="phone"
                                            id="phone"
                                            class="form-control bg-light border-0 rounded-3"
                                            placeholder="Enter your phone number"
                                            required
                                        >

                                    </div>



                                    <!-- Payment Method -->

                                    <div class="mb-4">

                                        <label
                                            for="payment_method"
                                            class="form-label fw-semibold"
                                        >
                                            Payment Method
                                        </label>


                                        <select
                                            name="payment_method"
                                            id="payment_method"
                                            class="form-select bg-light border-0 rounded-3"
                                            required
                                        >

                                            <option
                                                value=""
                                                selected
                                                disabled
                                            >
                                                Select Payment Method
                                            </option>


                                            <option value="Cash on Delivery">
                                                Cash on Delivery
                                            </option>


                                            <option value="Card">
                                                Card
                                            </option>

                                        </select>

                                    </div>



                                    <!-- Place Order -->

                                    <button
                                        type="submit"
                                        class="btn btn-dark w-100 py-2 rounded-3 fw-bold"
                                    >

                                        Place Order

                                    </button>


                                </form>

                            </div>


                        </div>

                    </div>

                </div>


            </div>


        <?php endif; ?>


    </div>

</section>


<?php include_once '../shared/footer.php'; ?>


<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

