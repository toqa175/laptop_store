<?php
require_once '../config/database.php';

$id = $_GET['id'] ?? '';

if (empty($id) || !is_numeric($id)) {
    die("Invalid product ID.");
}

$id = (int)$id;

$sql = "
    SELECT 
        products.*,
        brands.name AS brand_name,
        categories.name AS category_name,
        categories.slug AS category_slug
    FROM products
    LEFT JOIN brands 
        ON products.brand_id = brands.id
    LEFT JOIN categories 
        ON products.category_id = categories.id
    WHERE products.id = $id
";

$result = $connection->query($sql);

if (!$result || $result->num_rows === 0) {
    die("Product not found.");
}

$product = $result->fetch_assoc();

$specs = [];

if (!empty($product['specs'])) {
    $specs = explode('|', $product['specs']);
}
?>

<?php include '../shared/header.php'; ?>
<?php include '../shared/navbar.php'; ?>

<div class="container py-5">

    <!-- Back Button -->
    <div class="mb-4">
        <a href="index.php" class="text-decoration-none text-dark">
            <i class="bi bi-arrow-left"></i>
            Back to Products
        </a>
    </div>

    <div class="row g-5 align-items-center">

        <!-- Product Image -->
        <div class="col-lg-6">

            <div class="card border-0 shadow-sm rounded-4 overflow-hidden">

                <img
                    src="../uploads/<?= htmlspecialchars($product['image']) ?>"
                    class="img-fluid w-100 object-fit-cover"
                    alt="<?= htmlspecialchars($product['name']) ?>"
                    style="height: 450px;"
                >

            </div>

        </div>


        <!-- Product Details -->
        <div class="col-lg-6">

            <!-- Badge -->
            <?php if (!empty($product['badge'])): ?>
                <span class="badge bg-dark rounded-pill px-3 py-2 mb-3">
                    <?= htmlspecialchars($product['badge']) ?>
                </span>
            <?php endif; ?>


            <!-- Brand -->
            <?php if (!empty($product['brand_name'])): ?>
                <p class="text-muted mb-2">
                    <?= htmlspecialchars($product['brand_name']) ?>
                </p>
            <?php endif; ?>


            <!-- Product Name -->
            <h1 class="fw-bold mb-3">
                <?= htmlspecialchars($product['name']) ?>
            </h1>


            <!-- Rating -->
            <?php if (isset($product['rating'])): ?>

                <div class="mb-3">

                    <span class="text-warning">
                        <i class="bi bi-star-fill"></i>
                    </span>

                    <span class="fw-medium">
                        <?= htmlspecialchars($product['rating']) ?>
                    </span>

                    <?php if (isset($product['rating_count'])): ?>
                        <span class="text-muted">
                            (<?= htmlspecialchars($product['rating_count']) ?> reviews)
                        </span>
                    <?php endif; ?>

                </div>

            <?php endif; ?>


            <!-- Price -->
            <div class="mb-4">

                <span class="fs-2 fw-bold">
                    $<?= number_format((float)$product['price'], 2) ?>
                </span>

                <?php if (!empty($product['old_price'])): ?>

                    <span class="text-muted text-decoration-line-through ms-2">
                        $<?= number_format((float)$product['old_price'], 2) ?>
                    </span>

                <?php endif; ?>

            </div>


            <!-- Specifications -->
            <?php if (!empty($specs)): ?>

                <h5 class="fw-bold mb-3">
                    Specifications
                </h5>

                <ul class="list-group list-group-flush mb-4">

                    <?php foreach ($specs as $spec): ?>

                        <?php if (trim($spec) !== ''): ?>

                            <li class="list-group-item px-0">
                                <i class="bi bi-check2 me-2"></i>
                                <?= htmlspecialchars(trim($spec)) ?>
                            </li>

                        <?php endif; ?>

                    <?php endforeach; ?>

                </ul>

            <?php endif; ?>


            <!-- Add To Cart -->
            <a
                href="/laptop-store/cart/add.php?product_id=<?= $product['id'] ?>"
                class="btn btn-dark w-100 py-3 rounded-3 fw-medium d-flex align-items-center justify-content-center gap-2 text-decoration-none"
            >
                <i class="bi bi-cart-plus"></i>
                Add to Cart
            </a>

        </div>

    </div>

</div>

<?php include '../shared/footer.php'; ?>