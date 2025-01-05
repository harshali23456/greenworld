<!DOCTYPE html>
<html lang="en">
<?php include '../components/userMeta.php'; ?>

<body>
    <!-- Preloader -->
    <div class="preloader d-flex align-items-center justify-content-center">
        <div class="preloader-circle"></div>
        <div class="preloader-img">
            <img src="../images/img/core-img/leaf.png" alt="Preloader Image" />
        </div>
    </div>

    <!-- Header -->
    <?php include ('../components/userHeader.php'); ?>

    <!-- ##### Breadcrumb Area Start ##### -->
    <div class="breadcrumb-area">
        <!-- Top Breadcrumb Area -->
        <div class="top-breadcrumb-area bg-img bg-overlay d-flex align-items-center justify-content-center"
            style="background-image: url(../images/img/bg-img/24.jpg);">
            <h2>Order Successfull</h2>
        </div>

        <div class="container">
            <div class="row">
                <div class="col-12">
                    <nav aria-label="breadcrumb">
                        <ol class="breadcrumb">
                            <li class="breadcrumb-item"><a href="#"><i class="fa fa-home"></i> Home</a></li>
                            <li class="breadcrumb-item active" aria-current="page">Order Success</li>
                        </ol>
                    </nav>
                </div>
            </div>
        </div>
    </div>
    <!-- ##### Breadcrumb Area End ##### -->

    <!-- Success Message -->
    <div class="custom-container">
        <div class="row">
            <div class="col-12 flex">
                <h2>Your order has been placed successfully!</h2>
                <p>Thank you for shopping with us. Your order will be processed shortly.</p>
                <a href="/greenworld/user/view_orders.php" class="btn alazea-btn">View Your Orders</a>
            </div>
        </div>
    </div>

    <!-- Scripts -->
    <?php include ('../components/userScripts.php'); ?>
</body>

</html>