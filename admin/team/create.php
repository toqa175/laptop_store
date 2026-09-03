<?php

ini_set('display_errors', 1);
error_reporting(E_ALL);

include __DIR__ . "/../../config/database.php";

if (!isset($connection) && isset($conn)) {
    $connection = $conn;
}

$error = "";
$success = "";

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $name = mysqli_real_escape_string($connection, $_POST['name'] ?? '');
    $role = mysqli_real_escape_string($connection, $_POST['role'] ?? '');
    $bio  = mysqli_real_escape_string($connection, $_POST['bio'] ?? '');

    if (empty($name) || empty($role)) {

        $error = "Name and Role are required fields.";

    } else {

        $imageName = "";

        // Upload Image
        if (isset($_FILES['image']) && $_FILES['image']['error'] === UPLOAD_ERR_OK) {

            $uploadDir = __DIR__ . "/../../uploads/";

            // Create uploads folder if it doesn't exist
            if (!is_dir($uploadDir)) {
                mkdir($uploadDir, 0777, true);
            }

            $originalName = $_FILES['image']['name'];
            $tmpName = $_FILES['image']['tmp_name'];
            $fileSize = $_FILES['image']['size'];

            $extension = strtolower(pathinfo($originalName, PATHINFO_EXTENSION));

            $allowedExtensions = ['jpg', 'jpeg', 'png', 'webp'];

            if (!in_array($extension, $allowedExtensions)) {

                $error = "Only JPG, JPEG, PNG and WEBP images are allowed.";

            } elseif ($fileSize > 5 * 1024 * 1024) {

                $error = "Image size must be less than 5MB.";

            } else {

                // Generate unique image name
                $imageName = 'team_' . time() . '_' . uniqid() . '.' . $extension;

                $imagePath = $uploadDir . $imageName;

                if (move_uploaded_file($tmpName, $imagePath)) {

                    $query = "
                        INSERT INTO team
                        (name, role, bio, image, created_at)
                        VALUES
                        ('$name', '$role', '$bio', '$imageName', NOW())
                    ";

                    if (mysqli_query($connection, $query)) {

                        $success = "Team member added successfully!";

                    } else {

                        // Delete uploaded image if database insert fails
                        if (file_exists($imagePath)) {
                            unlink($imagePath);
                        }

                        $error = "Error: " . mysqli_error($connection);
                    }

                } else {

                    $error = "Failed to upload image.";
                }
            }

        } else {

            // Add member without image
            $query = "
                INSERT INTO team
                (name, role, bio, image, created_at)
                VALUES
                ('$name', '$role', '$bio', '', NOW())
            ";

            if (mysqli_query($connection, $query)) {

                $success = "Team member added successfully!";

            } else {

                $error = "Error: " . mysqli_error($connection);
            }
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

                <h2 class="fw-bold">
                    Add Team Member
                </h2>

                <a href="index.php" class="btn btn-outline-secondary btn-sm">
                    <i class="fas fa-arrow-left me-1"></i>
                    Back
                </a>

            </div>


            <!-- Success Message -->
            <?php if (!empty($success)): ?>

                <div class="alert alert-success">
                    <?php echo htmlspecialchars($success); ?>
                </div>

            <?php endif; ?>


            <!-- Error Message -->
            <?php if (!empty($error)): ?>

                <div class="alert alert-danger">
                    <?php echo htmlspecialchars($error); ?>
                </div>

            <?php endif; ?>


            <div class="card shadow-sm border-0 rounded-3">

                <div class="card-body p-4">

                    <form action="" method="POST" enctype="multipart/form-data">

                        <!-- Name -->
                        <div class="mb-3">

                            <label class="form-label fw-semibold">
                                Member Name
                            </label>

                            <input
                                type="text"
                                name="name"
                                class="form-control"
                                placeholder="Enter member name"
                                required
                            >

                        </div>


                        <!-- Role -->
                        <div class="mb-3">

                            <label class="form-label fw-semibold">
                                Role / Job Title
                            </label>

                            <input
                                type="text"
                                name="role"
                                class="form-control"
                                placeholder="Enter role or job title"
                                required
                            >

                        </div>


                        <!-- Bio -->
                        <div class="mb-3">

                            <label class="form-label fw-semibold">
                                Bio
                            </label>

                            <textarea
                                name="bio"
                                class="form-control"
                                rows="4"
                                placeholder="Enter team member bio"
                            ></textarea>

                        </div>


                        <!-- Image -->
                        <div class="mb-4">

                            <label class="form-label fw-semibold">
                                Team Member Image
                            </label>

                            <input
                                type="file"
                                name="image"
                                class="form-control"
                                accept="image/jpeg,image/png,image/webp"
                            >

                            <small class="text-muted">
                                JPG, JPEG, PNG or WEBP. Maximum size: 5MB.
                            </small>

                        </div>


                        <!-- Submit -->
                        <button
                            type="submit"
                            class="btn btn-primary px-4"
                        >
                            <i class="fas fa-save me-2"></i>
                            Save Member
                        </button>

                    </form>

                </div>

            </div>

        </div>

    </div>

</div>


<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>

</body>
</html>
