<?php
ini_set('display_errors', 1);
error_reporting(E_ALL);

include __DIR__ . "/../../config/database.php";

if (!isset($connection) && isset($conn)) {
    $connection = $conn;
}

$error = "";
$success = "";
$category = null;
$brands = [];
$current_brand_id = "";


/*
|--------------------------------------------------------------------------
| Check Category ID
|--------------------------------------------------------------------------
*/

if (!isset($_GET['id']) || empty($_GET['id'])) {
    header("Location: index.php");
    exit();
}

$id = intval($_GET['id']);


/*
|--------------------------------------------------------------------------
| Get Category
|--------------------------------------------------------------------------
*/

$res = mysqli_query(
    $connection,
    "SELECT * FROM categories WHERE id = $id"
);

if ($res && mysqli_num_rows($res) > 0) {

    $category = mysqli_fetch_assoc($res);

} else {

    header("Location: index.php");
    exit();
}


/*
|--------------------------------------------------------------------------
| Get All Brands
|--------------------------------------------------------------------------
*/

$brand_res = mysqli_query(
    $connection,
    "SELECT * FROM brands ORDER BY name ASC"
);

if ($brand_res) {

    while ($row = mysqli_fetch_assoc($brand_res)) {
        $brands[] = $row;
    }
}


/*
|--------------------------------------------------------------------------
| Get Current Brand
|--------------------------------------------------------------------------
*/

$curr_res = mysqli_query(
    $connection,
    "SELECT id
     FROM brands
     WHERE category_id = $id
     LIMIT 1"
);

if ($curr_res && mysqli_num_rows($curr_res) > 0) {

    $curr_row = mysqli_fetch_assoc($curr_res);

    $current_brand_id = $curr_row['id'];
}


/*
|--------------------------------------------------------------------------
| Update Category
|--------------------------------------------------------------------------
*/

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $name = mysqli_real_escape_string(
        $connection,
        $_POST['name'] ?? ''
    );

    $brand_id = intval(
        $_POST['brand_id'] ?? 0
    );


    /*
    |--------------------------------------------------------------------------
    | Validate Name
    |--------------------------------------------------------------------------
    */

    if (empty($name)) {

        $error = "Category name cannot be empty.";

    } else {

        /*
        |--------------------------------------------------------------------------
        | Keep Old Image
        |--------------------------------------------------------------------------
        */

        $imageName = $category['image'] ?? "";


        /*
        |--------------------------------------------------------------------------
        | Upload New Image
        |--------------------------------------------------------------------------
        */

        if (
            isset($_FILES['image']) &&
            $_FILES['image']['error'] === UPLOAD_ERR_OK
        ) {

            $uploadDir = __DIR__ . "/../../uploads/";


            // Create uploads folder if it doesn't exist
            if (!is_dir($uploadDir)) {

                mkdir(
                    $uploadDir,
                    0777,
                    true
                );
            }


            $originalName = $_FILES['image']['name'];
            $tmpName = $_FILES['image']['tmp_name'];
            $fileSize = $_FILES['image']['size'];

            $extension = strtolower(
                pathinfo(
                    $originalName,
                    PATHINFO_EXTENSION
                )
            );


            $allowedExtensions = [
                'jpg',
                'jpeg',
                'png',
                'webp'
            ];


            /*
            |--------------------------------------------------------------------------
            | Validate Extension
            |--------------------------------------------------------------------------
            */

            if (
                !in_array(
                    $extension,
                    $allowedExtensions
                )
            ) {

                $error =
                    "Only JPG, JPEG, PNG and WEBP images are allowed.";

            /*
            |--------------------------------------------------------------------------
            | Validate Size
            |--------------------------------------------------------------------------
            */

            } elseif (
                $fileSize > 5 * 1024 * 1024
            ) {

                $error =
                    "Image size must be less than 5MB.";

            } else {


                /*
                |--------------------------------------------------------------------------
                | Generate New Image Name
                |--------------------------------------------------------------------------
                */

                $newImageName =
                    "category_" .
                    time() .
                    "_" .
                    uniqid() .
                    "." .
                    $extension;


                $imagePath =
                    $uploadDir .
                    $newImageName;


                /*
                |--------------------------------------------------------------------------
                | Move Uploaded Image
                |--------------------------------------------------------------------------
                */

                if (
                    move_uploaded_file(
                        $tmpName,
                        $imagePath
                    )
                ) {

                    /*
                    |--------------------------------------------------------------------------
                    | Delete Old Image
                    |--------------------------------------------------------------------------
                    */

                    if (!empty($category['image'])) {

                        $oldImagePath =
                            $uploadDir .
                            $category['image'];

                        if (
                            file_exists(
                                $oldImagePath
                            )
                        ) {

                            unlink(
                                $oldImagePath
                            );
                        }
                    }


                    // Use new image
                    $imageName = $newImageName;

                } else {

                    $error =
                        "Failed to upload image.";
                }
            }
        }


        /*
        |--------------------------------------------------------------------------
        | Update Database
        |--------------------------------------------------------------------------
        */

        if (empty($error)) {

            $imageNameEscaped =
                mysqli_real_escape_string(
                    $connection,
                    $imageName
                );


            $query = "
                UPDATE categories
                SET
                    name = '$name',
                    image = '$imageNameEscaped'
                WHERE id = $id
            ";


            if (
                mysqli_query(
                    $connection,
                    $query
                )
            ) {


                /*
                |--------------------------------------------------------------------------
                | Remove Old Brand Relation
                |--------------------------------------------------------------------------
                */

                mysqli_query(
                    $connection,
                    "UPDATE brands
                     SET category_id = NULL
                     WHERE category_id = $id"
                );


                /*
                |--------------------------------------------------------------------------
                | Add New Brand Relation
                |--------------------------------------------------------------------------
                */

                if ($brand_id > 0) {

                    mysqli_query(
                        $connection,
                        "UPDATE brands
                         SET category_id = $id
                         WHERE id = $brand_id"
                    );
                }


                /*
                |--------------------------------------------------------------------------
                | Success
                |--------------------------------------------------------------------------
                */

                $success =
                    "Category updated successfully!";


                // Update displayed data
                $category['name'] = $name;
                $category['image'] = $imageName;

                $current_brand_id = $brand_id;

            } else {

                $error =
                    "Error: " .
                    mysqli_error($connection);
            }
        }
    }
}
?>


