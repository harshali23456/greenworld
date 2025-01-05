<?php
session_start();
include '../includes/database.php';

$showAlert = false;
$showError = false;

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $name = $_POST['name'];
    $price = $_POST['price'];
    $description = $_POST['description'];
    $full_description = $_POST['full_description'];
    $sku = $_POST['sku'];
    $category = $_POST['category'];
    $tags = isset($_POST['tags']) ? json_encode($_POST['tags']) : '';
    $related_products = isset($_POST['related_products']) ? json_encode($_POST['related_products']) : '';

    $additional_info = $_POST['additional_info'] ?? '';
    $reviews = $_POST['reviews'] ?? '';
    $images = $_POST['images'] ?? '';
    $stock = $_POST['stock'];
    $sql = "INSERT INTO plants (name, price, description, full_description, sku, category, tags, related_products, additional_info, reviews, images, stock) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
    $stmt = $conn->prepare($sql);

    if ($stmt === false) {
        $showError = "Failed to prepare the statement: " . htmlspecialchars($conn->error);
    } else {
        $stmt->bind_param("sdsdsssssssi", $name, $price, $description, $full_description, $sku, $category, $tags, $related_products, $additional_info, $reviews, $images, $stock);
        if ($stmt->execute()) {
            $showAlert = true;
        } else {
            $showError = "Failed to add plant: " . htmlspecialchars($stmt->error);
        }
        $stmt->close();
    }
    mysqli_close($conn);
}
?>



<!DOCTYPE html>
<html lang="en">

<head>
    <?php include '../components/userMeta.php'; ?>
    <title>Add New Plant</title>
</head>

<body>
    <?php include '../components/adminheader.php'; ?>
    <!-- Preloader -->
    <div class="preloader d-flex align-items-center justify-content-center">
        <div class="preloader-circle"></div>
        <div class="preloader-img">
            <img src="../images/img/core-img/leaf.png" alt="Loading" />
        </div>
    </div>

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

    <section class="hero-area green">
        <div class="">
            <form class="login-form" method="POST" action="" enctype="multipart/form-data">
                <!-- Plant Name -->
                <div class="form-group">
                    <label for="name">Plant Name</label>
                    <input type="text" class="form-control" id="name" name="name" placeholder="Enter Plant Name"
                        required />
                </div>
                <!-- Price -->
                <div class="form-group">
                    <label for="price">Price</label>
                    <input type="number" step="0.01" class="form-control" id="price" name="price"
                        placeholder="Enter Price" required />
                </div>
                <!-- Description -->
                <div class="form-group">
                    <label for="description">Description</label>
                    <textarea class="form-control" id="description" name="description"
                        placeholder="Enter Description"></textarea>
                </div>
                <!-- Full Description -->
                <div class="form-group">
                    <label for="full_description">Full Description</label>
                    <textarea class="form-control" id="full_description" name="full_description"
                        placeholder="Enter Full Description"></textarea>
                </div>
                <!-- SKU -->
                <div class="form-group">
                    <label for="sku">SKU</label>
                    <input type="text" class="form-control" id="sku" name="sku" placeholder="Enter SKU">
                </div>
                <!-- Category -->
                <div class="form-group">
                    <label for="category">Category</label>
                    <select class="form-control" id="category" name="category" required>
                        <option value="">Select Category</option>
                        <option value="Indoor Plants">Indoor Plants</option>
                        <option value="Ayurvedic Plants">Ayurvedic Plants</option>
                        <option value="Seeds">Seeds</option>
                        <option value="Flowers">Flowers</option>
                        <option value="Climbers">Climbers</option>
                        <option value="Others">Others</option>
                    </select>
                </div>
                <!-- Tags -->
                <div class="form-group">
                    <label for="tags">Tags</label>
                    <input type="text" class="form-control" id="tags" name="tags" placeholder="Enter Tags">
                </div>
                <!-- Related Products -->
                <div class="form-group">
                    <label for="related_products">Related Products</label>
                    <input type="text" class="form-control" id="related_products" name="related_products"
                        placeholder="Enter Related Products">
                </div>
                <!-- Additional Info -->
                <div class="form-group">
                    <label for="additional_info">Additional Info</label>
                    <textarea class="form-control" id="additional_info" name="additional_info"
                        placeholder="Enter Additional Info"></textarea>
                </div>
                <!-- Reviews -->
                <div class="form-group">
                    <label for="reviews">Reviews</label>
                    <textarea class="form-control" id="reviews" name="reviews" placeholder="Enter Reviews"></textarea>
                </div>
                <!-- Images -->
                <div class="form-group">
                    <label for="images">Images</label>
                    <input type="text" class="form-control" id="images" name="images" multiple
                        placeholder="image_name.jpg">
                </div>
                <!-- Stock -->
                <div class="form-group">
                    <label for="stock">Stock</label>
                    <input type="number" class="form-control" id="stock" name="stock" placeholder="Enter Stock"
                        required />
                </div>
                <!-- Error and Success Messages -->
                <div class="error">
                    <?php if ($showAlert): ?>
                        <div class="alert alert-success alert-dismissible fade show" role="alert">
                            <strong>Success!</strong> The plant has been added.
                            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                <span aria-hidden="true">X</span>
                            </button>
                        </div>
                    <?php endif; ?>

                    <?php if ($showError): ?>
                        <div class="alert alert-danger alert-dismissible fade show" role="alert">
                            <strong>Error!</strong> <?php echo htmlspecialchars($showError); ?>
                            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                <span aria-hidden="true">X</span>
                            </button>
                        </div>
                    <?php endif; ?>
                </div>
                <!-- Buttons -->
                <div class="mr-auto ml-auto">
                    <button type="submit" class="btn btn-primary">Submit</button>
                    <button type="reset" class="btn btn-secondary">Reset</button>
                </div>
            </form>
        </div>
    </section>
    <?php include '../components/userScripts.php'; ?>
</body>

</html>