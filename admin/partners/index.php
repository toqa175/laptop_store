<?php
ini_set('display_errors', 1);
error_reporting(E_ALL);

include __DIR__ . "/../../config/database.php"; 
if (!isset($connection) && isset($conn)) { $connection = $conn; }

$partners = [];
if ($connection) {
    $result = mysqli_query($connection, "SELECT * FROM partners ORDER BY id DESC");
    if ($result) {
        while ($row = mysqli_fetch_assoc($result)) {
            $partners[] = $row;
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
                    <h2 class="fw-bold">Manage Partners</h2>
                    <a href="create.php" class="btn btn-primary"><i class="fas fa-plus me-2"></i> Add New Partner</a>
                </div>

                <div class="card shadow-sm border-0 rounded-3">
                    <div class="card-body p-0">
                        <table class="table table-hover mb-0 align-middle">
                            <thead class="table-light">
                                <tr>
                                    <th class="py-3 px-3">#ID</th>
                                    <th class="py-3">Name</th>
                                    <th class="py-3">Created At</th>
                                    <th class="py-3 text-center">Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php if (!empty($partners)): ?>
                                    <?php foreach ($partners as $partner): ?>
                                        <tr>
                                            <td class="px-3 fw-semibold">#<?php echo $partner['id']; ?></td>
                                            <td class="fw-semibold"><?php echo htmlspecialchars($partner['name']); ?></td>
                                            <td><?php echo $partner['created_at'] ?? 'N/A'; ?></td>
                                            <td class="text-center">
                                                <a href="edit.php?id=<?php echo $partner['id']; ?>" class="btn btn-sm btn-outline-primary me-1"><i class="fas fa-edit"></i> Edit</a>
                                                <a href="delete.php?id=<?php echo $partner['id']; ?>" class="btn btn-sm btn-outline-danger" onclick="return confirm('Are you sure you want to delete this partner?');"><i class="fas fa-trash"></i> Delete</a>
                                            </td>
                                        </tr>
                                    <?php endforeach; ?>
                                <?php else: ?>
                                    <tr>
                                        <td colspan="4" class="text-center py-4 text-muted">No partners found.</td>
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