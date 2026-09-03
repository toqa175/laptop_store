<?php
ini_set('display_errors', 1);
error_reporting(E_ALL);

include __DIR__ . "/../../config/database.php";

if (!isset($connection) && isset($conn)) {
    $connection = $conn;
}

$categories = [];

if ($connection) {

    // Get categories with their associated brands
    $sql = "
        SELECT
            categories.*,
            GROUP_CONCAT(brands.name SEPARATOR ', ') AS brands_list
        FROM categories
        LEFT JOIN brands
            ON categories.id = brands.category_id
        GROUP BY categories.id
        ORDER BY categories.id DESC
    ";

    $result = mysqli_query($connection, $sql);

    if ($result) {

        while ($row = mysqli_fetch_assoc($result)) {
            $categories[] = $row;
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

            <!-- Page Header -->
            <div class="d-flex justify-content-between align-items-center mb-4">

                <h2 class="fw-bold">
                    Manage Categories
                </h2>

                <a
                    href="create.php"
                    class="btn btn-primary"
                >
                    <i class="fas fa-plus me-2"></i>
                    Add New Category
                </a>

            </div>


            <!-- Categories Card -->
            <div class="card shadow-sm border-0 rounded-3">

                <div class="card-body p-0">

                    <div class="table-responsive">

                        <table class="table table-hover mb-0 align-middle">

                            <thead class="table-light">

                                <tr>

                                    <th class="py-3 px-3">
                                        ID
                                    </th>

                                    <th class="py-3">
                                        Image
                                    </th>

                                    <th class="py-3">
                                        Category Name
                                    </th>

                                    <th class="py-3">
                                        Associated Brands
                                    </th>

                                    <th class="py-3">
                                        Created At
                                    </th>

                                    <th class="py-3 text-center">
                                        Actions
                                    </th>

                                </tr>

                            </thead>


                            <tbody>

                                <?php if (!empty($categories)): ?>

                                    <?php foreach ($categories as $cat): ?>

                                        <tr>

                                            <!-- ID -->
                                            <td class="px-3 fw-semibold">
                                                <?php echo $cat['id']; ?>
                                            </td>


                                            <!-- Image -->
                                            <td>

                                                <?php if (!empty($cat['image'])): ?>

                                                    <img
                                                        src="../../uploads/<?php echo htmlspecialchars($cat['image']); ?>"
                                                        alt="<?php echo htmlspecialchars($cat['name']); ?>"
                                                        class="rounded-3 shadow-sm"
                                                        style="
                                                            width: 70px;
                                                            height: 70px;
                                                            object-fit: cover;
                                                        "
                                                    >

                                                <?php else: ?>

                                                    <div
                                                        class="bg-light border rounded-3 d-flex align-items-center justify-content-center"
                                                        style="
                                                            width: 70px;
                                                            height: 70px;
                                                        "
                                                    >
                                                        <i
                                                            class="fas fa-image text-muted"
                                                            style="font-size: 24px;"
                                                        ></i>
                                                    </div>

                                                <?php endif; ?>

                                            </td>


                                            <!-- Category Name -->
                                            <td class="fw-semibold">

                                                <?php
                                                echo htmlspecialchars($cat['name']);
                                                ?>

                                            </td>


                                            <!-- Associated Brands -->
                                            <td>

                                                <?php if (!empty($cat['brands_list'])): ?>

                                                    <?php

                                                    $brands_arr = explode(
                                                        ', ',
                                                        $cat['brands_list']
                                                    );

                                                    foreach ($brands_arr as $b_name) {

                                                        echo '
                                                            <span class="badge bg-info text-dark me-1 mb-1">
                                                                ' .
                                                                htmlspecialchars($b_name)
                                                                .
                                                            '
                                                            </span>
                                                        ';
                                                    }

                                                    ?>

                                                <?php else: ?>

                                                    <span class="text-muted fst-italic">
                                                        No brands yet
                                                    </span>

                                                <?php endif; ?>

                                            </td>


                                            <!-- Created At -->
                                            <td>

                                                <?php
                                                echo !empty($cat['created_at'])
                                                    ? htmlspecialchars($cat['created_at'])
                                                    : 'N/A';
                                                ?>

                                            </td>


                                            <!-- Actions -->
                                            <td class="text-center">

                                                <a
                                                    href="edit.php?id=<?php echo $cat['id']; ?>"
                                                    class="btn btn-sm btn-outline-primary me-1"
                                                >
                                                    <i class="fas fa-edit"></i>
                                                    Edit
                                                </a>


                                                <a
                                                    href="delete.php?id=<?php echo $cat['id']; ?>"
                                                    class="btn btn-sm btn-outline-danger"
                                                    onclick="return confirm('Are you sure you want to delete this category?');"
                                                >
                                                    <i class="fas fa-trash"></i>
                                                    Delete
                                                </a>

                                            </td>

                                        </tr>

                                    <?php endforeach; ?>


                                <?php else: ?>

                                    <!-- No Categories -->
                                    <tr>

                                        <td
                                            colspan="6"
                                            class="text-center py-4 text-muted"
                                        >
                                            No categories found in database.
                                        </td>

                                    </tr>

                                <?php endif; ?>

                            </tbody>

                        </table>

                    </div>

                </div>

            </div>

        </div>

    </div>

</div>


<!-- Bootstrap JS -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>

</body>
</html>

