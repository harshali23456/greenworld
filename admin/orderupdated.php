<!DOCTYPE html>
<html lang="en">
<head>
    <?php include('../components/userMeta.php'); ?>
    <title>Order Update Status</title>
    <style>
        .message {
            font-size: 18px;
            font-weight: bold;
            margin-top: 20px;
        }
        .success {
            color: green;
        }
        .fail {
            color: red;
        }
        .invalid {
            color: orange;
        }
    </style>
</head>
<body>
    <?php include '../components/adminheader.php'; ?>

    <div class="custom-container">
        <h1>Order Status Update</h1>
        <div class="message">
            <?php
            if (isset($_GET['status'])) {
                $status = $_GET['status'];
                if ($status === 'success') {
                    echo '<p class="success">Order status updated successfully!</p>';
                } elseif ($status === 'fail') {
                    echo '<p class="fail">Failed to update order status. Please try again.</p>';
                } elseif ($status === 'invalid') {
                    echo '<p class="invalid">Invalid status value provided.</p>';
                }
            }
            ?>
        </div>
        <a href="/greenworld/admin/adminDashboard.php" class="btn btn-primary">Back to Dashboard</a>
    </div>

    <?php include '../components/userScripts.php'; ?>
</body>
</html>
