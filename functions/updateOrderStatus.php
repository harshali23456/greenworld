<?php
session_start();
include '../includes/database.php';

// Ensure the user is an admin
if (!isset($_SESSION['admin']) || $_SESSION['admin'] !== true) {
    header("Location: /greenworld/login.php");
    exit();
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $orderId = $_POST['orderId'];
    $status = $_POST['status'];

    // Validate status input
    $validStatuses = ['Pending', 'Approved', 'Rejected', 'Processing', 'Shipped', 'Delivered', 'Cancelled'];
    if (!in_array($status, $validStatuses)) {
        header("Location: /greenworld/admin/orderupdated.php?status=invalid");
        exit();
    }


    // Update order status in the database
    $sql = "UPDATE orders SET status = ? WHERE id = ?";
    $stmt = $conn->prepare($sql);
    if ($stmt === false) {
        die('Prepare failed: ' . htmlspecialchars($conn->error));
    }
    
    $stmt->bind_param("si", $status, $orderId);
    $success = $stmt->execute();
    $stmt->close();

    if ($success) {
        header("Location: /greenworld/admin/orderupdated.php?status=success");
    } else {
        header("Location: /greenworld/admin/orderupdated.php?status=fail");
    }
}

mysqli_close($conn);
?>
