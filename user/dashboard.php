<!DOCTYPE html>
<html lang="en">
<?php
include '../components/userMeta.php';
include '../includes/database.php';

// Initialize category filter
$selectedCategory = isset($_POST['category']) ? $_POST['category'] : 'All';

// Query to count products in each category
$sql = "SELECT 
            SUM(CASE WHEN category = 'Indoor Plants' THEN 1 ELSE 0 END) AS indoor_plants,
            SUM(CASE WHEN category = 'Ayurvedic Plants' THEN 1 ELSE 0 END) AS ayurvedic_plants,
            SUM(CASE WHEN category = 'Seeds' THEN 1 ELSE 0 END) AS seeds,
            SUM(CASE WHEN category = 'Flowers' THEN 1 ELSE 0 END) AS flowers,
            SUM(CASE WHEN category = 'Climbers' THEN 1 ELSE 0 END) AS climbers,
            SUM(CASE WHEN category = 'Others' THEN 1 ELSE 0 END) AS others,
            COUNT(*) AS total_plants
        FROM plants";

$result = $conn->query($sql);

$categoryCounts = [
    'indoor_plants' => 0,
    'ayurvedic_plants' => 0,
    'seeds' => 0,
    'flowers' => 0,
    'climbers' => 0,
    'others' => 0,
    'total_plants' => 0
];

if ($result->num_rows > 0) {
    // Fetch the counts
    $row = $result->fetch_assoc();
    $categoryCounts['indoor_plants'] = $row['indoor_plants'];
    $categoryCounts['ayurvedic_plants'] = $row['ayurvedic_plants'];
    $categoryCounts['seeds'] = $row['seeds'];
    $categoryCounts['flowers'] = $row['flowers'];
    $categoryCounts['climbers'] = $row['climbers'];
    $categoryCounts['others'] = $row['others'];
    $categoryCounts['total_plants'] = $row['total_plants'];
}

// Pagination setup
$limit = 9; // Number of plants per page
$page = isset($_GET['page']) ? intval($_GET['page']) : 1;
$offset = ($page - 1) * $limit;

// Query to get plants with pagination and category filter
$categoryCondition = $selectedCategory === 'All' ? '' : "WHERE category = '$selectedCategory'";
$sql = "SELECT * FROM plants $categoryCondition LIMIT $limit OFFSET $offset";
$result = $conn->query($sql);

$products = [];

if ($result->num_rows > 0) {
    // Fetch the products
    while ($row = $result->fetch_assoc()) {
        $products[] = $row;
    }
}

// Close connection
$conn->close();
?>

