<?php
ini_set('display_errors', 1);
error_reporting(E_ALL);

include __DIR__ . "/../../config/database.php"; 
if (!isset($connection) && isset($conn)) { $connection = $conn; }

$error = "";
$success = "";

// جلب كل البراندات
$brands = [];
$brand_res = mysqli_query($connection, "SELECT * FROM brands ORDER BY name ASC");
if ($brand_res) {
    while ($row = mysqli_fetch_assoc($brand_res)) {
        $brands[] = $row;
    }
}

if ($_SERVER['REQUEST_METHOD'] == 'POST' && $connection) {
    $name = mysqli_real_escape_string($connection, $_POST['name']);
    $brand_id = intval($_POST['brand_id'] ?? 0); // اختيار براند واحد مباشر

    if (!empty($name)) {
        // 1. إضافة القسم الجديد
        $query = "INSERT INTO categories (name, created_at) VALUES ('$name', NOW())";
        if (mysqli_query($connection, $query)) {
            $category_id = mysqli_insert_id($connection);

            // 2. ربط البراند بالقسم لو تم اختياره
            if ($brand_id > 0) {
                mysqli_query($connection, "UPDATE brands SET category_id = $category_id WHERE id = $brand_id");
            }

            $success = "Category added successfully!";
        } else {
            $error = "Error: " . mysqli_error($connection);
        }
    } else {
        $error = "Category name cannot be empty.";
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
                    <h2 class="fw-bold">Add New Category</h2>
                    <a href="index.php" class="btn btn-outline-secondary btn-sm"><i class="fas fa-arrow-left me-1"></i> Back to Categories</a>
                </div>

                <?php if (!empty($success)): ?><div class="alert alert-success"><?php echo $success; ?></div><?php endif; ?>
                <?php if (!empty($error)): ?><div class="alert alert-danger"><?php echo $error; ?></div><?php endif; ?>

                <div class="card shadow-sm border-0 rounded-3">
                    <div class="card-body p-4">
                        <form action="" method="POST">
                            <div class="mb-3">
                                <label class="form-label fw-semibold">Category Name</label>
                                <input type="text" name="name" class="form-control" required placeholder="e.g. Laptops, Accessories">
                            </div>

                            <!-- رجعناها نفس شكل الـ Select الشيك العادي زي الـ Brands -->
                            <div class="mb-3">
                                <label class="form-label fw-semibold">Select Brand</label>
                                <select name="brand_id" class="form-select">
                                    <option value="">Choose Brand (Optional)</option>
                                    <?php foreach ($brands as $brand): ?>
                                        <option value="<?php echo $brand['id']; ?>"><?php echo htmlspecialchars($brand['name']); ?></option>
                                    <?php endforeach; ?>
                                </select>
                            </div>

                            <button type="submit" class="btn btn-primary px-4"><i class="fas fa-save me-2"></i> Save Category</button>
                        </form>
                    </div>
                </div>

            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>