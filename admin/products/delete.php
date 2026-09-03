<?php
ini_set('display_errors', 1);
error_reporting(E_ALL);

include __DIR__ . "/../../config/database.php"; 
if (!isset($connection) && isset($conn)) { $connection = $conn; }

if (isset($_GET['id']) && !empty($_GET['id']) && $connection) {
    $id = intval($_GET['id']);

    // جلب اسم الصورة عشان نحذفها من الفولدر
    $res = mysqli_query($connection, "SELECT image FROM products WHERE id = $id");
    if ($res && mysqli_num_rows($res) > 0) {
        $product = mysqli_fetch_assoc($res);
        if (!empty($product['image'])) {
            $image_path = __DIR__ . "/../../uploads/" . $product['image'];
            if (file_exists($image_path)) {
                @unlink($image_path);
            }
        }

        // حذف السجل من قاعدة البيانات
        mysqli_query($connection, "DELETE FROM products WHERE id = $id");
    }
}

// العودة لصفحة المنتجات بعد الحذف
header("Location: index.php");
exit();