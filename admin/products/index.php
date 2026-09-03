<?php
ini_set('display_errors', 1);
error_reporting(E_ALL);

include __DIR__ . "/../../config/database.php"; 
if (!isset($connection) && isset($conn)) { $connection = $conn; }

$products = [];
if ($connection) {
    $query = "SELECT p.*, c.name AS category_name, b.name AS brand_name 
              FROM products p 
              LEFT JOIN categories c ON p.category_id = c.id 
              LEFT JOIN brands b ON p.brand_id = b.id 
              ORDER BY p.id DESC";
    $result = mysqli_query($connection, $query);
    if ($result) {
        while ($row = mysqli_fetch_assoc($result)) {
            $products[] = $row;
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
                    <h2 class="fw-bold">Manage Products</h2>
                    <a href="create.php" class="btn btn-primary"><i class="fas fa-plus me-2"></i> Add New Product</a>
                </div>

                <div class="card shadow-sm border-0 rounded-3">
                    <div class="card-body p-0">
                        <table class="table table-hover mb-0 align-middle">
                            <thead class="table-light">
                                <tr>
                                    <th class="py-3 px-3">#ID</th>
                                    <th class="py-3">Image</th>
                                    <th class="py-3">Product Name</th>
                                    <th class="py-3">Category</th>
                                    <th class="py-3">Brand</th>
                                    <th class="py-3">Price</th>
                                    <th class="py-3">Stock</th>
                                    <th class="py-3 text-center">Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php if (!empty($products)): ?>
                                    <?php foreach ($products as $prod): ?>
                                        <tr>
                                            <td class="px-3 fw-semibold">#<?php echo $prod['id']; ?></td>
                                            <td>
                                                <?php if (!empty($prod['image'])): ?>
                                                    <img src="../../uploads/<?php echo $prod['image']; ?>" alt="Product" width="40" height="40" class="rounded object-fit-cover">
                                                <?php else: ?>
                                                    <span class="text-muted small">No Image</span>
                                                <?php endif; ?>
                                            </td>
                                            <td class="fw-semibold"><?php echo htmlspecialchars($prod['name']); ?></td>
                                            <td><?php echo htmlspecialchars($prod['category_name'] ?? 'Uncategorized'); ?></td>
                                            <td><?php echo htmlspecialchars($prod['brand_name'] ?? 'Unbranded'); ?></td>
                                            <td>$<?php echo number_format($prod['price'], 2); ?></td>
                                            <td><?php echo $prod['stock']; ?></td>
                                            <td class="text-center">
                                                <a href="edit.php?id=<?php echo $prod['id']; ?>" class="btn btn-sm btn-outline-primary me-1"><i class="fas fa-edit"></i> Edit</a>
                                                <a href="delete.php?id=<?php echo $prod['id']; ?>" class="btn btn-sm btn-outline-danger" onclick="return confirm('Are you sure you want to delete this product?');"><i class="fas fa-trash"></i> Delete</a>
                                            </td>
                                        </tr>
                                    <?php endforeach; ?>
                                <?php else: ?>
                                    <tr>
                                        <td colspan="8" class="text-center py-4 text-muted">No products found in database.</td>
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