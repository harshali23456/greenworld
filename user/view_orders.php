<?php
include '../includes/database.php';
session_start();

if (!isset($_SESSION['user_id'])) {
    header("Location: /greenworld/user/login.php");
    exit;
}

$user_id = $_SESSION['user_id'];

// Fetch orders
$sql = "SELECT o.id, o.order_date, o.total_amount, o.status
        FROM orders o
        WHERE o.user_id = ?
        ORDER BY o.order_date DESC";
$stmt = $conn->prepare($sql);
$stmt->bind_param("s", $user_id);
$stmt->execute();
$result = $stmt->get_result();

$orders = [];
while ($row = $result->fetch_assoc()) {
    $orders[] = $row;
}

$stmt->close();
$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<?php include ('../components/userMeta.php'); ?>

<body>
    <!-- Preloader -->
    <div class="preloader d-flex align-items-center justify-content-center">
        <div class="preloader-circle"></div>
        <div class="preloader-img">
            <img src="../images/img/core-img/leaf.png" alt="Preloader Image" />
        </div>
    </div>

    <!-- Header -->
    <?php include '../components/userHeader.php'; ?>
    <!-- ##### Breadcrumb Area Start ##### -->
    <div class="breadcrumb-area">
        <!-- Top Breadcrumb Area -->
        <div class="top-breadcrumb-area bg-img bg-overlay d-flex align-items-center justify-content-center"
            style="background-image: url(../images/img/bg-img/24.jpg);">
            <h2>SHOP DETAILS</h2>
        </div>

        <div class="container">
            <div class="row">
                <div class="col-12">
                    <nav aria-label="breadcrumb">
                        <ol class="breadcrumb">
                            <li class="breadcrumb-item"><a href="/"><i class="fa fa-home"></i> Home</a></li>
                            <li class="breadcrumb-item"><a href="/shop">Shop</a></li>
                            <li class="breadcrumb-item active" aria-current="page">Order</li>
                        </ol>
                    </nav>
                </div>
            </div>
        </div>
    </div>
    <!-- ##### Breadcrumb Area End ##### -->


    <!-- Orders Area -->
    <div class="custom-container">
        <div class="row">
            <div class="col-12">
                <div class="orders-table clearfix table-design">
                    <table class="table table-responsive table table-striped w-auto">
                        Hello <strong><?= htmlspecialchars($_SESSION['user_name']) ?></strong>, here are your orders:
                        <thead>
                            <tr>
                                <th>Order ID</th>
                                <th>Order Date</th>
                                <th>Total Amount</th>
                                <th>Status</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($orders as $order): ?>
                                <tr>
                                    <td><?= htmlspecialchars($order['id']) ?></td>
                                    <td><?= htmlspecialchars($order['order_date']) ?></td>
                                    <td>₹<?= htmlspecialchars($order['total_amount']) ?></td>
                                    <td><?= htmlspecialchars($order['status']) ?></td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
    <?php include ('../components/userScripts.php'); ?>
</body>

</html>