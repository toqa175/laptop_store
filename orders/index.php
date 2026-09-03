
<?php

session_start();

require_once '../config/database.php';


// Check if user is logged in

if (!isset($_SESSION['user_id'])) {

    header("Location: ../auth/login.php");
    exit;

}


// Get logged-in user ID

$user_id = (int) $_SESSION['user_id'];


// Get user's orders

$ordersResult = $connection->query("
    SELECT
        id,
        total_amount,
        status,
        shipping_address,
        phone,
        payment_method,
        created_at
    FROM orders
    WHERE user_id = $user_id
    ORDER BY id DESC
");

$orders = $ordersResult->fetch_all(MYSQLI_ASSOC);

?>

<?php include_once '../shared/header.php'; ?>

<?php include_once '../shared/navbar.php'; ?>


<section class="py-5 bg-light">

    <div class="container py-4">

        <!-- Page Title -->

        <div class="text-center mb-5">

            <h1 class="display-5 fw-bold text-dark mb-2">
                My Orders
            </h1>

            <p class="text-muted">
                View all your orders and their details.
            </p>

        </div>


        <?php if (empty($orders)): ?>

            <!-- No Orders -->

            <div class="card border-0 shadow-sm rounded-4 p-5 text-center">

                <i class="bi bi-bag-x display-1 text-muted mb-3"></i>

                <h3 class="fw-bold text-dark">
                    No Orders Yet
                </h3>

                <p class="text-muted mb-4">
                    You haven't placed any orders yet.
                </p>

                <a
                    href="/laptop-store/products/index.php"
                    class="btn btn-dark px-4 py-2 rounded-3 fw-bold"
                >
                    Start Shopping
                    <i class="bi bi-arrow-right ms-1"></i>
                </a>

            </div>


        <?php else: ?>

            <!-- Orders -->

            <div class="card border-0 shadow-sm rounded-4">

                <div class="card-body p-4">

                    <div class="table-responsive">

                        <table class="table align-middle mb-0">

                            <thead>

                                <tr>

                                    <th>
                                        Order ID
                                    </th>

                                    <th>
                                        Total
                                    </th>

                                    <th>
                                        Status
                                    </th>

                                    <th>
                                        Payment
                                    </th>

                                    <th>
                                        Date
                                    </th>

                                    <th>
                                        Action
                                    </th>

                                </tr>

                            </thead>


                            <tbody>

                                <?php foreach ($orders as $order): ?>

                                    <tr>

                                        <!-- Order ID -->

                                        <td class="fw-bold">
                                            #<?= htmlspecialchars($order['id']) ?>
                                        </td>


                                        <!-- Total -->

                                        <td class="fw-semibold">
                                            $<?= number_format($order['total_amount'], 2) ?>
                                        </td>


                                        <!-- Status -->

                                        <td>

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

                                        </td>


                                        <!-- Payment -->

                                        <td>
                                            <?= htmlspecialchars($order['payment_method']) ?>
                                        </td>


                                        <!-- Date -->

                                        <td>
                                            <?= date(
                                                'M d, Y',
                                                strtotime($order['created_at'])
                                            ) ?>
                                        </td>


                                        <!-- Action -->

                                        <td>

                                            <a
                                                href="details.php?id=<?= $order['id'] ?>"
                                                class="btn btn-sm btn-dark rounded-2"
                                            >
                                                View Details
                                            </a>

                                        </td>

                                    </tr>

                                <?php endforeach; ?>

                            </tbody>

                        </table>

                    </div>

                </div>

            </div>

        <?php endif; ?>

    </div>

</section>


<?php include_once '../shared/footer.php'; ?>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
