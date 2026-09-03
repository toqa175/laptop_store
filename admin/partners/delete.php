<?php
include __DIR__ . "/../../config/database.php"; 
if (!isset($connection) && isset($conn)) { $connection = $conn; }

if (isset($_GET['id']) && !empty($_GET['id'])) {
    $id = intval($_GET['id']);
    if ($connection) {
        mysqli_query($connection, "DELETE FROM partners WHERE id = $id");
    }
}
header("Location: index.php");
exit();
?>