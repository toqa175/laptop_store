<?php
ini_set('display_errors', 1);
error_reporting(E_ALL);

// الاتصال بقاعدة البيانات باستخدام المسار الجذري
include $_SERVER['DOCUMENT_ROOT'] . "/laptop-store/config/database.php";

if (!isset($connection) && isset($conn)) {
    $connection = $conn;
}

// جلب الإحصائيات (المنتجات، الطلبات، المستخدمين، إلخ)
$products_count = 0;
$orders_count = 0;
$users_count = 0;
$clients_count = 0;
$categories_count = 0;
$brands_count = 0;
$partners_count = 0;
$recent_orders = [];

if ($connection) {
    // عدد المنتجات
    $res = mysqli_query($connection, "SELECT COUNT(*) as cnt FROM products");
    if ($res) $products_count = mysqli_fetch_assoc($res)['cnt'];

    // عدد الطلبات
    $res = mysqli_query($connection, "SELECT COUNT(*) as cnt FROM orders");
    if ($res) $orders_count = mysqli_fetch_assoc($res)['cnt'];

    // عدد المستخدمين
    $res = mysqli_query($connection, "SELECT COUNT(*) as cnt FROM users");
    if ($res) $users_count = mysqli_fetch_assoc($res)['cnt'];

    // عدد العملاء (الذين قاموا بطلب طلبات على الأقل)
    $res = mysqli_query($connection, "SELECT COUNT(DISTINCT user_id) as cnt FROM orders");
    if ($res) $clients_count = mysqli_fetch_assoc($res)['cnt'];

    // عدد التصنيفات
    $res = mysqli_query($connection, "SELECT COUNT(*) as cnt FROM categories");
    if ($res) $categories_count = mysqli_fetch_assoc($res)['cnt'];

    // عدد الماركات
    $res = mysqli_query($connection, "SELECT COUNT(*) as cnt FROM brands");
    if ($res) $brands_count = mysqli_fetch_assoc($res)['cnt'];

    // عدد الشركاء
    $res = mysqli_query($connection, "SELECT COUNT(*) as cnt FROM partners");
    if ($res) $partners_count = mysqli_fetch_assoc($res)['cnt'];

    // جلب أحدث الطلبات مع اسم العميل
    $orders_sql = "SELECT o.*, u.full_name as customer_name 
                   FROM orders o 
                   LEFT JOIN users u ON o.user_id = u.id 
                   ORDER BY o.created_at DESC LIMIT 5";
    $orders_res = mysqli_query($connection, $orders_sql);
    if ($orders_res) {
        while ($row = mysqli_fetch_assoc($orders_res)) {
            $recent_orders[] = $row;
        }
    }
}
?>

<?php include $_SERVER['DOCUMENT_ROOT'] . "/laptop-store/admin/shared/header.php"; ?>

