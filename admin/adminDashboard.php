<?php
session_start();
include '../includes/database.php';

// Ensure the user is an admin
if (!isset($_SESSION['admin']) || $_SESSION['admin'] !== true) {
    header("Location: /greenworld/login.php");
    exit;
}

// Fetch all orders
$sql = "SELECT o.id, o.user_id, o.order_date, o.total_amount, o.status, u.fullName
        FROM orders o
        JOIN users u ON o.user_id = u.id";
$result = mysqli_query($conn, $sql);

// Check for query execution errors
if (!$result) {
    die("Query failed: " . mysqli_error($conn));
}

$orders = mysqli_fetch_all($result, MYSQLI_ASSOC);

// Fetch order items for orders
$orderItems = [];
foreach ($orders as $order) {
    $orderId = $order['id'];
    $sql = "SELECT oi.product_id, p.name AS productName, oi.quantity, oi.price
            FROM order_items oi
            JOIN plants p ON oi.product_id = p.id
            WHERE oi.order_id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $orderId);
    $stmt->execute();
    $result = $stmt->get_result();

    // Check if there are items for the order
    if ($result) {
        $items = $result->fetch_all(MYSQLI_ASSOC);
        $orderItems[$orderId] = $items;
    } else {
        $orderItems[$orderId] = [];
    }
    $stmt->close();
}

mysqli_close($conn);
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <?php include ('../components/userMeta.php'); ?>
    <title>Admin Dashboard</title>
</head>

<body>
    <?php include '../components/adminHeader.php'; ?>
    <!-- Header -->
    <!-- Breadcrumb Area -->
    <div class="breadcrumb-area">
        <div class="top-breadcrumb-area bg-img bg-overlay d-flex align-items-center justify-content-center"
            style="background-image: url(../images/img/bg-img/24.jpg);">
            <h2>Cart</h2>
        </div>
        <div class="container">
            <div class="row">
                <div class="col-12">
                    <nav aria-label="breadcrumb">
                        <ol class="breadcrumb">
                            <li class="breadcrumb-item"><a href="/"><i class="fa fa-home"></i> Home</a></li>
                            <li class="breadcrumb-item active" aria-current="page">Cart</li>
                        </ol>
                    </nav>
                </div>
            </div>
        </div>
    </div>

    <div class="custom-container">
        <h1>Admin Dashboard</h1>
        <table class="table table-bordered table-design table-striped w-auto">
            <thead>
                <tr>
                    <th>Order ID</th>
                    <th>User</th>
                    <th>Order Date</th>
                    <th>Total Amount</th>
                    <th>Status</th>
                    <th>View Items</th>
                    <th>Update Status</th>
                </tr>
            </thead>
            <tbody>
                <?php if (is_array($orders) && count($orders) > 0): ?>
                    <?php foreach ($orders as $order): ?>
                        <tr>
                            <td><?= htmlspecialchars($order['id']) ?></td>
                            <td><?= htmlspecialchars($order['fullName']) ?></td>
                            <td><?= htmlspecialchars($order['order_date']) ?></td>
                            <td>₹<?= htmlspecialchars($order['total_amount']) ?></td>
                            <td><?php
                            // Determine the button class based on the status
                            $statusClass = '';
                            switch ($order['status']) {
                                case 'Pending':
                                    $statusClass = 'btn-warning';
                                    break;
                                case 'Approved':
                                    $statusClass = 'btn-success';
                                    break;
                                case 'Rejected':
                                    $statusClass = 'btn-danger';
                                    break;
                                case 'Processing':
                                    $statusClass = 'btn-info';
                                    break;
                                case 'Shipped':
                                    $statusClass = 'btn-primary';
                                    break;
                                case 'Delivered':
                                    $statusClass = 'btn-secondary';
                                    break;
                                case 'Cancelled':
                                    $statusClass = 'btn-dark';
                                    break;
                                default:
                                    $statusClass = 'btn-light';
                            }
                            ?>
                                <button type="button"
                                    class="btn <?= $statusClass ?>"><?= htmlspecialchars($order['status']) ?></button>
                            </td>
                            <td>
                                <button class="btn btn-info"
                                    onclick="toggleOrderItems(<?= htmlspecialchars($order['id']) ?>)">View Items</button>
                            </td>
                            <td style="display:flex; justify-content:center; align-items:center; gap:10px;">
                                <form action="/greenworld/functions/updateOrderStatus.php" method="post">
                                    <input type="hidden" name="orderId" value="<?= htmlspecialchars($order['id']) ?>" />
                                    <select name="status" required class="form-select form-select-lg p-1 btn btn-outline-info"
                                        aria-label=".form-select-lg example">
                                        <option value="Pending" <?= $order['status'] == 'Pending' ? 'selected' : '' ?>>Pending
                                        </option>
                                        <option value="Approved" <?= $order['status'] == 'Approved' ? 'selected' : '' ?>>Approved
                                        </option>
                                        <option value="Rejected" <?= $order['status'] == 'Rejected' ? 'selected' : '' ?>>Rejected
                                        </option>
                                        <option value="Processing" <?= $order['status'] == 'Processing' ? 'selected' : '' ?>>
                                            Processing</option>
                                        <option value="Shipped" <?= $order['status'] == 'Shipped' ? 'selected' : '' ?>>Shipped
                                        </option>
                                        <option value="Delivered" <?= $order['status'] == 'Delivered' ? 'selected' : '' ?>>
                                            Delivered</option>

                                    </select>
                                    <button type="submit" class="btn btn-primary">Update Status</button>
                                </form>
                            </td>
                        </tr>
                        <tr id="items-<?= htmlspecialchars($order['id']) ?>" class="order-items" style="display:none;">
                            <td colspan="7">
                                <div style="width: 100%; display: flex; justify-content: center; align-items: center;">
                                    <table class="table table-striped" style="width: 50vw; max-width: 100%;">
                                        <thead>
                                            <tr>
                                                <th>Product Name</th>
                                                <th>Quantity</th>
                                                <th>Price</th>
                                                <th>Total</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php if (isset($orderItems[$order['id']]) && count($orderItems[$order['id']]) > 0): ?>
                                                <?php foreach ($orderItems[$order['id']] as $item): ?>
                                                    <tr>
                                                        <td><?= htmlspecialchars($item['productName']) ?></td>
                                                        <td><?= htmlspecialchars($item['quantity']) ?></td>
                                                        <td>₹<?= htmlspecialchars($item['price']) ?></td>
                                                        <td>₹<?= htmlspecialchars($item['quantity'] * $item['price']) ?></td>
                                                    </tr>
                                                <?php endforeach; ?>
                                            <?php else: ?>
                                                <tr>
                                                    <td colspan="4">No items found.</td>
                                                </tr>
                                            <?php endif; ?>
                                        </tbody>
                                    </table>
                                </div>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                <?php else: ?>
                    <tr>
                        <td colspan="7">No orders found.</td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>

    <?php include '../components/userScripts.php'; ?>

    <script>
        function toggleOrderItems(orderId) {
            const itemsRow = document.getElementById(`items-${orderId}`);
            if (itemsRow.style.display === 'none') {
                itemsRow.style.display = 'table-row';
            } else {
                itemsRow.style.display = 'none';
            }
        }
    </script>
</body>

</html>