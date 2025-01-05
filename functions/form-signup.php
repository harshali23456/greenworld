<?php
include '../includes/database.php'; // Adjust path as needed

// Retrieve form data
$fullName = $_POST['fullName'];
$phoneNo = $_POST['phoneNo'];
$email = $_POST['email'];
$password = $_POST['password'];

// Debugging output
error_log("Full Name: " . htmlspecialchars($fullName));
error_log("Phone No: " . htmlspecialchars($phoneNo));
error_log("Email: " . htmlspecialchars($email));
error_log("Password: " . htmlspecialchars($password));

// Prepare SQL query
$stmt = $conn->prepare("INSERT INTO users (fullName, phoneNo, email, password) VALUES (?, ?, ?, ?)");
$stmt->bind_param("ssss", $fullName, $phoneNo, $email, $password);

if ($stmt->execute()) {
    // header("Location: /greenworld/login.php");
    exit();
} else {
    $error = "Error: " . $stmt->error;
    // include '../signup.php';
    exit();
}

$stmt->close();
$conn->close();
?>
