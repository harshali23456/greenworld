<!DOCTYPE html>
<html lang="en">
<?php
include '../components/userMeta.php';
include '../includes/database.php';

$showAlert = false;
$showError = false;

if (isset($_GET['id'])) {
    $id = intval($_GET['id']);

    // Fetch the plant details
    $sql = "SELECT * FROM plants WHERE id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $result = $stmt->get_result();
    $plant = $result->fetch_assoc();

    if (!$plant) {
        $showError = "Plant not found.";
    }

    // Handle form submission
    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        $name = $_POST['name'];
        $price = $_POST['price'];
        $description = $_POST['description'];
        $full_description = $_POST['full_description'];
        $sku = $_POST['sku'];
        $category = $_POST['category'];

        // Ensure tags and related_products are processed correctly
        $tags = isset($_POST['tags']) ? $_POST['tags'] : ''; // Make sure it's a string
        if (is_array($tags)) {
            $tags = implode(',', $tags); // Convert array to comma-separated string
        }
        $tags = json_encode(explode(',', $tags));

        $related_products = isset($_POST['related_products']) ? $_POST['related_products'] : ''; // Make sure it's a string
        if (is_array($related_products)) {
            $related_products = implode(',', $related_products); // Convert array to comma-separated string
        }
        $related_products = json_encode(explode(',', $related_products));

        $additional_info = $_POST['additional_info'] ?? '';
        $reviews = $_POST['reviews'] ?? '';
        $images = $_POST['images'] ?? '';
        $stock = intval($_POST['stock']);

        $sql = "UPDATE plants SET 
        name = ?, 
        price = ?, 
        description = ?, 
        full_description = ?, 
        sku = ?, 
        category = ?, 
        tags = ?, 
        related_products = ?, 
        additional_info = ?, 
        reviews = ?, 
        imgUrl = ?, 
        stock = ? 
    WHERE id = ?";

        $stmt = $conn->prepare($sql);

        if ($stmt === false) {
            $showError = "Failed to prepare the statement: " . htmlspecialchars($conn->error);
        } else {
            $stmt->bind_param(
                "sdsssssssssii",
                $name,
                $price,
                $description,
                $full_description,
                $sku,
                $category,
                $tags,
                $related_products,
                $additional_info,
                $reviews,
                $images,
                $stock,
                $id
            );

            if ($stmt->execute()) {
                $showAlert = true;
            } else {
                $showError = "Failed to update plant: " . htmlspecialchars($stmt->error) . " - " . htmlspecialchars(mysqli_error($conn));
            }
            $stmt->close();
        }
        mysqli_close($conn);
    }

} else {
    $showError = "Invalid plant ID.";
}
?>

