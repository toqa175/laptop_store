<?php
ini_set('display_errors', 1);
error_reporting(E_ALL);

include __DIR__ . "/../../config/database.php"; 
if (!isset($connection) && isset($conn)) { $connection = $conn; }

$error = "";
$success = "";
$partner = null;

if (!isset($_GET['id']) || empty($_GET['id'])) {
    header("Location: index.php");
    exit();
}

$id = intval($_GET['id']);

if ($connection) {
    $res = mysqli_query($connection, "SELECT * FROM partners WHERE id = $id");
    if ($res && mysqli_num_rows($res) > 0) {
        $partner = mysqli_fetch_assoc($res);
    } else {
        header("Location: index.php");
        exit();
    }

    if ($_SERVER['REQUEST_METHOD'] == 'POST') {
        $name = mysqli_real_escape_string($connection, $_POST['name']);

        if (!empty($name)) {
            $query = "UPDATE partners SET name = '$name' WHERE id = $id";
            if (mysqli_query($connection, $query)) {
                $success = "Partner updated successfully!";
                $partner['name'] = $name;
            } else {
                $error = "Error: " . mysqli_error($connection);
            }
        } else {
            $error = "Partner name cannot be empty.";
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
                    <h2 class="fw-bold">Edit Partner</h2>
                    <a href="index.php" class="btn btn-outline-secondary btn-sm"><i class="fas fa-arrow-left me-1"></i> Back</a>
                </div>

                <?php if (!empty($success)): ?><div class="alert alert-success"><?php echo $success; ?></div><?php endif; ?>
                <?php if (!empty($error)): ?><div class="alert alert-danger"><?php echo $error; ?></div><?php endif; ?>

                <div class="card shadow-sm border-0 rounded-3">
                    <div class="card-body p-4">
                        <form action="" method="POST">
                            <div class="mb-3">
                                <label class="form-label fw-semibold">Partner Name</label>
                                <input type="text" name="name" class="form-control" value="<?php echo htmlspecialchars($partner['name'] ?? ''); ?>" required>
                            </div>
                            <button type="submit" class="btn btn-primary px-4"><i class="fas fa-save me-2"></i> Update Partner</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>