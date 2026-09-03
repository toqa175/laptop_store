<?php
ini_set('display_errors', 1);
error_reporting(E_ALL);

include __DIR__ . "/../../config/database.php"; 
if (!isset($connection) && isset($conn)) { $connection = $conn; }

if (isset($_GET['id']) && !empty($_GET['id']) && $connection) {
    $id = intval($_GET['id']);
    mysqli_query($connection, "DELETE FROM brands WHERE id = $id");
}

header("Location: index.php");
exit();