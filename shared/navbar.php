<?php

// Start session if it hasn't started yet
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

$isLoggedIn = isset($_SESSION['user_id']);
$username = $_SESSION['username'] ?? '';
$role = $_SESSION['role'] ?? 'user';

?>

<nav class="navbar navbar-expand-lg navbar-light bg-white sticky-top shadow-sm py-3">

    <div class="container-fluid px-lg-5">

        <!-- Logo -->

        <a
            class="navbar-brand d-flex align-items-center gap-2 fw-bold text-primary fs-4"
            href="/laptop-store/index.php"
        >
            <i class="bi bi-laptop-fill fs-3"></i>
            NovaTech
        </a>


        <!-- Mobile Toggle Button -->

        <button
            class="navbar-toggler"
            type="button"
            data-bs-toggle="collapse"
            data-bs-target="#navbarNav"
        >
            <span class="navbar-toggler-icon"></span>
        </button>


        <!-- Navbar Links -->

        <div class="collapse navbar-collapse" id="navbarNav">

            <ul class="navbar-nav mx-auto mb-2 mb-lg-0 fw-medium">

                <li class="nav-item">
                    <a
                        class="nav-link px-3"
                        href="/laptop-store/index.php"
                    >
                        Home
                    </a>
                </li>


                <li class="nav-item">
                    <a
                        class="nav-link px-3"
                        href="/laptop-store/products/index.php"
                    >
                        Shop
                    </a>
                </li>


                <li class="nav-item">
                    <a
                        class="nav-link px-3"
                        href="/laptop-store/index.php#categories"
                    >
                        Categories
                    </a>
                </li>


                <li class="nav-item">
                    <a
                        class="nav-link px-3"
                        href="/laptop-store/index.php#products"
                    >
                        Products
                    </a>
                </li>


                <li class="nav-item">
                    <a
                        class="nav-link px-3"
                        href="/laptop-store/index.php#brands"
                    >
                        Brands
                    </a>
                </li>


                <li class="nav-item">
                    <a
                        class="nav-link px-3"
                        href="/laptop-store/index.php#team"
                    >
                        Team
                    </a>
                </li>


                <li class="nav-item">
                    <a
                        class="nav-link px-3"
                        href="/laptop-store/index.php#about"
                    >
                        About
                    </a>
                </li>


                <li class="nav-item">
                    <a
                        class="nav-link px-3"
                        href="/laptop-store/index.php#contact"
                    >
                        Contact
                    </a>
                </li>

            </ul>


            <!-- Navbar Icons -->

            <div class="d-flex align-items-center gap-3">


                <!-- Cart -->

                <?php if ($isLoggedIn): ?>

                    <a
                        href="/laptop-store/cart/index.php"
                        class="text-dark fs-5 text-decoration-none px-1"
                        title="Cart"
                    >
                        <i class="bi bi-cart3"></i>
                    </a>

                <?php endif; ?>


                <!-- Login / User -->

                <?php if (!$isLoggedIn): ?>

                    <!-- Login Button -->

                    <a
                        href="/laptop-store/auth/login.php"
                        class="btn btn-dark px-3 py-2 rounded-3 fw-semibold"
                    >
                        <i class="bi bi-box-arrow-in-right me-1"></i>
                        Login
                    </a>


                <?php else: ?>


                    <!-- Logged In User -->

                    <div class="dropdown">

                        <button
                            class="btn btn-outline-dark dropdown-toggle px-3 py-2 rounded-3 fw-semibold"
                            type="button"
                            data-bs-toggle="dropdown"
                            aria-expanded="false"
                        >

                            <i class="bi bi-person-circle me-1"></i>

                            <?= htmlspecialchars($username) ?>

                        </button>


                        <ul class="dropdown-menu dropdown-menu-end shadow-sm border-0 rounded-3">


                            <?php if ($role === 'admin'): ?>

                                <!-- Admin Dashboard -->

                                <li>

                                    <a
                                        class="dropdown-item py-2"
                                        href="/laptop-store/admin/index.php"
                                    >
                                        <i class="bi bi-speedometer2 me-2"></i>
                                        Admin Dashboard
                                    </a>

                                </li>

                                <li>
                                    <hr class="dropdown-divider">
                                </li>

                            <?php endif; ?>


                            <!-- My Orders -->

                            <li>

                                <a
                                    class="dropdown-item py-2"
                                    href="/laptop-store/orders/index.php"
                                >
                                    <i class="bi bi-bag-check me-2"></i>
                                    My Orders
                                </a>

                            </li>


                            <!-- Logout -->

                            <li>

                                <a
                                    class="dropdown-item py-2 text-danger"
                                    href="/laptop-store/auth/logout.php"
                                >
                                    <i class="bi bi-box-arrow-right me-2"></i>
                                    Logout
                                </a>

                            </li>


                        </ul>

                    </div>


                <?php endif; ?>


            </div>

        </div>

    </div>

</nav>

