<?php
ini_set('display_errors', 1);
error_reporting(E_ALL);

include __DIR__ . "/../../config/database.php";

if (!isset($connection) && isset($connection)) {
    $connection = $connection;
}

$clients = [];

if ($connection) {

    $sql = "SELECT 
                u.id,
                u.full_name,

                (
                    SELECT o.phone
                    FROM orders o
                    WHERE o.user_id = u.id
                    ORDER BY o.created_at DESC
                    LIMIT 1
                ) AS phone,

                (
                    SELECT o.shipping_address
                    FROM orders o
                    WHERE o.user_id = u.id
                    ORDER BY o.created_at DESC
                    LIMIT 1
                ) AS address,

                COUNT(o.id) AS orders_count

            FROM users u

            INNER JOIN orders o
                ON u.id = o.user_id

            GROUP BY u.id, u.full_name

            ORDER BY u.id DESC";

    $result = mysqli_query($connection, $sql);

    if ($result) {
        while ($row = mysqli_fetch_assoc($result)) {
            $clients[] = $row;
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

                <div class="d-flex justify-content-between align-items-center mb-4">
                    <h2 class="fw-bold">Manage Clients</h2>
                </div>

                <div class="card shadow-sm border-0 rounded-3">

                    <div class="card-body p-0">

                        <table class="table table-hover mb-0 align-middle">

                            <thead class="table-light">
                                <tr>

                                    <th class="py-3 px-3">#ID</th>

                                    <th class="py-3">Name</th>

                                    <th class="py-3">Phone</th>

                                    <th class="py-3">Address</th>

                                    <th class="py-3 text-center">
                                        Orders Count
                                    </th>

                                    <th class="py-3 text-center">
                                        Actions
                                    </th>

                                </tr>
                            </thead>

                            <tbody>

                                <?php if (!empty($clients)): ?>

                                    <?php foreach ($clients as $client): ?>

                                        <tr>

                                            <td class="px-3 fw-semibold">
                                                #<?php echo $client['id']; ?>
                                            </td>

                                            <td class="fw-semibold">
                                                <?php echo htmlspecialchars($client['full_name']); ?>
                                            </td>

                                            <td>
                                                <?php
                                                echo !empty($client['phone'])
                                                    ? htmlspecialchars($client['phone'])
                                                    : 'N/A';
                                                ?>
                                            </td>

                                            <td>
                                                <?php
                                                echo !empty($client['address'])
                                                    ? htmlspecialchars($client['address'])
                                                    : 'N/A';
                                                ?>
                                            </td>

                                            <td class="text-center">
                                                <span class="badge bg-primary">
                                                    <?php echo $client['orders_count']; ?>
                                                </span>
                                            </td>

                                            <td class="text-center">

                                                <a
                                                    href="../orders/index.php?user_id=<?php echo $client['id']; ?>"
                                                    class="btn btn-sm btn-outline-primary"
                                                >
                                                    <i class="fas fa-eye"></i>
                                                    View Orders
                                                </a>

                                            </td>

                                        </tr>

                                    <?php endforeach; ?>

                                <?php else: ?>

                                    <tr>
                                        <td
                                            colspan="6"
                                            class="text-center py-4 text-muted"
                                        >
                                            No clients found.
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

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>

</body>

</html>