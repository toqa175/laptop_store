<?php
require_once '../config/database.php';

$category = $_GET['category'] ?? '';
$brand = $_GET['brand'] ?? '';

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
    WHERE 1=1
";

if (!empty($category)) {
    $category = $connection->real_escape_string($category);
    $sql .= " AND categories.slug = '$category'";
}

if (!empty($brand)) {
    $brand = $connection->real_escape_string($brand);
    $sql .= " AND brands.name = '$brand'";
}

$sql .= " ORDER BY products.id DESC";

$productResult = $connection->query($sql);

if (!$productResult) {
    die("Query Error: " . $connection->error);
}

$products = $productResult->fetch_all(MYSQLI_ASSOC);
?>

<?php include_once '../shared/header.php'; ?>

<?php include_once '../shared/navbar.php'; ?>


<section class="py-5 bg-light">

    <div class="container-fluid px-lg-5 py-4">

        <!-- Page Header -->

        <div class="text-center mb-5">

            <?php if (!empty($category)): ?>

                <span class="text-primary fw-bold text-uppercase small">
                    Category
                </span>

                <h1 class="display-5 fw-bold text-dark mt-2">
                    <?= htmlspecialchars($products[0]['category_name'] ?? $category) ?>
                </h1>

            <?php elseif (!empty($brand)): ?>

                <span class="text-primary fw-bold text-uppercase small">
                    Brand
                </span>

                <h1 class="display-5 fw-bold text-dark mt-2">
                    <?= htmlspecialchars($brand) ?>
                </h1>

            <?php else: ?>

                <h1 class="display-5 fw-bold text-dark">
                    All Products
                </h1>

            <?php endif; ?>

            <p class="text-muted fs-5">
                Explore our collection of laptops.
            </p>

        </div>


        <!-- Products -->

        <?php if (empty($products)): ?>

            <div class="text-center py-5">

                <i class="bi bi-box-seam display-1 text-muted"></i>

                <h3 class="fw-bold text-dark mt-3">
                    No Products Found
                </h3>

                <p class="text-muted">
                    There are no products available for this selection.
                </p>

                <a
                    href="/laptop-store/products/index.php"
                    class="btn btn-dark px-4 py-2 rounded-3"
                >
                    View All Products
                </a>

            </div>

        <?php else: ?>

            <div class="row g-4">

                <?php foreach ($products as $product): ?>

                    <div class="col-12 col-sm-6 col-lg-3">

                        <div class="card h-100 border-0 shadow-sm rounded-4 overflow-hidden">

                            <!-- Product Image -->

                            <div class="position-relative">

                                <img
                                    src="../uploads/<?= htmlspecialchars($product['image']) ?>"
                                    class="card-img-top object-fit-cover"
                                    alt="<?= htmlspecialchars($product['name']) ?>"
                                    style="height: 220px;"
                                >

                                <?php if (!empty($product['badge'])): ?>

                                    <span
                                        class="position-absolute top-0 start-0 m-3 badge rounded-pill px-3 py-2"
                                        style="background-color: #ff6b00;"
                                    >
                                        <?= htmlspecialchars($product['badge']) ?>
                                    </span>

                                <?php endif; ?>

                            </div>


                            <!-- Card Body -->

                            <div class="card-body d-flex flex-column p-4">

                                <!-- Brand + Rating -->

                                <div class="d-flex justify-content-between align-items-center mb-1">

                                    <span class="text-primary fw-bold small text-uppercase">
                                        <?= htmlspecialchars($product['brand_name'] ?? 'Unknown') ?>
                                    </span>

                                    <span class="small fw-semibold text-dark">

                                        <i class="bi bi-star-fill text-warning"></i>

                                        <?= htmlspecialchars($product['rating']) ?>

                                    </span>

                                </div>


                                <!-- Product Name -->

                                <h5 class="card-title fw-bold text-dark mb-3">
                                    <?= htmlspecialchars($product['name']) ?>
                                </h5>


                                <!-- Specs -->

                                <ul class="list-unstyled small text-muted mb-4 d-flex flex-column gap-1">

                                    <?php

                                    $specs = explode('|', $product['specs']);

                                    ?>

                                    <?php foreach ($specs as $spec): ?>

                                        <li>

                                            <i class="bi bi-check-lg text-success me-1"></i>

                                            <?= htmlspecialchars(trim($spec)) ?>

                                        </li>

                                    <?php endforeach; ?>

                                </ul>


                                <!-- Price -->

                                <div class="mt-auto">

                                    <div class="mb-3">

                                        <span class="fs-4 fw-bold text-dark">
                                            $<?= number_format($product['price'], 2) ?>
                                        </span>

                                    </div>


                                    <!-- View Details -->

                                    <a
                                        href="/laptop-store/products/details.php?id=<?= $product['id'] ?>"
                                        class="btn btn-outline-dark w-100 py-2 rounded-3 fw-medium mb-2"
                                    >
                                        View Details
                                    </a>


                                    <!-- Add To Cart -->

                                    <a
                                        href="/laptop-store/cart/add.php?product_id=<?= $product['id'] ?>"
                                        class="btn btn-dark w-100 py-2 rounded-3 fw-medium d-flex align-items-center justify-content-center gap-2 text-decoration-none"
                                    >
                                        <i class="bi bi-cart-plus"></i>

                                        Add to Cart
                                    </a>

                                </div>

                            </div>

                        </div>

                    </div>

                <?php endforeach; ?>

            </div>

        <?php endif; ?>

    </div>

</section>


<?php include_once '../shared/footer.php'; ?>


<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>