<?php
ini_set('display_errors', 1);
error_reporting(E_ALL);

include __DIR__ . "/../../config/database.php"; 
if (!isset($connection) && isset($conn)) { $connection = $conn; }

$error = "";
$success = "";
$user = null;

if (!isset($_GET['id']) || empty($_GET['id'])) {
    header("Location: index.php");
    exit();
}

$id = intval($_GET['id']);

if ($connection) {
    // جلب بيانات المستخدم
    $res = mysqli_query($connection, "SELECT * FROM users WHERE id = $id");
    if ($res && mysqli_num_rows($res) > 0) {
        $user = mysqli_fetch_assoc($res);
    } else {
        header("Location: index.php");
        exit();
    }

    if ($_SERVER['REQUEST_METHOD'] == 'POST') {
        $username = mysqli_real_escape_string($connection, $_POST['username']);
        $full_name = mysqli_real_escape_string($connection, $_POST['full_name']);
        $email = mysqli_real_escape_string($connection, $_POST['email']);
        $role = mysqli_real_escape_string($connection, $_POST['role']);
        $password = mysqli_real_escape_string($connection, $_POST['password']);

        if (!empty($username) && !empty($email)) {
            // تحديث البيانات (مع الباسورد العادي أو المشفر حسب رغبتك، هنا بنحفظه نص عادي زي ما طلبتي)
            if (!empty($password)) {
                $query = "UPDATE users SET username = '$username', full_name = '$full_name', email = '$email', role = '$role', password = '$password' WHERE id = $id";
            } else {
                $query = "UPDATE users SET username = '$username', full_name = '$full_name', email = '$email', role = '$role' WHERE id = $id";
            }

            if (mysqli_query($connection, $query)) {
                $success = "User updated successfully!";
                // تحديث البيانات المعروضة في الفورم مباشرة
                $user['username'] = $username;
                $user['full_name'] = $full_name;
                $user['email'] = $email;
                $user['role'] = $role;
                if (!empty($password)) {
                    $user['password'] = $password;
                }
            } else {
                $error = "Error: " . mysqli_error($connection);
            }
        } else {
            $error = "Username and email cannot be empty.";
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
                    <h2 class="fw-bold">Edit User</h2>
                    <a href="index.php" class="btn btn-outline-secondary btn-sm"><i class="fas fa-arrow-left me-1"></i> Back to Users</a>
                </div>

                <?php if (!empty($success)): ?><div class="alert alert-success"><?php echo $success; ?></div><?php endif; ?>
                <?php if (!empty($error)): ?><div class="alert alert-danger"><?php echo $error; ?></div><?php endif; ?>

                <div class="card shadow-sm border-0 rounded-3">
                    <div class="card-body p-4">
                        <form action="" method="POST">
                            <div class="mb-3">
                                <label class="form-label fw-semibold">Username</label>
                                <input type="text" name="username" class="form-control" value="<?php echo htmlspecialchars($user['username'] ?? ''); ?>" required>
                            </div>

                            <div class="mb-3">
                                <label class="form-label fw-semibold">Full Name</label>
                                <input type="text" name="full_name" class="form-control" value="<?php echo htmlspecialchars($user['full_name'] ?? ''); ?>">
                            </div>

                            <div class="mb-3">
                                <label class="form-label fw-semibold">Email Address</label>
                                <input type="email" name="email" class="form-control" value="<?php echo htmlspecialchars($user['email'] ?? ''); ?>" required>
                            </div>

                            <div class="mb-3">
                                <label class="form-label fw-semibold">Password <span class="text-muted fw-normal">(Leave blank if you don't want to change it)</span></label>
                                <input type="text" name="password" class="form-control" value="<?php echo htmlspecialchars($user['password'] ?? ''); ?>">
                            </div>

                            <div class="mb-3">
                                <label class="form-label fw-semibold">Role</label>
                                <select name="role" class="form-select">
                                    <option value="user" <?php echo (isset($user['role']) && $user['role'] == 'user') ? 'selected' : ''; ?>>User</option>
                                    <option value="admin" <?php echo (isset($user['role']) && $user['role'] == 'admin') ? 'selected' : ''; ?>>Admin</option>
                                </select>
                            </div>

                            <button type="submit" class="btn btn-primary px-4"><i class="fas fa-save me-2"></i> Update User</button>
                        </form>
                    </div>
                </div>

            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>