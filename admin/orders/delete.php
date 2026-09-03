<?php
include '../../config/database.php';

// لو متغير الاتصال اسمه conn عندك بدلاً من connection، بنظبطه هنا
if (!isset($connection) && isset($conn)) {
    $connection = $conn;
}

if (isset($_GET['id'])) {
    $id = intval($_GET['id']);
    
    // حذف الأوردر من قاعدة البيانات
    $query = "DELETE FROM orders WHERE id = $id";
    mysqli_query($connection, $query);
}

// الرجوع لصفحة الـ Index الخاصة بالآدمن مباشرة
header("Location: ../index.php");
exit();
?>