<?php include __DIR__ . "/../shared/header.php"; ?>

<body class="bg-light">

<div class="d-flex">

    <!-- Sidebar -->
    <?php include __DIR__ . "/../shared/sidebar.php"; ?>


    <div class="flex-grow-1">

        <!-- Navbar -->
        <?php include __DIR__ . "/../shared/navbar.php"; ?>


        <div class="container-fluid px-4 py-4">


            <!-- Header -->
            <div class="d-flex justify-content-between align-items-center mb-4">

                <h2 class="fw-bold">
                    Edit Category
                </h2>

                <a
                    href="index.php"
                    class="btn btn-outline-secondary btn-sm"
                >
                    <i class="fas fa-arrow-left me-1"></i>
                    Back to Categories
                </a>

            </div>


            <!-- Success Message -->
            <?php if (!empty($success)): ?>

                <div class="alert alert-success">
                    <?php
                    echo htmlspecialchars($success);
                    ?>
                </div>

            <?php endif; ?>


            <!-- Error Message -->
            <?php if (!empty($error)): ?>

                <div class="alert alert-danger">
                    <?php
                    echo htmlspecialchars($error);
                    ?>
                </div>

            <?php endif; ?>


            <!-- Card -->
            <div class="card shadow-sm border-0 rounded-3">

                <div class="card-body p-4">

                    <form
                        action=""
                        method="POST"
                        enctype="multipart/form-data"
                    >


                        <!-- Category Name -->
                        <div class="mb-3">

                            <label class="form-label fw-semibold">
                                Category Name
                            </label>

                            <input
                                type="text"
                                name="name"
                                class="form-control"
                                value="<?php
                                echo htmlspecialchars(
                                    $category['name'] ?? ''
                                );
                                ?>"
                                required
                            >

                        </div>


                        <!-- Current Image -->
                        <div class="mb-3">

                            <label class="form-label fw-semibold">
                                Current Image
                            </label>

                            <div>

                                <?php if (!empty($category['image'])): ?>

                                    <img
                                        src="../../uploads/<?php
                                        echo htmlspecialchars(
                                            $category['image']
                                        );
                                        ?>"
                                        alt="<?php
                                        echo htmlspecialchars(
                                            $category['name']
                                        );
                                        ?>"
                                        class="rounded-3 shadow-sm"
                                        style="
                                            width: 180px;
                                            height: 180px;
                                            object-fit: cover;
                                        "
                                    >

                                <?php else: ?>

                                    <div
                                        class="bg-light border rounded-3 d-flex align-items-center justify-content-center"
                                        style="
                                            width: 180px;
                                            height: 180px;
                                        "
                                    >

                                        <i
                                            class="fas fa-image text-muted"
                                            style="
                                                font-size: 60px;
                                            "
                                        ></i>

                                    </div>

                                <?php endif; ?>

                            </div>

                        </div>


                        <!-- New Image -->
                        <div class="mb-4">

                            <label class="form-label fw-semibold">
                                Change Category Image
                            </label>

                            <input
                                type="file"
                                name="image"
                                class="form-control"
                                accept="image/jpeg,image/png,image/webp"
                            >

                            <small class="text-muted">
                                JPG, JPEG, PNG or WEBP.
                                Maximum size: 5MB.
                                Leave empty to keep the current image.
                            </small>

                        </div>


                        <!-- Brand -->
                        <div class="mb-4">

                            <label class="form-label fw-semibold">
                                Select Brand
                            </label>

                            <select
                                name="brand_id"
                                class="form-select"
                            >

                                <option value="">
                                    Choose Brand (Optional)
                                </option>


                                <?php foreach ($brands as $brand): ?>

                                    <option
                                        value="<?php
                                        echo $brand['id'];
                                        ?>"
                                        <?php
                                        echo (
                                            $current_brand_id ==
                                            $brand['id']
                                        )
                                            ? 'selected'
                                            : '';
                                        ?>
                                    >

                                        <?php
                                        echo htmlspecialchars(
                                            $brand['name']
                                        );
                                        ?>

                                    </option>

                                <?php endforeach; ?>

                            </select>

                        </div>


                        <!-- Submit -->
                        <button
                            type="submit"
                            class="btn btn-primary px-4"
                        >

                            <i class="fas fa-save me-2"></i>

                            Update Category

                        </button>


                    </form>

                </div>

            </div>

        </div>

    </div>

</div>


<!-- Bootstrap JS -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>

</body>
</html>

