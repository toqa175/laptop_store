<?php
ini_set('display_errors', 1);
error_reporting(E_ALL);

include __DIR__ . "/../../config/database.php";

if (!isset($connection) && isset($conn)) {
    $connection = $conn;
}

$user_id = isset($_GET['user_id']) ? intval($_GET['user_id']) : 0;

$orders = [];
$client_name = "All Orders";

if ($connection) {

    if ($user_id > 0) {
        $sql = "SELECT o.* FROM orders o
                INNER JOIN users u ON u.id = o.user_id
                WHERE o.user_id = $user_id
                ORDER BY o.created_at DESC";
    } else {
        $sql = "SELECT o.* FROM orders o
                ORDER BY o.created_at DESC";
    }

    $result = mysqli_query($connection, $sql);

    if ($result) {
        while ($row = mysqli_fetch_assoc($result)) {
            $orders[] = $row;
        }
    }

    if ($user_id > 0) {
        $name_sql = "SELECT full_name FROM users WHERE id = $user_id LIMIT 1";
        $name_result = mysqli_query($connection, $name_sql);

        if ($name_result && mysqli_num_rows($name_result) > 0) {
            $user = mysqli_fetch_assoc($name_result);
            $client_name = $user['full_name'];
        }
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
                        <h2 class="fw-bold mb-1">Orders</h2>
                        <?php if ($user_id > 0): ?>
                            <p class="text-muted mb-0">Orders for <strong><?php echo htmlspecialchars($client_name); ?></strong></p>
                        <?php else: ?>
                            <p class="text-muted mb-0">All customer orders</p>
                        <?php endif; ?>
                    </div>
                    <a href="../clients/index.php" class="btn btn-outline-secondary">
                        <i class="fas fa-arrow-left me-2"></i> Back to Clients
                    </a>
                </div>

                <!-- Orders Card -->
                <div class="card shadow-sm border-0 rounded-3">
                    <div class="card-body p-0">
                        <div class="table-responsive">
                            <table class="table table-hover mb-0 align-middle">
                                <thead class="table-light">
                                    <tr>
                                        <th class="py-3 px-3">#Order ID</th>
                                        <th class="py-3">Customer</th>
                                        <th class="py-3">Phone</th>
                                        <th class="py-3">Address</th>
                                        <th class="py-3">Total</th>
                                        <th class="py-3">Payment</th>
                                        <th class="py-3 text-center">Status</th>
                                        <th class="py-3">Date</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php if (!empty($orders)): ?>
                                        <?php foreach ($orders as $order): ?>
                                            <tr>
                                                <td class="px-3 fw-semibold">#<?php echo $order['id']; ?></td>
                                                <td class="fw-semibold">
                                                    <?php
                                                    $order_user_id = $order['user_id'];
                                                    $customer_sql = "SELECT full_name FROM users WHERE id = $order_user_id LIMIT 1";
                                                    $customer_result = mysqli_query($connection, $customer_sql);
                                                    if ($customer_result && mysqli_num_rows($customer_result) > 0) {
                                                        $customer = mysqli_fetch_assoc($customer_result);
                                                        echo htmlspecialchars($customer['full_name']);
                                                    } else {
                                                        echo "Unknown";
                                                    }
                                                    ?>
                                                </td>
                                                <td><?php echo !empty($order['phone']) ? htmlspecialchars($order['phone']) : 'N/A'; ?></td>
                                                <td><?php echo !empty($order['shipping_address']) ? htmlspecialchars($order['shipping_address']) : 'N/A'; ?></td>
                                                <td class="fw-semibold"><?php echo number_format($order['total_amount'], 2); ?></td>
                                                <td><?php echo !empty($order['payment_method']) ? htmlspecialchars($order['payment_method']) : 'N/A'; ?></td>
                                                <td class="text-center">
                                                    <?php
                                                    $status = strtolower($order['status'] ?? '');
                                                    $badge_class = 'bg-secondary';
                                                    if ($status == 'pending') $badge_class = 'bg-warning text-dark';
                                                    elseif ($status == 'processing') $badge_class = 'bg-info text-dark';
                                                    elseif ($status == 'shipped') $badge_class = 'bg-primary';
                                                    elseif ($status == 'delivered') $badge_class = 'bg-success';
                                                    elseif ($status == 'cancelled') $badge_class = 'bg-danger';
                                                    ?>
                                                    <span class="badge <?php echo $badge_class; ?>">
                                                        <?php echo htmlspecialchars($order['status']); ?>
                                                    </span>
                                                </td>
                                                <td><?php echo !empty($order['created_at']) ? htmlspecialchars($order['created_at']) : 'N/A'; ?></td>
                                            </tr>
                                        <?php endforeach; ?>
                                    <?php else: ?>
                                        <tr>
                                            <td colspan="8" class="text-center py-5 text-muted">
                                                <i class="fas fa-shopping-cart fa-2x mb-3"></i>
                                                <div>No orders found.</div>
                                            </td>
                                        </tr>
                                    <?php endif; ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>

            </div>

        </div>

    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script> 
</body> 
</html>