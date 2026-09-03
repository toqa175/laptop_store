<?php
include __DIR__ . "/../../config/database.php"; 

$categories = [];
$cat_result = mysqli_query($connection, "SELECT * FROM categories ORDER BY name ASC");
if ($cat_result) {
    while ($row = mysqli_fetch_assoc($cat_result)) {
        $categories[] = $row;
    }
}

$error = "";
$success = "";

if ($_SERVER['REQUEST_METHOD'] == 'POST' && $connection) {
    $name = mysqli_real_escape_string($connection, $_POST['name']);
    $category_id = intval($_POST['category_id']); 

    if (!empty($name) && $category_id > 0) {
        
        $query = "INSERT INTO brands (name, category_id, created_at) VALUES ('$name', $category_id, NOW())";
        if (mysqli_query($connection, $query)) {
            $success = "Brand added successfully!";
        } else {
            $error = "Error: " . mysqli_error($connection);
        }
    } else {
        $error = "Please fill in all fields and select a valid category.";
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
                    <h2 class="fw-bold">Add New Brand</h2>
                    <a href="index.php" class="btn btn-outline-secondary btn-sm"><i class="fas fa-arrow-left me-1"></i> Back to Brands</a>
                </div>

                <?php if (!empty($success)): ?><div class="alert alert-success"><?php echo $success; ?></div><?php endif; ?>
                <?php if (!empty($error)): ?><div class="alert alert-danger"><?php echo $error; ?></div><?php endif; ?>

                <div class="card shadow-sm border-0 rounded-3">
                    <div class="card-body p-4">
                        <form action="" method="POST">
                            <div class="mb-3">
                                <label class="form-label fw-semibold">Brand Name</label>
                                <input type="text" name="name" class="form-control" required placeholder="e.g. Dell, HP, Lenovo">
                            </div>
                            <div class="mb-3">
                                <label class="form-label fw-semibold">Select Category</label>
                                <select name="category_id" class="form-select" required>
                                    <option value="">Choose Category</option>
                                    <?php foreach ($categories as $cat): ?>
                                        <option value="<?php echo $cat['id']; ?>"><?php echo htmlspecialchars($cat['name']); ?></option>
                                    <?php endforeach; ?>
                                    </select>
                            </div>
                            <button type="submit" class="btn btn-primary px-4"><i class="fas fa-save me-2"></i> Save Brand</button>
                        </form>
                    </div>
                </div>

            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>