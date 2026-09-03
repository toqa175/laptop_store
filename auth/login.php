<?php 
session_start(); 
 
require_once '../config/database.php'; 
 
$error = ''; 
 
if ($_SERVER['REQUEST_METHOD'] === 'POST') { 
 
    $username = trim($_POST['username'] ?? ''); 
    $password = $_POST['password'] ?? ''; 
 
    // Validation
    if (empty($username) || empty($password)) { 
 
        $error = "Please enter username and password."; 
 
    } else { 
 
        // Find user by username
        $stmt = $connection->prepare( 
            "SELECT id, username, password, full_name, role 
             FROM users 
             WHERE username = ?" 
        ); 
 
        $stmt->bind_param("s", $username); 
        $stmt->execute(); 
 
        $result = $stmt->get_result(); 
 
        if ($result->num_rows === 1) { 
 
            $user = $result->fetch_assoc(); 
 
            // Check password without hashing
            if ($password === $user['password']) { 
 
               // Store user data in session
                $_SESSION['user_id'] = $user['id'];
                $_SESSION['username'] = $user['username'];
                $_SESSION['full_name'] = $user['full_name'];
                $_SESSION['role'] = $user['role'];

                // Redirect based on role
                if ($user['role'] === 'admin') {

                    header("Location: ../admin/index.php");
                    exit;

                } else {

                    header("Location: ../index.php");
                    exit;
                }
 
            } else { 
 
                $error = "Incorrect password."; 
 
            } 
 
        } else { 
 
            $error = "Username not found."; 
 
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
 
                                <i class="bi bi-box-arrow-in-right fs-2"></i> 
 
                            </div> 
 
                            <h2 class="fw-bold text-dark"> 
                                Welcome Back 
                            </h2> 
 
                            <p class="text-muted mb-0"> 
                                Login to your NovaTech account. 
                            </p> 
 
                        </div> 
 
 
                        <!-- Error Message -->
 
                        <?php if (!empty($error)): ?> 
 
                            <div class="alert alert-danger rounded-3"> 
                                <?= htmlspecialchars($error) ?> 
                            </div> 
 
                        <?php endif; ?> 
 
 
                        <!-- Login Form -->
 
                        <form method="POST"> 
 
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
 
 
                            <!-- Password -->
 
                            <div class="mb-4"> 
 
                                <label class="form-label fw-semibold"> 
                                    Password * 
                                </label> 
 
                                <input 
                                    type="password" 
                                    name="password" 
                                    class="form-control form-control-lg" 
                                    required 
                                > 
 
                            </div> 
 
 
                            <!-- Login Button -->
 
                            <button 
                                type="submit" 
                                class="btn btn-primary btn-lg w-100 rounded-3 fw-bold" 
                            > 
 
                                <i class="bi bi-box-arrow-in-right me-2"></i> 
 
                                Login 
 
                            </button> 
 
                        </form> 
 
 
                        <!-- Register Link -->
 
                        <div class="text-center mt-4"> 
 
                            <p class="text-muted mb-0"> 
 
                                Don't have an account? 
 
                                <a 
                                    href="register.php" 
                                    class="text-primary fw-bold text-decoration-none" 
                                > 
                                    Create Account 
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