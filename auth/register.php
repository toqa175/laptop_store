<?php
session_start();

require_once '../config/database.php';

$error = '';
$success = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $username  = trim($_POST['username'] ?? '');
    $email     = trim($_POST['email'] ?? '');
    $password  = $_POST['password'] ?? '';
    $full_name = trim($_POST['full_name'] ?? '');
    $address   = trim($_POST['address'] ?? '');

    // Validation
    if (empty($username) || empty($email) || empty($password)) {

        $error = "Please fill in all required fields.";

    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {

        $error = "Please enter a valid email address.";

    } elseif (strlen($password) < 6) {

        $error = "Password must be at least 6 characters.";

    } else {

        // Check if username already exists
        $checkUsername = $connection->prepare(
            "SELECT id FROM users WHERE username = ?"
        );

        $checkUsername->bind_param("s", $username);
        $checkUsername->execute();

        $usernameResult = $checkUsername->get_result();

        if ($usernameResult->num_rows > 0) {

            $error = "Username already exists.";

        } else {

            // Check if email already exists
            $checkEmail = $connection->prepare(
                "SELECT id FROM users WHERE email = ?"
            );

            $checkEmail->bind_param("s", $email);
            $checkEmail->execute();

            $emailResult = $checkEmail->get_result();

            if ($emailResult->num_rows > 0) {

                $error = "Email already registered.";

            } else {

                // Store password without hashing
                $plainPassword = $password;

                // Insert new user
                $stmt = $connection->prepare(
                    "INSERT INTO users
                    (username, email, password, full_name, address, role)
                    VALUES (?, ?, ?, ?, ?, 'user')"
                );

                $stmt->bind_param(
                    "sssss",
                    $username,
                    $email,
                    $plainPassword,
                    $full_name,
                    $address
                );

                if ($stmt->execute()) {

                    $success = "Account created successfully! You can now login.";

                } else {

                    $error = "Something went wrong. Please try again.";

                }
            }
        }
    }
}
?>

<?php include_once '../shared/header.php'; ?>

<?php include_once '../shared/navbar.php'; ?>


<section class="min-vh-100 d-flex align-items-center bg-light py-5">

    <div class="container">

        <div class="row justify-content-center">

            <div class="col-12 col-md-8 col-lg-6 col-xl-5">

                <div class="card border-0 shadow-sm rounded-4 overflow-hidden">

                    <div class="card-body p-4 p-md-5">

                        <!-- Header -->

                        <div class="text-center mb-4">

                            <div
                                class="bg-primary text-white rounded-circle d-inline-flex align-items-center justify-content-center mb-3"
                                style="width: 65px; height: 65px;"
                            >

                                <i class="bi bi-person-plus fs-2"></i>

                            </div>

                            <h2 class="fw-bold text-dark">
                                Create Account
                            </h2>

                            <p class="text-muted mb-0">
                                Join NovaTech and start shopping.
                            </p>

                        </div>


                        <!-- Error Message -->

                        <?php if (!empty($error)): ?>

                            <div class="alert alert-danger rounded-3">

                                <?= htmlspecialchars($error) ?>

                            </div>

                        <?php endif; ?>


                        <!-- Success Message -->

                        <?php if (!empty($success)): ?>

                            <div class="alert alert-success rounded-3">

                                <?= htmlspecialchars($success) ?>

                            </div>

                        <?php endif; ?>


                        <!-- Register Form -->

                        <form method="POST">

                            <!-- Full Name -->

                            <div class="mb-3">

                                <label class="form-label fw-semibold">
                                    Full Name
                                </label>

                                <input
                                    type="text"
                                    name="full_name"
                                    class="form-control form-control-lg"
                                    value="<?= htmlspecialchars($_POST['full_name'] ?? '') ?>"
                                >

                            </div>


                            <!-- Username -->

                            <div class="mb-3">

                                <label class="form-label fw-semibold">
                                    Username *
                                </label>

                                <input
                                    type="text"
                                    name="username"
                                    class="form-control form-control-lg"
                                    value="<?= htmlspecialchars($_POST['username'] ?? '') ?>"
                                    required
                                >

                            </div>


                            <!-- Email -->

                            <div class="mb-3">

                                <label class="form-label fw-semibold">
                                    Email *
                                </label>

                                <input
                                    type="email"
                                    name="email"
                                    class="form-control form-control-lg"
                                    value="<?= htmlspecialchars($_POST['email'] ?? '') ?>"
                                    required
                                >

                            </div>


                            <!-- Password -->

                            <div class="mb-3">

                                <label class="form-label fw-semibold">
                                    Password *
                                </label>

                                <input
                                    type="password"
                                    name="password"
                                    class="form-control form-control-lg"
                                    required
                                >

                                <small class="text-muted">
                                    Password must be at least 6 characters.
                                </small>

                            </div>


                            <!-- Address -->

                            <div class="mb-4">

                                <label class="form-label fw-semibold">
                                    Address
                                </label>

                                <textarea
                                    name="address"
                                    class="form-control"
                                    rows="3"
                                ><?= htmlspecialchars($_POST['address'] ?? '') ?></textarea>

                            </div>


                            <!-- Register Button -->

                            <button
                                type="submit"
                                class="btn btn-primary btn-lg w-100 rounded-3 fw-bold"
                            >

                                <i class="bi bi-person-plus me-2"></i>

                                Create Account

                            </button>

                        </form>


                        <!-- Login Link -->

                        <div class="text-center mt-4">

                            <p class="text-muted mb-0">

                                Already have an account?

                                <a
                                    href="login.php"
                                    class="text-primary fw-bold text-decoration-none"
                                >
                                    Login
                                </a>

                            </p>

                        </div>

                    </div>

                </div>

            </div>

        </div>

    </div>

</section>


<?php include_once '../shared/footer.php'; ?>