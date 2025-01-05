<?php
include './includes/database.php';
session_start();

if (!isset($_SESSION['user_id'])) {
    header("Location: /greenworld/user/login.php");
    exit;
}

$user_id = $_SESSION['user_id'];
$totalAmount = 0;

// Get shipping address and payment mode from form submission
$shipping_address = $_POST['shipping_address'] ?? '';
$payment_mode = $_POST['payment_mode'] ?? '';

// Begin transaction
$conn->begin_transaction();

try {
    // Check stock availability and calculate total amount
    $sql = "SELECT p.id, p.price, c.quantity, p.stock
            FROM cart c
            JOIN plants p ON c.product_id = p.id
            WHERE c.user_id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $user_id);
    $stmt->execute();
    $result = $stmt->get_result();

    $cartItems = [];
    $stockSufficient = true;

    while ($row = $result->fetch_assoc()) {
        $totalAmount += $row['price'] * $row['quantity'];

        // Check if there's enough stock
        if ($row['stock'] < $row['quantity']) {
            $stockSufficient = false;
            $cartItems[] = [
                'product_id' => $row['id'],
                'available_stock' => $row['stock']
            ];
        } else {
            $cartItems[] = [
                'product_id' => $row['id'],
                'ordered_quantity' => $row['quantity']
            ];
        }
    }

    if (!$stockSufficient) {
        // Rollback transaction and redirect to an error page if stock is insufficient
        $conn->rollback();
        $_SESSION['cart_items'] = $cartItems; // Optionally store cart items in session for later use
        header("Location: /greenworld/user/stock_error.php"); // Redirect to an error page
        exit;
    }

    // Insert order
    $sql = "INSERT INTO orders (user_id, total_amount, shipping_address, payment_mode, status)
            VALUES (?, ?, ?, ?, 'Pending')";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("dss", $user_id, $totalAmount, $shipping_address, $payment_mode);
    $stmt->execute();
    $order_id = $stmt->insert_id;

    // Insert order items
    $sql = "INSERT INTO order_items (order_id, product_id, quantity, price)
            SELECT ?, product_id, quantity, (SELECT price FROM plants WHERE id = product_id)
            FROM cart WHERE user_id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("is", $order_id, $user_id);
    $stmt->execute();

    // Update stock in plants table
    $sql = "UPDATE plants p
            JOIN cart c ON p.id = c.product_id
            SET p.stock = p.stock - c.quantity
            WHERE c.user_id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $user_id);
    $stmt->execute();

    // Clear cart
    $sql = "DELETE FROM cart WHERE user_id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $user_id);
    $stmt->execute();

    // Commit transaction
    $conn->commit();

    header("Location: /greenworld/user/order_success.php");
    exit;
} catch (Exception $e) {
    // Rollback transaction on error
    $conn->rollback();
    // Handle the error, e.g., log it and show a user-friendly message
    error_log($e->getMessage());
    header("Location: /greenworld/user/error.php"); // Redirect to an error page
    exit;
}
?>