<body class="bg-light">

    <div class="d-flex">

        <?php include $_SERVER['DOCUMENT_ROOT'] . "/laptop-store/admin/shared/sidebar.php"; ?>

        <div class="flex-grow-1">

            <?php include $_SERVER['DOCUMENT_ROOT'] . "/laptop-store/admin/shared/navbar.php"; ?>

            <div class="container-fluid px-4 py-4">

                <!-- Page Header -->
                <div class="mb-4">
                    <h2 class="fw-bold mb-1">Dashboard</h2>
                    <p class="text-muted mb-0">Welcome back, Admin</p>
                </div>

                <!-- Statistics Cards Row 1 -->
                <div class="row g-3 mb-4">
                    <div class="col-md-3">
                        <div class="card shadow-sm border-0 rounded-3 p-3">
                            <div class="text-muted small">Products</div>
                            <div class="d-flex align-items-center mt-2">
                                <div class="bg-primary bg-opacity-10 text-primary p-2 rounded me-3">
                                    <i class="fas fa-box fa-lg"></i>
                                </div>
                                <h3 class="fw-bold mb-0"><?php echo $products_count; ?></h3>
                            </div>
                            <div class="text-muted x-small mt-2">Total laptops in inventory</div>
                        </div>
                    </div>

                    <div class="col-md-3">
                        <div class="card shadow-sm border-0 rounded-3 p-3">
                            <div class="text-muted small">Orders</div>
                            <div class="d-flex align-items-center mt-2">
                                <div class="bg-success bg-opacity-10 text-success p-2 rounded me-3">
                                    <i class="fas fa-shopping-cart fa-lg"></i>
                                </div>
                                <h3 class="fw-bold mb-0"><?php echo $orders_count; ?></h3>
                            </div>
                            <div class="text-muted x-small mt-2">Pending & completed orders</div>
                        </div>
                    </div>

                    <div class="col-md-3">
                        <div class="card shadow-sm border-0 rounded-3 p-3">
                            <div class="text-muted small">Users</div>
                            <div class="d-flex align-items-center mt-2">
                                <div class="bg-danger bg-opacity-10 text-danger p-2 rounded me-3">
                                    <i class="fas fa-users fa-lg"></i>
                                </div>
                                <h3 class="fw-bold mb-0"><?php echo $users_count; ?></h3>
                            </div>
                            <div class="text-muted x-small mt-2">Registered website accounts</div>
                        </div>
                    </div>

                    <div class="col-md-3">
                        <div class="card shadow-sm border-0 rounded-3 p-3">
                            <div class="text-muted small">Clients</div>
                            <div class="d-flex align-items-center mt-2">
                                <div class="bg-info bg-opacity-10 text-info p-2 rounded me-3">
                                    <i class="fas fa-user-check fa-lg"></i>
                                </div>
                                <h3 class="fw-bold mb-0"><?php echo $clients_count; ?></h3>
                            </div>
                            <div class="text-muted x-small mt-2">Customers who placed orders</div>
                        </div>
                    </div>
                </div>

                <!-- Statistics Cards Row 2 -->
                <div class="row g-3 mb-4">
                    <div class="col-md-4">
                        <div class="card shadow-sm border-0 rounded-3 p-3">
                            <div class="text-muted small">Categories</div>
                            <div class="d-flex align-items-center mt-2">
                                <div class="bg-warning bg-opacity-10 text-warning p-2 rounded me-3">
                                    <i class="fas fa-list fa-lg"></i>
                                </div>
                                <h3 class="fw-bold mb-0"><?php echo $categories_count; ?></h3>
                            </div>
                            <div class="text-muted x-small mt-2">Unique product categories</div>
                        </div>
                    </div>

                    <div class="col-md-4">
                        <div class="card shadow-sm border-0 rounded-3 p-3">
                            <div class="text-muted small">Brands</div>
                            <div class="d-flex align-items-center mt-2">
                                <div class="bg-primary bg-opacity-10 text-primary p-2 rounded me-3">
                                    <i class="fas fa-tags fa-lg"></i>
                                </div>
                                <h3 class="fw-bold mb-0"><?php echo $brands_count; ?></h3>
                            </div>
                            <div class="text-muted x-small mt-2">Major laptop brands stocked</div>
                        </div>
                    </div>

                    <div class="col-md-4">
                        <div class="card shadow-sm border-0 rounded-3 p-3">
                            <div class="text-muted small">Partners</div>
                            <div class="d-flex align-items-center mt-2">
                                <div class="bg-success bg-opacity-10 text-success p-2 rounded me-3">
                                    <i class="fas fa-handshake fa-lg"></i>
                                </div>
                                <h3 class="fw-bold mb-0"><?php echo $partners_count; ?></h3>
                            </div>
                            <div class="text-muted x-small mt-2">Affiliated partners & resellers</div>
                        </div>
                    </div>
                </div>

                <!-- Recent Orders Section -->
                <div class="mb-3">
                    <h4 class="fw-bold">Recent Orders</h4>
                </div>

                <div class="card shadow-sm border-0 rounded-3">
                    <div class="card-body p-0">
                        <div class="table-responsive">
                            <table class="table table-hover mb-0 align-middle">
                                <thead class="table-light">
                                    <tr>
                                        <th class="py-3 px-3">Order ID</th>
                                        <th class="py-3">Customer</th>
                                        <th class="py-3">Total</th>
                                        <th class="py-3 text-center">Status</th>
                                        <th class="py-3">Date</th>
                                        <th class="py-3 text-end px-3">Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php if (!empty($recent_orders)): ?>
                                        <?php foreach ($recent_orders as $order): ?>
                                            <tr>
                                                <td class="px-3 fw-semibold">#<?php echo $order['id']; ?></td>
                                                <td class="fw-semibold">
                                                    <?php echo htmlspecialchars($order['customer_name'] ?? 'Unknown'); ?>
                                                </td>
                                                <td class="fw-semibold">$<?php echo number_format($order['total_amount'], 2); ?></td>
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
                                                <td><?php echo htmlspecialchars($order['created_at']); ?></td>
                                                <td class="text-end px-3">
                                                    <a href="orders/edit.php?id=<?php echo $order['id']; ?>" class="text-primary me-2 text-decoration-none">Edit</a>
                                                    <a href="orders/delete.php?id=<?php echo $order['id']; ?>" class="text-danger text-decoration-none" onclick="return confirm('Are you sure?');">Delete</a>
                                                </td>
                                            </tr>
                                        <?php endforeach; ?>
                                    <?php else: ?>
                                        <tr>
                                            <td colspan="6" class="text-center py-4 text-muted">No recent orders found.</td>
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