<body>
    <!-- Preloader -->
    <div class="preloader d-flex align-items-center justify-content-center">
        <div class="preloader-circle"></div>
        <div class="preloader-img">
            <img src="../images/img/core-img/leaf.png" alt="" />
        </div>
    </div>

    <?php include '../components/userHeader.php'; ?>

    <!-- ##### Breadcrumb Area Start ##### -->
    <div class="breadcrumb-area">
        <!-- Top Breadcrumb Area -->
        <div class="top-breadcrumb-area bg-img bg-overlay d-flex align-items-center justify-content-center"
            style="background-image: url(../images/img/bg-img/24.jpg);">
            <h2>Shop</h2>
        </div>

        <div class="container">
            <div class="row">
                <div class="col-12">
                    <nav aria-label="breadcrumb">
                        <ol class="breadcrumb">
                            <li class="breadcrumb-item"><a href="#"><i class="fa fa-home"></i> Home</a></li>
                            <li class="breadcrumb-item active" aria-current="page">Shop</li>
                        </ol>
                    </nav>
                </div>
            </div>
        </div>
    </div>
    <!-- ##### Breadcrumb Area End ##### -->

    <!-- ##### Shop Area Start ##### -->
    <section class="shop-page section-padding-0-100">
        <div class="container">
            <div class="row">
                <!-- Shop Sorting Data -->
                <div class="col-12">
                    <div class="shop-sorting-data d-flex flex-wrap align-items-center justify-content-between">
                        <!-- Shop Page Count -->
                        <div class="shop-page-count">
                            <p>Showing
                                <?php echo $offset + 1; ?>-<?php echo min($offset + $limit, $categoryCounts['total_plants']); ?>
                                of <?php echo $categoryCounts['total_plants']; ?> results
                            </p>
                        </div>
                        <!-- Search by Terms -->
                        <div class="search_by_terms">
                            <form action="" method="post" class="form-inline">
                                <select name="category" class="custom-select widget-title"
                                    onchange="this.form.submit()">
                                    <option value="All" <?php echo $selectedCategory == 'All' ? 'selected' : ''; ?>>All
                                        Plants</option>
                                    <option value="Indoor Plants" <?php echo $selectedCategory == 'Indoor Plants' ? 'selected' : ''; ?>>Indoor Plants</option>
                                    <option value="Ayurvedic Plants" <?php echo $selectedCategory == 'Ayurvedic Plants' ? 'selected' : ''; ?>>Ayurvedic Plants</option>
                                    <option value="Seeds" <?php echo $selectedCategory == 'Seeds' ? 'selected' : ''; ?>>
                                        Seeds</option>
                                    <option value="Flowers" <?php echo $selectedCategory == 'Flowers' ? 'selected' : ''; ?>>Flowers</option>
                                    <option value="Climbers" <?php echo $selectedCategory == 'Climbers' ? 'selected' : ''; ?>>Climbers</option>
                                    <option value="Others" <?php echo $selectedCategory == 'Others' ? 'selected' : ''; ?>>
                                        Others</option>
                                </select>
                            </form>
                        </div>
                    </div>
                </div>
            </div>

            <div class="row">
                <!-- Sidebar Area -->
                <div class="col-12 col-md-4 col-lg-3">
                    <div class="shop-sidebar-area">
                        <!-- Shop Widget -->
                        <div class="shop-widget category mb-50">
                            <h4 class="widget-title">Categories</h4>
                            <div class="widget-desc">
                                <!-- Single Checkbox -->
                                <div class="custom-control custom-checkbox d-flex align-items-center mb-2">
                                    <input type="checkbox" class="custom-control-input" id="customCheck1" <?php echo $selectedCategory == 'All' ? 'checked' : ''; ?> />
                                    <label class="custom-control-label" for="customCheck1">All plants <span
                                            class="text-muted">(<?php echo $categoryCounts['total_plants']; ?>)</span></label>
                                </div>
                                <!-- Single Checkbox -->
                                <div class="custom-control custom-checkbox d-flex align-items-center mb-2">
                                    <input type="checkbox" class="custom-control-input" id="customCheck2" <?php echo $selectedCategory == 'Indoor Plants' ? 'checked' : ''; ?> />
                                    <label class="custom-control-label" for="customCheck2">Indoor plants <span
                                            class="text-muted">(<?php echo $categoryCounts['indoor_plants']; ?>)</span></label>
                                </div>
                                <!-- Single Checkbox -->
                                <div class="custom-control custom-checkbox d-flex align-items-center mb-2">
                                    <input type="checkbox" class="custom-control-input" id="customCheck3" <?php echo $selectedCategory == 'Ayurvedic Plants' ? 'checked' : ''; ?> />
                                    <label class="custom-control-label" for="customCheck3">Ayurvedic Plants <span
                                            class="text-muted">(<?php echo $categoryCounts['ayurvedic_plants']; ?>)</span></label>
                                </div>
                                <!-- Single Checkbox -->
                                <div class="custom-control custom-checkbox d-flex align-items-center mb-2">
                                    <input type="checkbox" class="custom-control-input" id="customCheck4" <?php echo $selectedCategory == 'Seeds' ? 'checked' : ''; ?> />
                                    <label class="custom-control-label" for="customCheck4">Seeds <span
                                            class="text-muted">(<?php echo $categoryCounts['seeds']; ?>)</span></label>
                                </div>
                                <!-- Single Checkbox -->
                                <div class="custom-control custom-checkbox d-flex align-items-center mb-2">
                                    <input type="checkbox" class="custom-control-input" id="customCheck5" <?php echo $selectedCategory == 'Flowers' ? 'checked' : ''; ?> />
                                    <label class="custom-control-label" for="customCheck5">Flowers <span
                                            class="text-muted">(<?php echo $categoryCounts['flowers']; ?>)</span></label>
                                </div>
                                <!-- Single Checkbox -->
                                <div class="custom-control custom-checkbox d-flex align-items-center mb-2">
                                    <input type="checkbox" class="custom-control-input" id="customCheck6" <?php echo $selectedCategory == 'Climbers' ? 'checked' : ''; ?> />
                                    <label class="custom-control-label" for="customCheck6">Climbers <span
                                            class="text-muted">(<?php echo $categoryCounts['climbers']; ?>)</span></label>
                                </div>
                                <!-- Single Checkbox -->
                                <div class="custom-control custom-checkbox d-flex align-items-center mb-2">
                                    <input type="checkbox" class="custom-control-input" id="customCheck7" <?php echo $selectedCategory == 'Others' ? 'checked' : ''; ?> />
                                    <label class="custom-control-label" for="customCheck7">Others <span
                                            class="text-muted">(<?php echo $categoryCounts['others']; ?>)</span></label>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <!-- All Products Area -->
                <div class="col-12 col-md-8 col-lg-9">
                    <div class="shop-products-area">
                        <div class="row">
                            <!-- Single Product -->
                            <?php foreach ($products as $product): ?>
                                <div class="col-12 col-sm-6 col-lg-4">
                                    <div class="single-product-area mb-50">
                                        <div class="product-img">
                                            <a
                                                href="/greenworld/user/shop-details.php?id=<?php echo urlencode($product['id']); ?>">
                                                <img src="<?php echo $product['imgUrl']; ?>" alt="" />
                                            </a>
                                        </div>
                                        <div class="product-info mt-15 text-center">
                                            <a href="shop_details.php?id=<?php echo $product['id']; ?>">
                                                <p><?php echo $product['name']; ?></p>
                                            </a>
                                            <h6>&#8377 &nbsp;<?php echo $product['price']; ?></h6>
                                        </div>
                                    </div>
                                </div>
                            <?php endforeach; ?>
                        </div>
                    </div>

                    <!-- Pagination -->
                    <nav aria-label="Page navigation">
                        <ul class="pagination">
                            <?php
                            $totalPages = ceil($categoryCounts['total_plants'] / $limit);
                            for ($i = 1; $i <= $totalPages; $i++):
                                ?>
                                <li class="page-item <?php echo ($i === $page) ? 'active' : ''; ?>">
                                    <a class="page-link" href="?page=<?php echo $i; ?>"><?php echo $i; ?></a>
                                </li>
                            <?php endfor; ?>
                            <?php if ($page < $totalPages): ?>
                                <li class="page-item">
                                    <a class="page-link" href="?page=<?php echo $page + 1; ?>"><i
                                            class="fa fa-angle-right"></i></a>
                                </li>
                            <?php endif; ?>
                        </ul>
                    </nav>
                </div>
            </div>
        </div>
    </section>
    <!-- ##### Shop Area End ##### -->

    <!-- All JS Files -->
    <?php include '../components/userScripts.php'; ?>
</body>

</html>