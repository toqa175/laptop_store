<?php
require_once 'config/database.php';
// Get Brands from Database
$brandResult = $connection->query("SELECT * FROM brands ORDER BY id ASC");
$brands = $brandResult->fetch_all(MYSQLI_ASSOC);

// Get Categories from Database
$categoryResult = $connection->query("SELECT * FROM categories ORDER BY id ASC");
$categories = $categoryResult->fetch_all(MYSQLI_ASSOC);

// Get Featured Products from Database
$productResult = $connection->query("
    SELECT 
        products.*,
        brands.name AS brand_name,
        categories.slug AS category_slug
    FROM products
    LEFT JOIN brands ON products.brand_id = brands.id
    LEFT JOIN categories ON products.category_id = categories.id
    ORDER BY products.id DESC
    LIMIT 4
");

// Get Team Members from Database
$teamResult = $connection->query("
    SELECT id, name, role, bio, image
    FROM team
    ORDER BY id ASC
");

$teamMembers = $teamResult->fetch_all(MYSQLI_ASSOC);

$products = $productResult->fetch_all(MYSQLI_ASSOC);

$contactMessage = '';
$contactSuccess = false;

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['contact_form'])) {

    $name = trim($_POST['name'] ?? '');
    $email = trim($_POST['email'] ?? '');
    $subject = trim($_POST['subject'] ?? '');
    $message = trim($_POST['message'] ?? '');

    if ($name === '' || $email === '' || $subject === '' || $message === '') {

        $contactMessage = "Please fill in all fields.";

    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {

        $contactMessage = "Please enter a valid email address.";

    } else {

        $stmt = $connection->prepare("
            INSERT INTO contact_messages (name, email, subject, message)
            VALUES (?, ?, ?, ?)
        ");

        $stmt->bind_param("ssss", $name, $email, $subject, $message);

        if ($stmt->execute()) {

            $contactSuccess = true;
            $contactMessage = "Your message has been sent successfully!";

        } else {

            $contactMessage = "Something went wrong. Please try again.";
        }

        $stmt->close();
    }
}

?>

<?php include_once 'shared/header.php'; ?>
<?php include_once 'shared/navbar.php'; ?>

<!-- Hero Carousel Section -->
<section id="hero" class="position-relative bg-dark text-white overflow-hidden">
    <div id="novaHeroCarousel" class="carousel slide carousel-fade" data-bs-ride="carousel">
        
        <!-- Slides Inner -->
        <div class="carousel-inner">
            
            <!-- Slide 1: Gaming Laptops (First Slide) -->
            <div class="carousel-item active position-relative" style="min-height: 80vh; background: url('https://images.unsplash.com/photo-1603302576837-37561b2e2302?q=80&w=1920&auto=format&fit=crop') center/cover no-repeat;">
                <div class="position-absolute top-0 start-0 w-100 h-100" style="background: linear-gradient(90deg, rgba(15, 23, 42, 0.95) 0%, rgba(15, 23, 42, 0.75) 50%, rgba(15, 23, 42, 0.3) 100%); z-index: 1;"></div>
                
                <div class="container-fluid px-lg-5 position-relative z-2 h-100 d-flex align-items-center py-5" style="min-height: 80vh;">
                    <div class="row w-100 py-5">
                        <div class="col-12 col-md-8 col-lg-6">
                            <span class="badge bg-danger bg-opacity-20 text-danger border border-danger border-opacity-25 px-3 py-2 rounded-pill fw-bold text-uppercase mb-3" style="letter-spacing: 0.08em; font-size: 0.75rem;">
                                HIGH PERFORMANCE
                            </span>
                            <h1 class="display-3 fw-bold text-white mb-3 lh-1">
                                Next-Gen Gaming<br>& Workstations
                            </h1>
                            <p class="text-light opacity-75 fs-5 mb-4" style="max-width: 480px;">
                                Equipped with latest RTX graphics cards and high-refresh screens for maximum speed.
                            </p>
                            <div class="d-flex flex-wrap gap-3">
                                <a href="/laptop-store/products/index.php?category=gaming" class="btn btn-primary btn-lg rounded-3 fw-bold px-4 py-2.5 fs-6">
                                    Explore Gaming <i class="bi bi-arrow-right ms-1"></i>
                                </a>
                                <a href="/laptop-store/products/index.php" class="btn btn-outline-light btn-lg rounded-3 fw-bold px-4 py-2.5 fs-6">
                                    View All Products
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Slide 2: Budget Laptops -->
            <div class="carousel-item position-relative" style="min-height: 80vh; background: url('https://images.unsplash.com/photo-1588872657578-7efd1f1555ed?q=80&w=1920&auto=format&fit=crop') center/cover no-repeat;">
                <div class="position-absolute top-0 start-0 w-100 h-100" style="background: linear-gradient(90deg, rgba(15, 23, 42, 0.95) 0%, rgba(15, 23, 42, 0.75) 50%, rgba(15, 23, 42, 0.3) 100%); z-index: 1;"></div>
                
                <div class="container-fluid px-lg-5 position-relative z-2 h-100 d-flex align-items-center py-5" style="min-height: 80vh;">
                    <div class="row w-100 py-5">
                        <div class="col-12 col-md-8 col-lg-6">
                            <span class="badge bg-warning bg-opacity-20 text-warning border border-warning border-opacity-25 px-3 py-2 rounded-pill fw-bold text-uppercase mb-3" style="letter-spacing: 0.08em; font-size: 0.75rem;">
                                SMART VALUE
                            </span>
                            <h1 class="display-3 fw-bold text-white mb-3 lh-1">
                                Power Without<br>the Price
                            </h1>
                            <p class="text-light opacity-75 fs-5 mb-4" style="max-width: 480px;">
                                Reliable everyday laptops starting at $549. Perfect for students and home office.
                            </p>
                            <div class="d-flex flex-wrap gap-3">
                                <a href="/laptop-store/products/index.php?category=budget" class="btn btn-primary btn-lg rounded-3 fw-bold px-4 py-2.5 fs-6">
                                    View Budget Laptops <i class="bi bi-arrow-right ms-1"></i>
                                </a>
                                <a href="/laptop-store/products/index.php" class="btn btn-outline-light btn-lg rounded-3 fw-bold px-4 py-2.5 fs-6">
                                    View All Products
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Slide 3: Ultrabooks -->
            <div class="carousel-item position-relative" style="min-height: 80vh; background: url('https://images.unsplash.com/photo-1525547719571-a2d4ac8945e2?q=80&w=1920&auto=format&fit=crop') center/cover no-repeat;">
                <div class="position-absolute top-0 start-0 w-100 h-100" style="background: linear-gradient(90deg, rgba(15, 23, 42, 0.95) 0%, rgba(15, 23, 42, 0.75) 50%, rgba(15, 23, 42, 0.3) 100%); z-index: 1;"></div>
                
                <div class="container-fluid px-lg-5 position-relative z-2 h-100 d-flex align-items-center py-5" style="min-height: 80vh;">
                    <div class="row w-100 py-5">
                        <div class="col-12 col-md-8 col-lg-6">
                            <span class="badge bg-info bg-opacity-20 text-info border border-info border-opacity-25 px-3 py-2 rounded-pill fw-bold text-uppercase mb-3" style="letter-spacing: 0.08em; font-size: 0.75rem;">
                                ULTRABOOKS
                            </span>
                            <h1 class="display-3 fw-bold text-white mb-3 lh-1">
                                Ultra-Thin,<br>All-Day Battery
                            </h1>
                            <p class="text-light opacity-75 fs-5 mb-4" style="max-width: 480px;">
                                Lightweight aluminum design engineered for seamless portability and efficiency.
                            </p>
                            <div class="d-flex flex-wrap gap-3">
                                <a href="/laptop-store/products/index.php?category=business" class="btn btn-primary btn-lg rounded-3 fw-bold px-4 py-2.5 fs-6">
                                    Shop Ultrabooks <i class="bi bi-arrow-right ms-1"></i>
                                </a>
                                <a href="/laptop-store/products/index.php" class="btn btn-outline-light btn-lg rounded-3 fw-bold px-4 py-2.5 fs-6">
                                    View All Products
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

        </div>

        <!-- Slider Controls Bar -->
        <div class="container-fluid px-lg-5 position-absolute bottom-0 start-0 end-0 z-3 pb-4">
            <div class="d-flex justify-content-between align-items-center">
                
                <div class="carousel-indicators position-static m-0 justify-content-start">
                    <button type="button" data-bs-target="#novaHeroCarousel" data-bs-slide-to="0" class="active" aria-current="true" aria-label="Slide 1"></button>
                    <button type="button" data-bs-target="#novaHeroCarousel" data-bs-slide-to="1" aria-label="Slide 2"></button>
                    <button type="button" data-bs-target="#novaHeroCarousel" data-bs-slide-to="2" aria-label="Slide 3"></button>
                </div>

                <div class="d-flex gap-2">
                    <button class="btn btn-light rounded-circle p-0 d-inline-flex align-items-center justify-content-center shadow-sm" type="button" data-bs-target="#novaHeroCarousel" data-bs-slide="prev" style="width: 42px; height: 42px;">
                        <i class="bi bi-chevron-left text-dark fs-6"></i>
                    </button>
                    <button class="btn btn-light rounded-circle p-0 d-inline-flex align-items-center justify-content-center shadow-sm" type="button" data-bs-target="#novaHeroCarousel" data-bs-slide="next" style="width: 42px; height: 42px;">
                        <i class="bi bi-chevron-right text-dark fs-6"></i>
                    </button>
                </div>

            </div>
        </div>

    </div>
</section>




<!-- Categories Section -->
<section id="categories" class="py-5 bg-white">

    <div class="container-fluid px-lg-5 py-4">

        <!-- Section Header -->
        <div class="text-center mb-5">

            <span class="text-primary fw-bold text-uppercase small tracking-wider">
                Browse By Category
            </span>

            <h2 class="display-5 fw-bold text-dark mt-2 mb-3">
                Find Your Perfect Machine
            </h2>

            <p class="text-muted fs-5 mx-auto" style="max-width: 650px;">
                Whether you're chasing frame rates, closing deals, or working in the field —
                we've got a laptop built for it.
            </p>

        </div>


        <!-- Dynamic Categories -->
        <div class="row g-4">

            <?php if (!empty($categories)): ?>

                <?php foreach ($categories as $category): ?>

                    <div class="col-12 col-sm-6 col-lg-3">

                        <a
                            href="/laptop-store/products/index.php?category=<?= urlencode($category['slug']) ?>"
                            class="text-decoration-none"
                        >

                            <div class="card bg-dark text-white border-0 shadow rounded-4 overflow-hidden h-100">


                                <!-- Category Image -->
                                <?php if (!empty($category['image'])): ?>

                                    <img
                                        src="uploads/<?= htmlspecialchars($category['image']) ?>"
                                        class="card-img object-fit-cover"
                                        alt="<?= htmlspecialchars($category['name']) ?>"
                                        style="
                                            height: 420px;
                                            opacity: 0.55;
                                        "
                                    >

                                <?php else: ?>

                                    <!-- Default Image if no image -->
                                    <img
                                        src="https://images.unsplash.com/photo-1542751371-adc38448a05e?q=80&w=800&auto=format&fit=crop"
                                        class="card-img object-fit-cover"
                                        alt="<?= htmlspecialchars($category['name']) ?>"
                                        style="
                                            height: 420px;
                                            opacity: 0.4;
                                        "
                                    >

                                <?php endif; ?>


                                <!-- Overlay -->
                                <div class="card-img-overlay d-flex flex-column justify-content-between p-4">


                                    <!-- Icon -->
                                    <div>

                                        <span class="badge bg-light text-dark p-2 rounded-3">

                                            <?php if (!empty($category['icon'])): ?>

                                                <i class="<?= htmlspecialchars($category['icon']) ?> fs-5"></i>

                                            <?php else: ?>

                                                <i class="bi bi-laptop fs-5"></i>

                                            <?php endif; ?>

                                        </span>

                                    </div>


                                    <!-- Content -->
                                    <div>

                                        <span class="text-info fw-bold small text-uppercase d-block mb-1">
                                            Explore Our Collection
                                        </span>


                                        <h3 class="card-title fw-bold fs-4 mb-2">
                                            <?= htmlspecialchars($category['name']) ?>
                                        </h3>


                                        <?php if (!empty($category['description'])): ?>

                                            <p class="card-text small text-light opacity-75 mb-3">
                                                <?= htmlspecialchars($category['description']) ?>
                                            </p>

                                        <?php endif; ?>


                                        <span class="fw-bold small text-white">
                                            Explore
                                            <i class="bi bi-arrow-right"></i>
                                        </span>

                                    </div>


                                </div>

                            </div>

                        </a>

                    </div>

                <?php endforeach; ?>


            <?php else: ?>

                <!-- No Categories -->
                <div class="col-12 text-center">

                    <p class="text-muted">
                        No categories available.
                    </p>

                </div>

            <?php endif; ?>

        </div>

    </div>

</section>



<!-- Featured Products Section -->
<section id="products" class="py-5 bg-light">
    <div class="container-fluid px-lg-5 py-4">
        
        <!-- Section Header -->
        <div class="d-flex flex-column flex-md-row justify-content-between align-items-md-end mb-5">
            <div>
                <div class="d-inline-flex align-items-center gap-2 text-primary fw-bold text-uppercase small mb-2">
                    <span style="width: 20px; height: 2px; background-color: #0d6efd;"></span> FEATURED PRODUCTS
                </div>
                <h2 class="display-5 fw-bold text-dark mb-2">Handpicked for Performance</h2>
                <p class="text-muted fs-5 mb-0" style="max-width: 520px;">
                    Our top-rated laptops across every category — tested, reviewed, and ready to ship.
                </p>
            </div>
            <a href="/laptop-store/products/index.php" class="text-primary fw-bold text-decoration-none mt-3 mt-md-0 fs-6">
                View All Products <i class="bi bi-arrow-right"></i>
            </a>
        </div>

        <!-- Dynamic Product Cards -->
        <div class="row g-4">

            <?php foreach ($products as $product): ?>

                <div class="col-12 col-sm-6 col-lg-3">

                    <div class="card h-100 border-0 shadow-sm rounded-4 overflow-hidden">

                        <!-- Image & Badge -->
                        <div class="position-relative">

                            <img
                                src="uploads/<?= htmlspecialchars($product['image']) ?>"
                                class="card-img-top object-fit-cover"
                                alt="<?= htmlspecialchars($product['name']) ?>"
                                style="height: 220px;"
                            >

                            <?php if (!empty($product['badge'])): ?>

                                <span class="position-absolute top-0 start-0 m-3 badge rounded-pill px-3 py-2"
                                      style="background-color: #ff6b00;">
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


                            <!-- Price & Button -->
                            <div class="mt-auto">

                                <div class="mb-3">
                                    <span class="fs-4 fw-bold text-dark">
                                        $<?= number_format($product['price'], 2) ?>
                                    </span>
                                </div>

                               <a
                                    href="/laptop-store/cart/add.php?product_id=<?= $product['id'] ?>"
                                    class="btn btn-dark w-100 py-2 rounded-3 fw-medium d-flex align-items-center justify-content-center gap-2 text-decoration-none"><i class="bi bi-cart-plus"></i>
                                    Add to Cart
                                </a>


                            </div>

                        </div>

                    </div>

                </div>

            <?php endforeach; ?>

        </div>
    </div>
</section>


<!-- Partners Section -->
<section id="partners" class="py-5 bg-white border-top border-bottom">
    <div class="container py-2">
        
        <p class="text-center text-primary fw-bold small text-uppercase mb-4" style="letter-spacing: 0.12em;">
            Trusted By Industry Leaders
        </p>

        <div class="row row-cols-2 row-cols-sm-3 row-cols-md-6 g-4 align-items-center justify-content-center text-center">
            <div class="col">
                <span class="fs-4 fw-bold text-primary opacity-75">Coca-Cola</span>
            </div>
            <div class="col">
                <span class="fs-4 fw-bold text-primary opacity-75">WE</span>
            </div>
            <div class="col">
                <span class="fs-4 fw-bold text-primary opacity-75">Fawry</span>
            </div>
            <div class="col">
                <span class="fs-4 fw-bold text-primary opacity-75">Raya</span>
            </div>
            <div class="col">
                <span class="fs-4 fw-bold text-primary opacity-75">Vodafone</span>
            </div>
            <div class="col">
                <span class="fs-4 fw-bold text-primary opacity-75">Elsewedy</span>
            </div>
        </div>

    </div>
</section>

<!-- Brands Section -->
<section id="brands" class="py-5 bg-light">
    <div class="container-fluid px-lg-5 py-4">
        
        <div class="text-center mb-5">
            <div class="d-inline-flex align-items-center gap-2 text-primary fw-bold text-uppercase small mb-2">
                <span style="width: 20px; height: 2px; background-color: #0d6efd;"></span> BRANDS WE CARRY
            </div>
            <h2 class="display-5 fw-bold text-dark mb-3">The Best in the Business</h2>
            <p class="text-muted fs-5 mx-auto" style="max-width: 600px;">
                We partner with the world's leading laptop manufacturers to bring you the latest technology.
            </p>
        </div>

        <div class="row g-4">

            <?php foreach ($brands as $brand): ?>

                <div class="col-12 col-md-4">

                    <a href="/laptop-store/products/index.php?brand=<?= urlencode($brand['name']) ?>"
                       class="text-decoration-none">

                        <div class="card h-100 border-0 shadow-sm rounded-4 p-4 border-top border-4 border-primary">

                            <div class="d-flex justify-content-between align-items-center mb-3">

                                <h3 class="fw-bold fs-2 mb-0 text-primary">
                                    <?= htmlspecialchars($brand['name']) ?>
                                </h3>

                                <i class="bi bi-arrow-right fs-4 text-muted"></i>

                            </div>

                            <p class="fw-bold text-dark mb-2">
                                <?= htmlspecialchars($brand['name']) ?>
                            </p>

                            <p class="text-muted small mb-0">
                                Discover the latest <?= htmlspecialchars($brand['name']) ?> laptops available in our store.
                            </p>

                        </div>

                    </a>

                </div>

            <?php endforeach; ?>

        </div>
    </div>
</section>


<!-- Team Section -->
<section id="team" class="py-5 bg-white">
    <div class="container-fluid px-lg-5 py-4">

        <!-- Section Header -->
        <div class="text-center mb-5">

            <div class="d-inline-flex align-items-center gap-2 text-primary fw-bold text-uppercase small mb-2">
                <span style="width: 20px; height: 2px; background-color: #0d6efd;"></span>
                OUR TEAM
            </div>

            <h2 class="display-5 fw-bold text-dark mb-3">
                The People Behind NovaTech
            </h2>

            <p class="text-muted fs-5 mx-auto" style="max-width: 650px;">
                Passionate tech experts and reviewers dedicated to testing,
                evaluating, and helping you find the right machine.
            </p>

        </div>


        <!-- Dynamic Team Members -->
        <div class="row g-4 justify-content-center">

            <?php if (!empty($teamMembers)): ?>

                <?php foreach ($teamMembers as $member): ?>

                    <div class="col-12 col-sm-6 col-lg-4">

                        <div class="text-center">

                            <!-- Team Image -->
                            <?php if (!empty($member['image'])): ?>

                                <img
                                    src="uploads/<?= htmlspecialchars($member['image']) ?>"
                                    class="img-fluid rounded-4 mb-3 object-fit-cover shadow-sm"
                                    alt="<?= htmlspecialchars($member['name']) ?>"
                                    style="height: 320px; width: 100%;"
                                >

                            <?php else: ?>

                                <div
                                    class="bg-light rounded-4 mb-3 d-flex align-items-center justify-content-center shadow-sm"
                                    style="height: 320px; width: 100%;"
                                >
                                    <i
                                        class="bi bi-person-circle text-secondary"
                                        style="font-size: 100px;"
                                    ></i>
                                </div>

                            <?php endif; ?>


                            <!-- Name -->
                            <h4 class="fw-bold text-dark mb-1">
                                <?= htmlspecialchars($member['name']) ?>
                            </h4>


                            <!-- Role -->
                            <p class="text-primary fw-medium small mb-2">
                                <?= htmlspecialchars($member['role']) ?>
                            </p>


                            <!-- Bio -->
                            <?php if (!empty($member['bio'])): ?>

                                <p class="text-muted small px-2">
                                    <?= htmlspecialchars($member['bio']) ?>
                                </p>

                            <?php endif; ?>

                        </div>

                    </div>

                <?php endforeach; ?>

            <?php else: ?>

                <!-- No Team Members -->
                <div class="col-12 text-center">

                    <p class="text-muted">
                        No team members available.
                    </p>

                </div>

            <?php endif; ?>

        </div>

    </div>
</section>




<!-- About Section -->
<section id="about" class="py-5 bg-white">
    <div class="container-fluid px-lg-5 py-4">
        <div class="row g-5 align-items-center">
            
            <div class="col-lg-7">
                <div class="d-inline-flex align-items-center gap-2 text-primary fw-bold text-uppercase small mb-2">
                    <span style="width: 20px; height: 2px; background-color: #0d6efd;"></span> ABOUT NOVATECH
                </div>
                <h2 class="display-5 fw-bold text-dark mb-4">Your Trusted Laptop Destination Since 2015</h2>
                
                <p class="text-secondary fs-5 mb-4 opacity-75">
                    NovaTech started with a simple mission: make buying a laptop easy. No jargon, no upselling — just honest advice and the best machines on the market.
                </p>
                <p class="text-secondary fs-6 mb-5 opacity-75">
                    Today we carry over 500 models from the world's top manufacturers, backed by a team of certified technicians and a commitment to customer satisfaction that's earned us a 4.9-star average rating from over 15,000 happy customers across Egypt.
                </p>

                <div class="row g-4 pt-2">
                    <div class="col-6 col-sm-3">
                        <h3 class="display-6 fw-bold text-primary mb-1">15K+</h3>
                        <p class="text-muted small fw-medium mb-0">Happy Customers</p>
                    </div>
                    <div class="col-6 col-sm-3">
                        <h3 class="display-6 fw-bold text-primary mb-1">500+</h3>
                        <p class="text-muted small fw-medium mb-0">Laptop Models</p>
                    </div>
                    <div class="col-6 col-sm-3">
                        <h3 class="display-6 fw-bold text-primary mb-1">10</h3>
                        <p class="text-muted small fw-medium mb-0">Years of Service</p>
                    </div>
                    <div class="col-6 col-sm-3">
                        <h3 class="display-6 fw-bold text-primary mb-1">4.9/5</h3>
                        <p class="text-muted small fw-medium mb-0">Average Rating</p>
                    </div>
                </div>
            </div>

            <div class="col-lg-5">
                <div class="card border-0 bg-light rounded-4 p-4 p-md-5 shadow-sm">
                    <h4 class="fw-bold text-dark mb-4">Why Buy From Us?</h4>
                    <div class="d-flex align-items-start gap-3 mb-4">
                        <div class="bg-primary text-white p-3 rounded-3">
                            <i class="bi bi-shield-check fs-4"></i>
                        </div>
                        <div>
                            <h6 class="fw-bold text-dark mb-1">Official Local Warranty</h6>
                            <p class="text-muted small mb-0">All our laptops come with full manufacturer warranty and local service center support.</p>
                        </div>
                    </div>
                    <div class="d-flex align-items-start gap-3 mb-4">
                        <div class="bg-primary text-white p-3 rounded-3">
                            <i class="bi bi-truck fs-4"></i>
                        </div>
                        <div>
                            <h6 class="fw-bold text-dark mb-1">Fast Nationwide Delivery</h6>
                            <p class="text-muted small mb-0">Safe and express door-to-door shipping across all Egyptian governorates.</p>
                        </div>
                    </div>
                    <div class="d-flex align-items-start gap-3">
                        <div class="bg-primary text-white p-3 rounded-3">
                            <i class="bi bi-headset fs-4"></i>
                        </div>
                        <div>
                            <h6 class="fw-bold text-dark mb-1">Free Tech Advice</h6>
                            <p class="text-muted small mb-0">Our expert team helps you pick the perfect specs for your budget and workflow.</p>
                        </div>
                    </div>
                </div>
            </div>

        </div>
    </div>
</section>

<section id="contact" class="py-5 bg-white border-top">
    <div class="container py-4">
        
        <div class="mx-auto text-center" style="max-width: 580px;">
            
            <div class="d-inline-flex align-items-center gap-2 text-primary fw-bold text-uppercase small mb-2">
                <span style="width: 20px; height: 2px; background-color: #0d6efd;"></span>
                GET IN TOUCH
            </div>

            <h2 class="display-5 fw-bold text-dark mb-2">
                Contact Us
            </h2>

            <p class="text-muted fs-6 mb-4">
                Any questions or remarks? Just write us a message!
            </p>


            <?php if (!empty($contactMessage)): ?>

                <div class="alert <?= $contactSuccess ? 'alert-success' : 'alert-danger' ?> rounded-pill">
                    <?= htmlspecialchars($contactMessage) ?>
                </div>

            <?php endif; ?>


            <form action="#contact" method="POST" class="text-start">

                <input type="hidden" name="contact_form" value="1">


                <div class="row g-3 mb-3">

                    <!-- Email -->
                    <div class="col-12 col-md-6">

                        <label
                            for="contactEmail"
                            class="form-label text-dark small fw-semibold mb-1 ms-1"
                        >
                            Email
                        </label>

                        <input
                            type="email"
                            name="email"
                            class="form-control bg-light border-0 py-2 px-3 rounded-pill text-dark shadow-sm"
                            id="contactEmail"
                            placeholder="Enter a valid email address"
                            required
                        >

                    </div>


                    <!-- Name -->
                    <div class="col-12 col-md-6">

                        <label
                            for="contactName"
                            class="form-label text-dark small fw-semibold mb-1 ms-1"
                        >
                            Name
                        </label>

                        <input
                            type="text"
                            name="name"
                            class="form-control bg-light border-0 py-2 px-3 rounded-pill text-dark shadow-sm"
                            id="contactName"
                            placeholder="Enter your Name"
                            required
                        >

                    </div>

                </div>


                <!-- Subject -->
                <div class="mb-3">

                    <label
                        for="contactSubject"
                        class="form-label text-dark small fw-semibold mb-1 ms-1"
                    >
                        Subject
                    </label>

                    <input
                        type="text"
                        name="subject"
                        class="form-control bg-light border-0 py-2 px-3 rounded-pill text-dark shadow-sm"
                        id="contactSubject"
                        placeholder="Enter the subject"
                        required
                    >

                </div>


                <!-- Message -->
                <div class="mb-4">

                    <label
                        for="contactMessage"
                        class="form-label text-dark small fw-semibold mb-1 ms-1"
                    >
                        Message
                    </label>

                    <textarea
                        name="message"
                        class="form-control bg-light border-0 px-3 py-3 rounded-4 text-dark shadow-sm"
                        id="contactMessage"
                        rows="5"
                        placeholder="Write your message..."
                        required
                    ></textarea>

                </div>


                <!-- Submit -->
                <button
                    type="submit"
                    class="btn btn-primary w-100 py-2 rounded-pill fw-bold text-uppercase shadow-sm"
                    style="letter-spacing: 0.08em; font-size: 0.95rem;"
                >
                    SUBMIT
                    <i class="bi bi-send-fill ms-1 small"></i>
                </button>

            </form>

        </div>

    </div>
</section>

<?php include_once 'shared/footer.php'; ?>

<!-- Bootstrap 5 JavaScript Bundle for Carousel functionality -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>