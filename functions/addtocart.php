<?php
include '../includes/database.php';
session_start();
if (!isset($_SESSION['user_id'])) {
    echo "Please log in to add items to your cart.";
    exit;
}

$product_id = isset($_POST['product_id']) ? htmlspecialchars($_POST['product_id']) : '';
$quantity = isset($_POST['quantity']) ? (int)$_POST['quantity'] : 0;
$user_id = isset($_POST['user_id']) ? htmlspecialchars($_POST['user_id']) : '';

if (empty($product_id) || $quantity <= 0 || empty($user_id)) {
    echo "Invalid input data.";
    exit;
}

$sql = "SELECT * FROM plants WHERE id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("s", $product_id);
$stmt->execute();
$product = $stmt->get_result()->fetch_assoc();

if (!$product) {
    echo "Product not found.";
    exit;
}

$sql = "SELECT * FROM cart WHERE product_id = ? AND user_id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("ss", $product_id, $user_id);
$stmt->execute();
$cartItem = $stmt->get_result()->fetch_assoc();

if ($cartItem) {
    $newQuantity = $cartItem['quantity'] + $quantity;
    $sql = "UPDATE cart SET quantity = ? WHERE product_id = ? AND user_id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("iss", $newQuantity, $product_id, $user_id);
} else {
    $sql = "INSERT INTO cart (product_id, user_id, quantity) VALUES (?, ?, ?)";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ssi", $product_id, $user_id, $quantity);
}

if ($stmt->execute()) {
    header("Location: /greenworld/user/cart.php");
    exit();
} else {
    echo "Failed to add product to cart.";
}

$stmt->close();
$conn->close();
?>
