<?php
session_start();

ini_set('display_errors', 1);
error_reporting(E_ALL);

// Check if user is logged in
if (!isset($_SESSION['user_id'])) {
    header("Location: ../auth/login.php");
    exit;
}

// Check if logged-in user is admin
if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'admin') {
    header("Location: ../index.php");
    exit;
}

// Database connection
include __DIR__ . "/../config/database.php";

// Support both connection variable names
if (!isset($connection) && isset($conn)) {
    $connection = $conn;
}

// Dashboard statistics
$total_products = 0;
$total_orders = 0;
$total_users = 0;
$total_categories = 0;
$total_brands = 0;
$recent_orders = [];

if ($connection) {

    $res_prod = mysqli_query(
        $connection,
        "SELECT COUNT(*) AS count FROM products"
    );

    if ($res_prod && $row = mysqli_fetch_assoc($res_prod)) {
        $total_products = $row['count'];
    }


    $res_ord = mysqli_query(
        $connection,
        "SELECT COUNT(*) AS count FROM orders"
    );

    if ($res_ord && $row = mysqli_fetch_assoc($res_ord)) {
        $total_orders = $row['count'];
    }


    $res_user = mysqli_query(
        $connection,
        "SELECT COUNT(*) AS count FROM users"
    );

    if ($res_user && $row = mysqli_fetch_assoc($res_user)) {
        $total_users = $row['count'];
    }


    $res_cat = mysqli_query(
        $connection,
        "SELECT COUNT(*) AS count FROM categories"
    );

    if ($res_cat && $row = mysqli_fetch_assoc($res_cat)) {
        $total_categories = $row['count'];
    }


    $res_brand = mysqli_query(
        $connection,
        "SELECT COUNT(*) AS count FROM brands"
    );

    if ($res_brand && $row = mysqli_fetch_assoc($res_brand)) {
        $total_brands = $row['count'];
    }


    // Recent orders
    $query_recent = "
        SELECT
            o.id,
            u.full_name AS customer,
            o.total_amount,
            o.status,
            o.created_at
        FROM orders o
        JOIN users u ON o.user_id = u.id
        ORDER BY o.created_at DESC
        LIMIT 5
    ";

    $res_recent = mysqli_query($connection, $query_recent);

    if ($res_recent) {

        while ($row = mysqli_fetch_assoc($res_recent)) {
            $recent_orders[] = $row;
        }

    }
}
?>
<?php include __DIR__ . "/shared/header.php"; ?>  
<body class="bg-light">

    <div class="d-flex">
        <!-- استدعاء السايدبار بالمسار الصحيح -->
        <?php include __DIR__ . "/shared/sidebar.php"; ?>

        <!-- المحتوى الرئيسي -->
        <div class="flex-grow-1">
            
        <?php include __DIR__ . "/shared/navbar.php"; ?>  

            <!-- محتوى الداشبورد -->
            <div class="container-fluid px-4">
                
                <!-- الصف الأول من الكروت (4 كروت) -->
                <div class="row g-3 mb-3">
                    <div class="col-md-3">
                        <div class="card shadow-sm border-0 p-3 rounded-3">
                            <div class="text-muted mb-1">Products</div>
                            <h3 class="fw-bold"><i class="fas fa-box text-primary me-2"></i> <?php echo $total_products; ?></h3>
                            <small class="text-muted">Total laptops in inventory</small>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="card shadow-sm border-0 p-3 rounded-3">
                            <div class="text-muted mb-1">Orders</div>
                            <h3 class="fw-bold"><i class="fas fa-shopping-cart text-success me-2"></i> <?php echo $total_orders; ?></h3>
                            <small class="text-muted">Pending & completed orders</small>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="card shadow-sm border-0 p-3 rounded-3">
                            <div class="text-muted mb-1">Users</div>
                            <h3 class="fw-bold"><i class="fas fa-users text-danger me-2"></i> <?php echo $total_users; ?></h3>
                            <small class="text-muted">Registered website accounts</small>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="card shadow-sm border-0 p-3 rounded-3">
                            <div class="text-muted mb-1">Clients</div>
                            <h3 class="fw-bold"><i class="fas fa-user-tie text-success me-2"></i> 0</h3>
                            <small class="text-muted">Business and bulk customers</small>
                        </div>
                    </div>
                </div>

                <!-- الصف الثاني من الكروت (3 كروت) -->
                <div class="row g-3 mb-4">
                    <div class="col-md-4">
                        <div class="card shadow-sm border-0 p-3 rounded-3">
                            <div class="text-muted mb-1">Categories</div>
                            <h3 class="fw-bold"><i class="fas fa-list text-warning me-2"></i> <?php echo $total_categories; ?></h3>
                            <small class="text-muted">Unique product categories</small>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="card shadow-sm border-0 p-3 rounded-3">
                            <div class="text-muted mb-1">Brands</div>
                            <h3 class="fw-bold"><i class="fas fa-tags text-primary me-2"></i> <?php echo $total_brands; ?></h3>
                            <small class="text-muted">Major laptop brands stocked</small>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="card shadow-sm border-0 p-3 rounded-3">
                            <div class="text-muted mb-1">Partners</div>
                            <h3 class="fw-bold"><i class="fas fa-handshake text-danger me-2"></i> 0</h3>
                            <small class="text-muted">Affiliated partners & resellers</small>
                        </div>
                    </div>
                </div>

                <!-- جدول أحدث الطلبات (Recent Orders) -->
                <div class="card shadow-sm border-0 mb-4 rounded-3">
                    <div class="card-header bg-white py-3">
                        <h5 class="mb-0 fw-bold">Recent Orders</h5>
                    </div>
                    <div class="card-body p-0">
                        <table class="table table-hover mb-0 align-middle">
                            <thead class="table-light">
                                <tr>
                                    <th class="py-3 px-3">Order ID</th>
                                    <th class="py-3">Customer</th>
                                    <th class="py-3">Total</th>
                                    <th class="py-3">Status</th>
                                    <th class="py-3">Date</th>
                                    <th class="py-3 text-center">Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php if (!empty($recent_orders)): ?>
                                    <?php foreach ($recent_orders as $order): ?>
                                        <tr>
                                            <td class="px-3 fw-semibold">#<?php echo $order['id']; ?></td>
                                            <td><?php echo htmlspecialchars($order['customer']); ?></td>
                                            <td>$<?php echo number_format($order['total_amount'], 2); ?></td>
                                            <td>
                                                <?php 
                                                    $status = strtolower($order['status']);
                                                    $badge_bg = 'bg-secondary';
                                                    if ($status == 'completed') $badge_bg = 'bg-success';
                                                    elseif ($status == 'pending') $badge_bg = 'bg-warning text-dark';
                                                    elseif ($status == 'processing') $badge_bg = 'bg-info text-dark';
                                                ?>
                                                <span class="badge <?php echo $badge_bg; ?> px-2 py-1"><?php echo ucfirst($order['status']); ?></span>
                                            </td>
                                            <td><?php echo $order['created_at']; ?></td>
                                            <td class="text-center">
                                                
                                                <a href="orders/delete.php?id=<?php echo $order['id']; ?>" class="text-danger text-decoration-none ms-2">Delete</a>
                                            </td>
                                        </tr>
                                    <?php endforeach; ?>
                                <?php else: ?>
                                    <tr>
                                        <td colspan="6" class="text-center py-4 text-muted">No orders found in database yet.</td>
                                    </tr>
                                <?php endif; ?>
                            </tbody>
                        </table>
                    </div>
                </div>

            </div>
        </div>
    </div>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>