<body>
    <!-- Preloader -->
    <div class="preloader d-flex align-items-center justify-content-center">
        <div class="preloader-circle"></div>
        <div class="preloader-img">
            <img src="../images/img/core-img/leaf.png" alt="" />
        </div>
    </div>

    <?php include '../components/adminHeader.php'; ?>

    <!-- ##### Breadcrumb Area Start ##### -->
    <div class="breadcrumb-area">
        <div class="top-breadcrumb-area bg-img bg-overlay d-flex align-items-center justify-content-center"
            style="background-image: url(../images/img/bg-img/24.jpg);">
            <h2>Edit Plant</h2>
        </div>

        <div class="container">
            <div class="row">
                <div class="col-12">
                    <nav aria-label="breadcrumb">
                        <ol class="breadcrumb">
                            <li class="breadcrumb-item"><a href="#"><i class="fa fa-home"></i> Home</a></li>
                            <li class="breadcrumb-item"><a href="shop.php">Shop</a></li>
                            <li class="breadcrumb-item active" aria-current="page">Edit Plant</li>
                        </ol>
                    </nav>
                </div>
            </div>
        </div>
    </div>
    <!-- ##### Breadcrumb Area End ##### -->

    <!-- ##### Edit Plant Area Start ##### -->
    <section class="edit-plant-page section-padding-0-100">
        <div class="container">
            <form class="edit-plant-form" method="POST" action="edit_plant.php?id=<?php echo $id; ?>"
                enctype="multipart/form-data">
                <!-- Plant Name -->
                <div class="form-group">
                    <label for="name">Plant Name</label>
                    <input type="text" class="form-control" id="name" name="name"
                        value="<?php echo htmlspecialchars($plant['name']); ?>" placeholder="Enter Plant Name"
                        required />
                </div>
                <!-- Price -->
                <div class="form-group">
                    <label for="price">Price</label>
                    <input type="number" step="0.01" class="form-control" id="price" name="price"
                        value="<?php echo htmlspecialchars($plant['price']); ?>" placeholder="Enter Price" required />
                </div>
                <!-- Description -->
                <div class="form-group">
                    <label for="description">Description</label>
                    <textarea class="form-control" id="description" name="description"
                        placeholder="Enter Description"><?php echo htmlspecialchars($plant['description']); ?></textarea>
                </div>
                <!-- Full Description -->
                <div class="form-group">
                    <label for="full_description">Full Description</label>
                    <textarea class="form-control" id="full_description" name="full_description"
                        placeholder="Enter Full Description"><?php echo htmlspecialchars($plant['full_description']); ?></textarea>
                </div>
                <!-- SKU -->
                <div class="form-group">
                    <label for="sku">SKU</label>
                    <input type="text" class="form-control" id="sku" name="sku"
                        value="<?php echo htmlspecialchars($plant['sku']); ?>" placeholder="Enter SKU">
                </div>
                <!-- Category -->
                <div class="form-group">
                    <label for="category">Category</label>
                    <select class="form-control" id="category" name="category" required>
                        <option value="Indoor Plants" <?php echo $plant['category'] === 'Indoor Plants' ? 'selected' : ''; ?>>Indoor Plants</option>
                        <option value="Ayurvedic Plants" <?php echo $plant['category'] === 'Ayurvedic Plants' ? 'selected' : ''; ?>>Ayurvedic Plants</option>
                        <option value="Seeds" <?php echo $plant['category'] === 'Seeds' ? 'selected' : ''; ?>>Seeds
                        </option>
                        <option value="Flowers" <?php echo $plant['category'] === 'Flowers' ? 'selected' : ''; ?>>Flowers
                        </option>
                        <option value="Climbers" <?php echo $plant['category'] === 'Climbers' ? 'selected' : ''; ?>>
                            Climbers</option>
                        <option value="Others" <?php echo $plant['category'] === 'Others' ? 'selected' : ''; ?>>Others
                        </option>
                    </select>
                </div>
                <!-- Tags -->
                <div class="form-group">
                    <label for="tags">Tags</label>
                    <input type="text" class="form-control" id="tags" name="tags[]"
                        placeholder="Enter Tags (comma-separated)"
                        value="<?php echo htmlspecialchars(implode(', ', json_decode($plant['tags']))); ?>">
                </div>
                <!-- Related Products -->
                <div class="form-group">
                    <label for="related_products">Related Products</label>
                    <input type="text" class="form-control" id="related_products" name="related_products[]"
                        placeholder="Enter Related Products (comma-separated)"
                        value="<?php echo htmlspecialchars(implode(', ', json_decode($plant['related_products']))); ?>">
                </div>
                <!-- Additional Info -->
                <div class="form-group">
                    <label for="additional_info">Additional Info</label>
                    <textarea class="form-control" id="additional_info" name="additional_info"
                        placeholder="Enter Additional Info"><?php echo htmlspecialchars($plant['additional_info']); ?></textarea>
                </div>
                <!-- Reviews -->
                <div class="form-group">
                    <label for="reviews">Reviews</label>
                    <textarea class="form-control" id="reviews" name="reviews"
                        placeholder="Enter Reviews"><?php echo htmlspecialchars($plant['reviews']); ?></textarea>
                </div>
                <!-- Images -->
                <div class="form-group">
                    <label for="images">Images</label>
                    <input type="text" class="form-control" id="images" name="images"
                        placeholder="Enter Images (comma-separated)"
                        value="<?php echo htmlspecialchars($plant['imgUrl']); ?>">
                </div>
                <!-- Stock -->
                <div class="form-group">
                    <label for="stock">Stock</label>
                    <input type="number" class="form-control" id="stock" name="stock"
                        value="<?php echo htmlspecialchars($plant['stock']); ?>" placeholder="Enter Stock" required />
                </div>
                <!-- Error and Success Messages -->
                <div class="error">
                    <?php if ($showAlert): ?>
                        <div class="alert alert-success" role="alert">
                            Plant updated successfully!
                        </div>
                    <?php elseif ($showError): ?>
                        <div class="alert alert-danger" role="alert">
                            <?php echo $showError; ?>
                        </div>
                    <?php endif; ?>
                </div>
                <!-- Submit Button -->
                <button type="submit" class="btn btn-primary">Save Changes</button>
            </form>
        </div>
    </section>
    <!-- ##### Edit Plant Area End ##### -->

    <!-- All JS Files -->
    <?php include '../components/userScripts.php'; ?>
</body>

</html>