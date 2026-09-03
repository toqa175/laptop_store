<?php

session_start();

require_once '../../config/database.php';


// ===============================
// Check if user is logged in
// ===============================

if (!isset($_SESSION['user_id'])) {

    header("Location: ../../auth/login.php");
    exit;

}


// ===============================
// Check if user is admin
// ===============================

if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'admin') {

    header("Location: ../../index.php");
    exit;

}


// ===============================
// Delete Contact Message
// ===============================

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['delete_id'])) {

    $delete_id = (int) $_POST['delete_id'];

    if ($delete_id > 0) {

        $stmt = $connection->prepare("
            DELETE FROM contact_messages
            WHERE id = ?
        ");

        $stmt->bind_param("i", $delete_id);

        $stmt->execute();

        $stmt->close();

    }

    // Refresh page after delete

    header("Location: index.php");
    exit;
}


// ===============================
// Get Contact Messages
// ===============================

$result = $connection->query("
    SELECT
        id,
        name,
        email,
        subject,
        message
    FROM contact_messages
    ORDER BY id DESC
");


if (!$result) {

    die("Error getting contact messages: " . $connection->error);

}


$messages = $result->fetch_all(MYSQLI_ASSOC);

?>


<?php include_once '../../shared/header.php'; ?>


<div class="d-flex">


    <!-- ===============================
         Sidebar
    ================================ -->

    <?php include_once '../shared/sidebar.php'; ?>


    <!-- ===============================
         Main Content
    ================================ -->

    <div class="flex-grow-1 bg-light min-vh-100">

        <div class="container-fluid p-4">


            <!-- ===============================
                 Page Header
            ================================ -->

            <div class="d-flex justify-content-between align-items-center mb-4">

                <div>

                    <h2 class="fw-bold text-dark mb-1">
                        Contact Messages
                    </h2>

                    <p class="text-muted mb-0">
                        View messages sent from your website.
                    </p>

                </div>


                <span class="badge bg-dark fs-6 px-3 py-2">

                    <?= count($messages) ?> Messages

                </span>

            </div>



            <!-- ===============================
                 Messages Table
            ================================ -->

            <div class="card border-0 shadow-sm rounded-4">

                <div class="card-body p-0">


                    <?php if (empty($messages)): ?>


                        <!-- No Messages -->

                        <div class="text-center p-5">

                            <i class="bi bi-envelope-x display-1 text-muted"></i>

                            <h4 class="fw-bold mt-3">
                                No Contact Messages
                            </h4>

                            <p class="text-muted mb-0">
                                There are no messages yet.
                            </p>

                        </div>


                    <?php else: ?>


                        <div class="table-responsive">

                            <table class="table table-hover align-middle mb-0">


                                <!-- Table Header -->

                                <thead class="table-light">

                                    <tr>

                                        <th class="px-4">
                                            #
                                        </th>

                                        <th>
                                            Name
                                        </th>

                                        <th>
                                            Email
                                        </th>

                                        <th>
                                            Subject
                                        </th>

                                        <th>
                                            Message
                                        </th>

                                        <th>
                                            Action
                                        </th>

                                    </tr>

                                </thead>


                                <!-- Table Body -->

                                <tbody>


                                    <?php foreach ($messages as $message): ?>


                                        <tr>


                                            <!-- ID -->

                                            <td class="px-4 fw-bold">

                                                #<?= htmlspecialchars($message['id']) ?>

                                            </td>



                                            <!-- Name -->

                                            <td>

                                                <span class="fw-semibold">

                                                    <?= htmlspecialchars($message['name']) ?>

                                                </span>

                                            </td>



                                            <!-- Email -->

                                            <td>

                                                <a
                                                    href="mailto:<?= htmlspecialchars($message['email']) ?>"
                                                    class="text-decoration-none"
                                                >

                                                    <?= htmlspecialchars($message['email']) ?>

                                                </a>

                                            </td>



                                            <!-- Subject -->

                                            <td>

                                                <span class="fw-semibold">

                                                    <?= htmlspecialchars($message['subject']) ?>

                                                </span>

                                            </td>



                                            <!-- Message -->

                                            <td style="min-width: 300px; max-width: 500px;">

                                                <div
                                                    class="text-muted"
                                                    style="
                                                        white-space: normal;
                                                        word-break: break-word;
                                                    "
                                                >

                                                    <?= nl2br(
                                                        htmlspecialchars($message['message'])
                                                    ) ?>

                                                </div>

                                            </td>



                                            <!-- Delete -->

                                            <td>

                                                <form
                                                    method="POST"
                                                    action="index.php"
                                                    onsubmit="return confirm('Are you sure you want to delete this message?');"
                                                >

                                                    <input
                                                        type="hidden"
                                                        name="delete_id"
                                                        value="<?= $message['id'] ?>"
                                                    >


                                                    <button
                                                        type="submit"
                                                        class="btn btn-sm btn-danger rounded-2"
                                                    >

                                                        <i class="bi bi-trash"></i>

                                                        Delete

                                                    </button>

                                                </form>

                                            </td>


                                        </tr>


                                    <?php endforeach; ?>


                                </tbody>

                            </table>

                        </div>


                    <?php endif; ?>


                </div>

            </div>


        </div>

    </div>

</div>


<?php include_once '../../shared/footer.php'; ?>


<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

