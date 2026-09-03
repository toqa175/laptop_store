<!-- الهيدر العلوي -->
<nav class="navbar navbar-expand-lg navbar-light bg-white shadow-sm px-4 py-3 mb-4">
    <div class="container-fluid">

        <div>
            <h4 class="fw-bold mb-0">Dashboard</h4>
            <small class="text-muted">Welcome back, Admin</small>
        </div>


            <!-- Admin Dropdown -->
            <div class="dropdown">

                <a
                    href="#"
                    class="d-flex align-items-center text-dark text-decoration-none dropdown-toggle"
                    data-bs-toggle="dropdown"
                    aria-expanded="false"
                >

                    <span class="badge bg-secondary rounded-pill me-2">3</span>

                    <div
                        class="bg-secondary text-white rounded-circle d-flex align-items-center justify-content-center fw-bold"
                        style="width: 35px; height: 35px;"
                    >
                        A
                    </div>

                    <span class="ms-2 fw-semibold">
                        Admin User
                    </span>

                </a>

                <!-- Dropdown Menu -->
                <ul class="dropdown-menu dropdown-menu-end shadow-sm border-0 mt-2">

                    <li>
                        <a
                            class="dropdown-item py-2"
                            href="/laptop-store/index.php"
                        >
                            <i class="fas fa-home me-2 text-muted"></i>
                            Home
                        </a>
                    </li>

                    <li>
                        <hr class="dropdown-divider">
                    </li>

                    <li>
                        <a
                            class="dropdown-item py-2 text-danger"
                            href="/laptop-store/admin/logout.php"
                        >
                            <i class="fas fa-sign-out-alt me-2"></i>
                            Logout
                        </a>
                    </li>

                </ul>

            </div>

        </div>

    </div>
</nav>

