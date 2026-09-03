<?php
ini_set('display_errors', 1);
error_reporting(E_ALL);

include __DIR__ . "/../../config/database.php"; 
if (!isset($connection) && isset($conn)) { $connection = $conn; }

$error = "";
$success = "";
$product = null;

// التحقق من وجود الـ ID في الرابط
if (!isset($_GET['id']) || empty($_GET['id'])) {
    header("Location: index.php");
    exit();
}

$id = intval($_GET['id']);

if ($connection) {
    // جلب بيانات المنتج المراد تعديله
    $res = mysqli_query($connection, "SELECT * FROM products WHERE id = $id");
    if ($res && mysqli_num_rows($res) > 0) {
        $product = mysqli_fetch_assoc($res);
    } else {
        header("Location: index.php");
        exit();
    }

    // جلب الأقسام والبراندات للـ Select boxes
    $categories = [];
    $cat_res = mysqli_query($connection, "SELECT * FROM categories");
    while ($row = mysqli_fetch_assoc($cat_res)) { $categories[] = $row; }

    $brands = [];
    $brand_res = mysqli_query($connection, "SELECT * FROM brands");
    while ($row = mysqli_fetch_assoc($brand_res)) { $brands[] = $row; }

    // معالجة تحديث البيانات عند الضغط على Submit
    if ($_SERVER['REQUEST_METHOD'] == 'POST') {
        $name       = mysqli_real_escape_string($connection, $_POST['name']);
        $description= mysqli_real_escape_string($connection, $_POST['description']);
        $price      = floatval($_POST['price']);
        $stock      = intval($_POST['stock']);
        $category_id= intval($_POST['category_id']);
        $brand_id   = intval($_POST['brand_id']);

        $image_name = $product['image']; // الصورة القديمة افتراضياً

        // لو تم رفع صورة جديدة
        if (isset($_FILES['image']) && $_FILES['image']['error'] == 0) {
            $image_tmp = $_FILES['image']['tmp_name'];
            $image_orig_name = $_FILES['image']['name'];
            $image_ext = strtolower(pathinfo($image_orig_name, PATHINFO_EXTENSION));
            
            $image_name = time() . "_" . uniqid() . "." . $image_ext;
            $upload_dir = __DIR__ . "/../../uploads/";
            
            if (!is_dir($upload_dir)) {
                mkdir($upload_dir, 0777, true);
            }
            
            if (move_uploaded_file($image_tmp, $upload_dir . $image_name)) {
                // حذف الصورة القديمة لو موجودة
                if (!empty($product['image']) && file_exists($upload_dir . $product['image'])) {
                    @unlink($upload_dir . $product['image']);
                }
            }
        }

        if (!empty($name) && $price > 0) {
            $query = "UPDATE products SET 
                        name = '$name', 
                        description = '$description', 
                        price = '$price', 
                        stock = '$stock', 
                        category_id = '$category_id', 
                        brand_id = '$brand_id', 
                        image = '$image_name' 
                      WHERE id = $id";
            
            if (mysqli_query($connection, $query)) {
                $success = "Product updated successfully!";
                // تحديث بيانات المنتج المعروضة في الفورم
                $res = mysqli_query($connection, "SELECT * FROM products WHERE id = $id");
                $product = mysqli_fetch_assoc($res);
            } else {
                $error = "Database Error: " . mysqli_error($connection);
            }
        } else {
            $error = "Please fill in all required fields correctly.";
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
                    <h2 class="fw-bold">Edit Product</h2>
                    <a href="index.php" class="btn btn-outline-secondary btn-sm"><i class="fas fa-arrow-left me-1"></i> Back to Products</a>
                </div>

                <?php if (!empty($success)): ?><div class="alert alert-success"><?php echo $success; ?></div><?php endif; ?>
                <?php if (!empty($error)): ?><div class="alert alert-danger"><?php echo $error; ?></div><?php endif; ?>

                <div class="card shadow-sm border-0 rounded-3">
                    <div class="card-body p-4">
                        <form action="" method="POST" enctype="multipart/form-data">
                            <div class="row g-3">
                                <div class="col-md-6">
                                    <label class="form-label fw-semibold">Product Name</label>
                                    <input type="text" name="name" class="form-control" value="<?php echo htmlspecialchars($product['name']); ?>" required>
                                </div>
                                <div class="col-md-3">
                                    <label class="form-label fw-semibold">Price ($)</label>
                                    <input type="number" step="0.01" name="price" class="form-control" value="<?php echo $product['price']; ?>" required>
                                </div>
                                <div class="col-md-3">
                                    <label class="form-label fw-semibold">Stock Quantity</label>
                                    <input type="number" name="stock" class="form-control" value="<?php echo $product['stock']; ?>" required>
                                </div>

                                <div class="col-md-6">
                                    <label class="form-label fw-semibold">Category</label>
                                    <select name="category_id" class="form-select" required>
                                        <option value="">Select Category</option>
                                        <?php foreach ($categories as $cat): ?>
                                            <option value="<?php echo $cat['id']; ?>" <?php echo ($product['category_id'] == $cat['id']) ? 'selected' : ''; ?>>
                                                <?php echo htmlspecialchars($cat['name']); ?>
                                            </option>
                                        <?php endforeach; ?>
                                    </select>
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label fw-semibold">Brand</label>
                                    <select name="brand_id" class="form-select" required>
                                        <option value="">Select Brand</option>
                                        <?php foreach ($brands as $brand): ?>
                                            <option value="<?php echo $brand['id']; ?>" <?php echo ($product['brand_id'] == $brand['id']) ? 'selected' : ''; ?>>
                                                <?php echo htmlspecialchars($brand['name']); ?>
                                            </option>
                                        <?php endforeach; ?>
                                    </select>
                                </div>

                                <div class="col-md-12">
                                    <label class="form-label fw-semibold">Description</label>
                                    <textarea name="description" class="form-control" rows="4"><?php echo htmlspecialchars($product['description']); ?></textarea>
                                </div>

                                <div class="col-md-12">
                                    <label class="form-label fw-semibold">Product Image</label>
                                    <?php if (!empty($product['image'])): ?>
                                        <div class="mb-2">
                                            <img src="../../uploads/<?php echo $product['image']; ?>" alt="Current Image" width="60" class="rounded">
                                            <span class="text-muted small ms-2">Current Image</span>
                                        </div>
                                    <?php endif; ?>
                                    <input type="file" name="image" class="form-control" accept="image/*">
                                </div>

                                <div class="col-md-12 mt-4">
                                    <button type="submit" class="btn btn-primary px-4 py-2"><i class="fas fa-save me-2"></i> Update Product</button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>

            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>