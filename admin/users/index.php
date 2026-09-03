<?php
ini_set('display_errors', 1);
error_reporting(E_ALL);

include __DIR__ . "/../../config/database.php"; 
if (!isset($connection) && isset($conn)) { $connection = $conn; }

$users = [];
if ($connection) {
    $sql = "SELECT * FROM users ORDER BY id DESC";
    $result = mysqli_query($connection, $sql);
    if ($result) {
        while ($row = mysqli_fetch_assoc($result)) {
            $users[] = $row;
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
                    <h2 class="fw-bold">Manage Users</h2>
                    <a href="create.php" class="btn btn-primary"><i class="fas fa-user-plus me-2"></i> Add New User</a>
                </div>

                <div class="card shadow-sm border-0 rounded-3">
                    <div class="card-body p-0">
                        <table class="table table-hover mb-0 align-middle">
                            <thead class="table-light">
                                <tr>
                                    <th class="py-3 px-3">#ID</th>
                                    <th class="py-3">Name</th>
                                    <th class="py-3">Email</th>
                                    <th class="py-3">Password</th>
                                    <th class="py-3">Role</th>
                                    <th class="py-3">Created At</th>
                                    <th class="py-3 text-center">Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php if (!empty($users)): ?>
                                    <?php foreach ($users as $user): ?>
                                        <tr>
                                            <td class="px-3 fw-semibold">#<?php echo $user['id']; ?></td>
                                            <td class="fw-semibold"><?php echo htmlspecialchars($user['full_name'] ?? $user['username']); ?></td>
                                            <td><?php echo htmlspecialchars($user['email']); ?></td>
                                            <!-- عرض الـ Password Hash المشفّر بشكل مختصر عشان ميكسرش شكل الجدول -->
                                            <td>
                                                <small class="text-muted text-truncate d-inline-block" style="max-width: 140px;" title="<?php echo htmlspecialchars($user['password']); ?>">
                                                    <?php echo htmlspecialchars($user['password']); ?>
                                                </small>
                                            </td>
                                            <td>
                                                <span class="badge <?php echo ($user['role'] == 'admin' || isset($user['is_admin']) && $user['is_admin']) ? 'bg-danger' : 'bg-secondary'; ?>">
                                                    <?php echo htmlspecialchars($user['role'] ?? 'User'); ?>
                                                </span>
                                            </td>
                                            <td><?php echo $user['created_at'] ?? 'N/A'; ?></td>
                                            <td class="text-center">
                                                <a href="edit.php?id=<?php echo $user['id']; ?>" class="btn btn-sm btn-outline-primary me-1"><i class="fas fa-edit"></i> Edit</a>
                                                <a href="delete.php?id=<?php echo $user['id']; ?>" class="btn btn-sm btn-outline-danger" onclick="return confirm('Are you sure you want to delete this user?');"><i class="fas fa-trash"></i> Delete</a>
                                            </td>
                                        </tr>
                                    <?php endforeach; ?>
                                <?php else: ?>
                                    <tr>
                                        <td colspan="7" class="text-center py-4 text-muted">No users found in database.</td>
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