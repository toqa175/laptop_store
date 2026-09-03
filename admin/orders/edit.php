<?php

ini_set('display_errors', 1);
error_reporting(E_ALL);

include __DIR__ . "/../../config/database.php";

if (!isset($connection) && isset($conn)) {
    $connection = $conn;
}

/*
|--------------------------------------------------------------------------
| Get Order ID
|--------------------------------------------------------------------------
*/

$order_id = isset($_GET['id']) ? intval($_GET['id']) : 0;

if ($order_id <= 0) {
    die("Invalid Order ID");
}

/*
|--------------------------------------------------------------------------
| Get Order
|--------------------------------------------------------------------------
*/

$sql = "SELECT * FROM orders WHERE id = $order_id LIMIT 1";

$result = mysqli_query($connection, $sql);

if (!$result || mysqli_num_rows($result) == 0) {
    die("Order not found");
}

$order = mysqli_fetch_assoc($result);


/*
|--------------------------------------------------------------------------
| Update Order
|--------------------------------------------------------------------------
*/

if (isset($_POST['updateOrder'])) {

    $phone = mysqli_real_escape_string(
        $connection,
        $_POST['phone']
    );

    $shipping_address = mysqli_real_escape_string(
        $connection,
        $_POST['shipping_address']
    );

    $total_amount = floatval($_POST['total_amount']);

    $payment_method = mysqli_real_escape_string(
        $connection,
        $_POST['payment_method']
    );

    $status = mysqli_real_escape_string(
        $connection,
        $_POST['status']
    );


    $update_sql = "UPDATE orders SET

                    phone = '$phone',
                    shipping_address = '$shipping_address',
                    total_amount = '$total_amount',
                    payment_method = '$payment_method',
                    status = '$status'

                   WHERE id = $order_id";


    if (mysqli_query($connection, $update_sql)) {

        header("Location: index.php");

        exit;

    } else {

        echo "Error updating order: "
             . mysqli_error($connection);
    }
}

?>

<?php include __DIR__ . "/../shared/header.php"; ?>

<body class="bg-light">

<div class="d-flex">

    <?php include __DIR__ . "/../shared/sidebar.php"; ?>

    <div class="flex-grow-1">

        <?php include __DIR__ . "/../shared/navbar.php"; ?>


        <div class="container-fluid px-4 py-4">

            <!-- Page Header -->

            <div class="d-flex justify-content-between align-items-center mb-4">

                <div>

                    <h2 class="fw-bold mb-1">
                        Edit Order
                    </h2>

                    <p class="text-muted mb-0">
                        Edit Order #<?php echo $order['id']; ?>
                    </p>

                </div>


                <a
                    href="index.php"
                    class="btn btn-outline-secondary"
                >

                    <i class="fas fa-arrow-left me-2"></i>

                    Back to Orders

                </a>

            </div>


            <!-- Edit Order Card -->

            <div class="card shadow-sm border-0 rounded-3">

                <div class="card-body p-4">

                    <form method="POST">


                        <!-- Phone -->

                        <div class="mb-3">

                            <label class="form-label fw-semibold">
                                Phone
                            </label>

                            <input
                                type="text"
                                name="phone"
                                class="form-control"
                                value="<?php echo htmlspecialchars($order['phone'] ?? ''); ?>"
                            >

                        </div>


                        <!-- Shipping Address -->

                        <div class="mb-3">

                            <label class="form-label fw-semibold">
                                Shipping Address
                            </label>

                            <textarea
                                name="shipping_address"
                                class="form-control"
                                rows="3"
                            ><?php echo htmlspecialchars($order['shipping_address'] ?? ''); ?></textarea>

                        </div>


                        <!-- Total Amount -->

                        <div class="mb-3">

                            <label class="form-label fw-semibold">
                                Total Amount
                            </label>

                            <input
                                type="number"
                                step="0.01"
                                name="total_amount"
                                class="form-control"
                                value="<?php echo htmlspecialchars($order['total_amount'] ?? ''); ?>"
                            >

                        </div>


                        <!-- Payment Method -->

                        <div class="mb-3">

                            <label class="form-label fw-semibold">
                                Payment Method
                            </label>

                            <select
                                name="payment_method"
                                class="form-select"
                            >

                                <option value="Cash"
                                    <?php
                                    echo ($order['payment_method'] ?? '') == 'Cash'
                                        ? 'selected'
                                        : '';
                                    ?>
                                >
                                    Cash
                                </option>


                                <option value="Card"
                                    <?php
                                    echo ($order['payment_method'] ?? '') == 'Card'
                                        ? 'selected'
                                        : '';
                                    ?>
                                >
                                    Card
                                </option>


                                <option value="Online"
                                    <?php
                                    echo ($order['payment_method'] ?? '') == 'Online'
                                        ? 'selected'
                                        : '';
                                    ?>
                                >
                                    Online
                                </option>

                            </select>

                        </div>


                        <!-- Status -->

                        <div class="mb-4">

                            <label class="form-label fw-semibold">
                                Status
                            </label>

                            <select
                                name="status"
                                class="form-select"
                            >

                                <option value="pending"
                                    <?php
                                    echo ($order['status'] ?? '') == 'pending'
                                        ? 'selected'
                                        : '';
                                    ?>
                                >
                                    Pending
                                </option>


                                <option value="processing"
                                    <?php
                                    echo ($order['status'] ?? '') == 'processing'
                                        ? 'selected'
                                        : '';
                                    ?>
                                >
                                    Processing
                                </option>


                                <option value="shipped"
                                    <?php
                                    echo ($order['status'] ?? '') == 'shipped'
                                        ? 'selected'
                                        : '';
                                    ?>
                                >
                                    Shipped
                                </option>


                                <option value="delivered"
                                    <?php
                                    echo ($order['status'] ?? '') == 'delivered'
                                        ? 'selected'
                                        : '';
                                    ?>
                                >
                                    Delivered
                                </option>


                                <option value="cancelled"
                                    <?php
                                    echo ($order['status'] ?? '') == 'cancelled'
                                        ? 'selected'
                                        : '';
                                    ?>
                                >
                                    Cancelled
                                </option>

                            </select>

                        </div>


                        <!-- Buttons -->

                        <div class="d-flex gap-2">

                            <button
                                type="submit"
                                name="updateOrder"
                                class="btn btn-primary"
                            >

                                <i class="fas fa-save me-2"></i>

                                Update Order

                            </button>


                            <a
                                href="index.php"
                                class="btn btn-secondary"
                            >

                                Cancel

                            </a>

                        </div>


                    </form>

                </div>

            </div>

        </div>

    </div>

</div>


<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>

</body>

</html>

