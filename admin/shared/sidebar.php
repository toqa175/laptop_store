<div class="d-flex flex-column flex-shrink-0 p-3 text-white bg-dark" style="width: 260px; min-height: 100vh;">
    <a href="#" class="d-flex align-items-center mb-3 mb-md-0 me-md-auto text-white text-decoration-none">
        <i class="fas fa-laptop me-2 fs-4 text-primary"></i>
        <span class="fs-5 fw-bold">LAPTOP STORE</span>
    </a>
    <hr>

    <small class="text-muted fw-bold px-2 mb-2" style="font-size: 0.75rem;">DASHBOARD</small>
    <ul class="nav nav-pills flex-column mb-3">
        <li class="nav-item">
            <a href="/laptop-store/admin/index.php" class="nav-link active text-white bg-primary">
                <i class="fas fa-home me-2"></i> Dashboard
            </a>
        </li>
    </ul>

    <small class="text-muted fw-bold px-2 mb-2" style="font-size: 0.75rem;">PRODUCT MANAGEMENT</small>
    <ul class="nav nav-pills flex-column mb-3">
        <!-- Products Dropdown -->
        <li class="nav-item mb-1">
            <a class="nav-link text-white d-flex justify-content-between align-items-center" data-bs-toggle="collapse" href="#productsMenu" role="button" aria-expanded="false" aria-controls="productsMenu">
                <span><i class="fas fa-box me-2"></i> Products</span>
                <i class="fas fa-chevron-down small"></i>
            </a>
            <div class="collapse ps-3 mt-1" id="productsMenu">
                <ul class="nav flex-column">
                    <li class="nav-item mb-1"><a href="/laptop-store/admin/products/index.php" class="nav-link text-white-50 py-1"><i class="fas fa-list me-2"></i> List Products</a></li>
                    <li class="nav-item"><a href="/laptop-store/admin/products/create.php" class="nav-link text-white-50 py-1"><i class="fas fa-plus me-2"></i> Add Product</a></li>
                </ul>
            </div>
        </li>

        <!-- Categories Dropdown -->
        <li class="nav-item mb-1">
            <a class="nav-link text-white d-flex justify-content-between align-items-center" data-bs-toggle="collapse" href="#categoriesMenu" role="button" aria-expanded="false" aria-controls="categoriesMenu">
                <span><i class="fas fa-list-alt me-2"></i> Categories</span>
                <i class="fas fa-chevron-down small"></i>
            </a>
            <div class="collapse ps-3 mt-1" id="categoriesMenu">
                <ul class="nav flex-column">
                    <li class="nav-item mb-1"><a href="/laptop-store/admin/categories/index.php" class="nav-link text-white-50 py-1"><i class="fas fa-list me-2"></i> List Categories</a></li>
                    <li class="nav-item"><a href="/laptop-store/admin/categories/create.php" class="nav-link text-white-50 py-1"><i class="fas fa-plus me-2"></i> Add Category</a></li>
                </ul>
            </div>
        </li>

        <!-- Brands Dropdown -->
        <li class="nav-item">
            <a class="nav-link text-white d-flex justify-content-between align-items-center" data-bs-toggle="collapse" href="#brandsMenu" role="button" aria-expanded="false" aria-controls="brandsMenu">
                <span><i class="fas fa-tags me-2"></i> Brands</span>
                <i class="fas fa-chevron-down small"></i>
            </a>
            <div class="collapse ps-3 mt-1" id="brandsMenu">
                <ul class="nav flex-column">
                    <li class="nav-item mb-1"><a href="/laptop-store/admin/brands/index.php" class="nav-link text-white-50 py-1"><i class="fas fa-list me-2"></i> List Brands</a></li>
                    <li class="nav-item"><a href="/laptop-store/admin/brands/create.php" class="nav-link text-white-50 py-1"><i class="fas fa-plus me-2"></i> Add Brand</a></li>
                </ul>
            </div>
        </li>
    </ul>

    <small class="text-muted fw-bold px-2 mb-2" style="font-size: 0.75rem;">STORE</small>
    <ul class="nav nav-pills flex-column mb-3">
        <!-- Orders Dropdown -->
        <li class="nav-item mb-1">
            <a class="nav-link text-white d-flex justify-content-between align-items-center" data-bs-toggle="collapse" href="#ordersMenu" role="button" aria-expanded="false" aria-controls="ordersMenu">
                <span><i class="fas fa-shopping-cart me-2"></i> Orders</span>
                <i class="fas fa-chevron-down small"></i>
            </a>
            <div class="collapse ps-3 mt-1" id="ordersMenu">
                <ul class="nav flex-column">
                    <li class="nav-item mb-1"><a href="/laptop-store/admin/orders/index.php" class="nav-link text-white-50 py-1"><i class="fas fa-list me-2"></i> All Orders</a></li>
                </ul>
            </div>
        </li>
        <!-- Clients Dropdown -->
        <li class="nav-item mb-1">
            <a class="nav-link text-white d-flex justify-content-between align-items-center" data-bs-toggle="collapse" href="#clientsMenu" role="button" aria-expanded="false" aria-controls="clientsMenu">
                <span><i class="fas fa-handshake me-2"></i> Clients</span>
                <i class="fas fa-chevron-down small"></i>
            </a>
            <div class="collapse ps-3 mt-1" id="clientsMenu">
                <ul class="nav flex-column">
                    <li class="nav-item mb-1"><a href="/laptop-store/admin/clients/index.php" class="nav-link text-white-50 py-1"><i class="fas fa-list me-2"></i> List Clients</a></li>
                    <li class="nav-item"><a href="/laptop-store/admin/clients/create.php" class="nav-link text-white-50 py-1"><i class="fas fa-plus me-2"></i> Add Client</a></li>
                </ul>
            </div>
        </li>

        <!-- Users Dropdown -->
        <li class="nav-item">
            <a class="nav-link text-white d-flex justify-content-between align-items-center" data-bs-toggle="collapse" href="#usersMenu" role="button" aria-expanded="false" aria-controls="usersMenu">
                <span><i class="fas fa-users me-2"></i> Users</span>
                <i class="fas fa-chevron-down small"></i>
            </a>
            <div class="collapse ps-3 mt-1" id="usersMenu">
                <ul class="nav flex-column">
                    <li class="nav-item mb-1"><a href="/laptop-store/admin/users/index.php" class="nav-link text-white-50 py-1"><i class="fas fa-list me-2"></i> List Users</a></li>
                    <li class="nav-item"><a href="/laptop-store/admin/users/create.php" class="nav-link text-white-50 py-1"><i class="fas fa-plus me-2"></i> Add User</a></li>
                </ul>
            </div>
        </li>
    </ul>

    <small class="text-muted fw-bold px-2 mb-2" style="font-size: 0.75rem;">CONTENT</small>
    <ul class="nav nav-pills flex-column mb-auto">
        <!-- Team Dropdown -->
        <li class="nav-item mb-1">
            <a class="nav-link text-white d-flex justify-content-between align-items-center" data-bs-toggle="collapse" href="#teamMenu" role="button" aria-expanded="false" aria-controls="teamMenu">
                <span><i class="fas fa-user-shield me-2"></i> Team</span>
                <i class="fas fa-chevron-down small"></i>
            </a>
            <div class="collapse ps-3 mt-1" id="teamMenu">
                <ul class="nav flex-column">
                    <li class="nav-item mb-1"><a href="/laptop-store/admin/team/index.php" class="nav-link text-white-50 py-1"><i class="fas fa-list me-2"></i> List Team</a></li>
                    <li class="nav-item"><a href="/laptop-store/admin/team/create.php" class="nav-link text-white-50 py-1"><i class="fas fa-plus me-2"></i> Add Team Member</a></li>
                </ul>
            </div>
        </li>

        <!-- Partners Dropdown -->
        <li class="nav-item">
            <a class="nav-link text-white d-flex justify-content-between align-items-center" data-bs-toggle="collapse" href="#partnersMenu" role="button" aria-expanded="false" aria-controls="partnersMenu">
                <span><i class="fas fa-handshake-angle me-2"></i> Partners</span>
                <i class="fas fa-chevron-down small"></i>
            </a>
            <div class="collapse ps-3 mt-1" id="partnersMenu">
                <ul class="nav flex-column">
                    <li class="nav-item mb-1"><a href="/laptop-store/admin/partners/index.php" class="nav-link text-white-50 py-1"><i class="fas fa-list me-2"></i> List Partners</a></li>
                    <li class="nav-item"><a href="/laptop-store/admin/partners/create.php" class="nav-link text-white-50 py-1"><i class="fas fa-plus me-2"></i> Add Partner</a></li>
                </ul>
            </div>
        </li>

        <a
            href="/laptop-store/admin/contact/index.php"
            class="nav-link">
            <i class="bi bi-envelope me-2"></i>
            Contacts
        </a>
    </ul>

    <hr>
    <div>
        <a href="/laptop-store/admin/logout.php" class="nav-link text-danger">
            <i class="fas fa-sign-out-alt me-2"></i> Logout
        </a>
    </div>
</div>