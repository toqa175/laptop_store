```php
<?php
ini_set('display_errors', 1);
error_reporting(E_ALL);

include __DIR__ . "/../../config/database.php";

if (!isset($connection) && isset($conn)) {
    $connection = $conn;
}

$error = "";
$success = "";
$member = null;

// Check ID
if (!isset($_GET['id']) || empty($_GET['id'])) {
    header("Location: index.php");
    exit();
}

$id = intval($_GET['id']);

// Get member
$res = mysqli_query(
    $connection,
    "SELECT * FROM team WHERE id = $id"
);

if ($res && mysqli_num_rows($res) > 0) {
    $member = mysqli_fetch_assoc($res);
} else {
    header("Location: index.php");
    exit();
}


// =========================
// Update Member
// =========================
if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $name = mysqli_real_escape_string(
        $connection,
        $_POST['name'] ?? ''
    );

    $role = mysqli_real_escape_string(
        $connection,
        $_POST['role'] ?? ''
    );

    $bio = mysqli_real_escape_string(
        $connection,
        $_POST['bio'] ?? ''
    );


    if (empty($name) || empty($role)) {

        $error = "Name and Role are required fields.";

    } else {

        // Keep old image by default
        $imageName = $member['image'] ?? '';


        // =========================
        // Upload New Image
        // =========================
        if (
            isset($_FILES['image']) &&
            $_FILES['image']['error'] === UPLOAD_ERR_OK
        ) {

            $uploadDir = __DIR__ . "/../../uploads/";

            // Create uploads folder if not exists
            if (!is_dir($uploadDir)) {
                mkdir($uploadDir, 0777, true);
            }

            $originalName = $_FILES['image']['name'];
            $tmpName = $_FILES['image']['tmp_name'];
            $fileSize = $_FILES['image']['size'];

            $extension = strtolower(
                pathinfo($originalName, PATHINFO_EXTENSION)
            );

            $allowedExtensions = [
                'jpg',
                'jpeg',
                'png',
                'webp'
            ];


            if (!in_array($extension, $allowedExtensions)) {

                $error = "Only JPG, JPEG, PNG and WEBP images are allowed.";

            } elseif ($fileSize > 5 * 1024 * 1024) {

                $error = "Image size must be less than 5MB.";

            } else {

                // New unique image name
                $newImageName =
                    'team_' .
                    time() .
                    '_' .
                    uniqid() .
                    '.' .
                    $extension;

                $imagePath = $uploadDir . $newImageName;


                if (move_uploaded_file($tmpName, $imagePath)) {

                    // Delete old image
                    if (!empty($member['image'])) {

                        $oldImagePath =
                            $uploadDir . $member['image'];

                        if (file_exists($oldImagePath)) {
                            unlink($oldImagePath);
                        }
                    }

                    $imageName = $newImageName;

                } else {

                    $error = "Failed to upload image.";
                }
            }
        }


        // =========================
        // Update Database
        // =========================
        if (empty($error)) {

            $imageNameEscaped = mysqli_real_escape_string(
                $connection,
                $imageName
            );

            $query = "
                UPDATE team
                SET
                    name = '$name',
                    role = '$role',
                    bio = '$bio',
                    image = '$imageNameEscaped'
                WHERE id = $id
            ";


            if (mysqli_query($connection, $query)) {

                $success = "Team member updated successfully!";

                // Update displayed data
                $member['name'] = $name;
                $member['role'] = $role;
                $member['bio'] = $bio;
                $member['image'] = $imageName;

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

            <!-- Header -->
            <div class="d-flex justify-content-between align-items-center mb-4">

                <h2 class="fw-bold">
                    Edit Team Member
                </h2>

                <a
                    href="index.php"
                    class="btn btn-outline-secondary btn-sm"
                >
                    <i class="fas fa-arrow-left me-1"></i>
                    Back
                </a>

            </div>


            <!-- Success -->
            <?php if (!empty($success)): ?>

                <div class="alert alert-success">
                    <?php echo htmlspecialchars($success); ?>
                </div>

            <?php endif; ?>


            <!-- Error -->
            <?php if (!empty($error)): ?>

                <div class="alert alert-danger">
                    <?php echo htmlspecialchars($error); ?>
                </div>

            <?php endif; ?>


            <div class="card shadow-sm border-0 rounded-3">

                <div class="card-body p-4">

                    <form
                        action=""
                        method="POST"
                        enctype="multipart/form-data"
                    >


                        <!-- Name -->
                        <div class="mb-3">

                            <label class="form-label fw-semibold">
                                Member Name
                            </label>

                            <input
                                type="text"
                                name="name"
                                class="form-control"
                                value="<?php echo htmlspecialchars($member['name'] ?? ''); ?>"
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
                                value="<?php echo htmlspecialchars($member['role'] ?? ''); ?>"
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
                            ><?php echo htmlspecialchars($member['bio'] ?? ''); ?></textarea>

                        </div>


                        <!-- Current Image -->
                        <div class="mb-3">

                            <label class="form-label fw-semibold">
                                Current Image
                            </label>

                            <div>

                                <?php if (!empty($member['image'])): ?>

                                    <img
                                        src="../../uploads/<?php echo htmlspecialchars($member['image']); ?>"
                                        alt="<?php echo htmlspecialchars($member['name']); ?>"
                                        class="rounded-3 shadow-sm"
                                        style="width: 180px; height: 180px; object-fit: cover;"
                                    >

                                <?php else: ?>

                                    <div
                                        class="bg-light border rounded-3 d-flex align-items-center justify-content-center"
                                        style="width: 180px; height: 180px;"
                                    >
                                        <i
                                            class="bi bi-person-circle text-secondary"
                                            style="font-size: 70px;"
                                        ></i>
                                    </div>

                                <?php endif; ?>

                            </div>

                        </div>


                        <!-- New Image -->
                        <div class="mb-4">

                            <label class="form-label fw-semibold">
                                Change Image
                            </label>

                            <input
                                type="file"
                                name="image"
                                class="form-control"
                                accept="image/jpeg,image/png,image/webp"
                            >

                            <small class="text-muted">
                                JPG, JPEG, PNG or WEBP. Maximum size: 5MB.
                                Leave empty to keep the current image.
                            </small>

                        </div>


                        <!-- Submit -->
                        <button
                            type="submit"
                            class="btn btn-primary px-4"
                        >
                            <i class="fas fa-save me-2"></i>
                            Update Member
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
```
