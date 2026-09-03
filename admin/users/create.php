<?php
ini_set('display_errors', 1);
error_reporting(E_ALL);

include __DIR__ . "/../../config/database.php"; 
if (!isset($connection) && isset($conn)) { $connection = $conn; }

$error = "";
$success = "";

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $username = mysqli_real_escape_string($connection, $_POST['username']);
    $full_name = mysqli_real_escape_string($connection, $_POST['full_name']);
    $email = mysqli_real_escape_string($connection, $_POST['email']);
    $password = mysqli_real_escape_string($connection, $_POST['password']);
    $role = mysqli_real_escape_string($connection, $_POST['role']);

    if (!empty($username) && !empty($email) && !empty($password)) {
        // التأكد إن الإيميل أو اليوزرنيم مش مسجلين قبل كده
        $check_user = mysqli_query($connection, "SELECT id FROM users WHERE email = '$email' OR username = '$username'");
        if (mysqli_num_rows($check_user) > 0) {
            $error = "This email or username is already registered.";
        } else {
            $query = "INSERT INTO users (username, full_name, email, password, role, created_at) VALUES ('$username', '$full_name', '$email', '$password', '$role', NOW())";
            if (mysqli_query($connection, $query)) {
                $success = "User added successfully!";
            } else {
                $error = "Error: " . mysqli_error($connection);
            }
        }
    } else {
        $error = "Please fill in all required fields.";
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
                    <h2 class="fw-bold">Add New User</h2>
                    <a href="index.php" class="btn btn-outline-secondary btn-sm"><i class="fas fa-arrow-left me-1"></i> Back to Users</a>
                </div>

                <?php if (!empty($success)): ?><div class="alert alert-success"><?php echo $success; ?></div><?php endif; ?>
                <?php if (!empty($error)): ?><div class="alert alert-danger"><?php echo $error; ?></div><?php endif; ?>

                <div class="card shadow-sm border-0 rounded-3">
                    <div class="card-body p-4">
                        <form action="" method="POST">
                            <div class="mb-3">
                                <label class="form-label fw-semibold">Username</label>
                                <input type="text" name="username" class="form-control" required>
                            </div>

                            <div class="mb-3">
                                <label class="form-label fw-semibold">Full Name</label>
                                <input type="text" name="full_name" class="form-control">
                            </div>

                            <div class="mb-3">
                                <label class="form-label fw-semibold">Email Address</label>
                                <input type="email" name="email" class="form-control" required>
                            </div>

                            <div class="mb-3">
                                <label class="form-label fw-semibold">Password</label>
                                <input type="text" name="password" class="form-control" value="123456" required>
                            </div>

                            <div class="mb-3">
                                <label class="form-label fw-semibold">Role</label>
                                <select name="role" class="form-select">
                                    <option value="user">User</option>
                                    <option value="admin">Admin</option>
                                </select>
                            </div>

                            <button type="submit" class="btn btn-primary px-4"><i class="fas fa-save me-2"></i> Save User</button>
                        </form>
                    </div>
                </div>

            </div>
        </div>
    </div>

    <!-- تأكيد تحميل سكربت البوتستراب عشان الـ Navbar والـ Dropdown يشتغلوا تمام -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>