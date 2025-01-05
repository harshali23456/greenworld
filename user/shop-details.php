    <?php
    // Include database connection (adjust the path as needed)
    include '../includes/database.php';

    // Start session to check for user login status
    session_start();

    // Retrieve and sanitize the 'product_name' parameter from the URL
    $id = isset($_GET['id']) ? htmlspecialchars($_GET['id']) : '';

    // Check if the parameter is empty or invalid
    if (empty($id)) {
        echo "Product not found.";
        exit;
    }

    // Prepare and execute the SQL query to fetch product details
    $sql = "SELECT * FROM plants WHERE id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $id);
    $stmt->execute();
    $product = $stmt->get_result()->fetch_assoc();

    // Check if product details were found
    if (!$product) {
        echo "Product not found.";
        exit;
    }

    // Check if the user is logged in
    $isUserLoggedIn = isset($_SESSION['user_id']);

    // Check if the product is in stock
    $isInStock = $product['stock'] > 0;

    // Close database connection    
    $stmt->close();
    $conn->close();
    ?>

    <!DOCTYPE html>
    <html lang="en">
    <?php include '../components/userMeta.php'; ?>

    <body>
        <!-- Preloader -->
        <div class="preloader d-flex align-items-center justify-content-center">
            <div class="preloader-circle"></div>
            <div class="preloader-img">
                <img src="../images/img/core-img/leaf.png" alt="">
            </div>
        </div>

        <!-- ##### Header Area Start ##### -->
        <?php include '../components/userHeader.php'; ?>
        <!-- ##### Header Area End ##### -->

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
                                <li class="breadcrumb-item active" aria-current="page">Shop Details</li>
                            </ol>
                        </nav>
                    </div>
                </div>
            </div>
        </div>
        <!-- ##### Breadcrumb Area End ##### -->

        <!-- ##### Single Product Details Area Start ##### -->
        <section class="single_product_details_area mb-50">
            <div class="produts-details--content mb-50">
                <div class="container">
                    <div class="row justify-content-between">

                        <div class="col-12 col-md-6 col-lg-5">
                            <div class="single_product_thumb">
                                <div id="product_details_slider" class="carousel slide" data-ride="carousel">
                                    <div class="carousel-inner">
                                        <?php if (!empty($product['imgUrl'])): ?>
                                            <div class="carousel-item active">
                                                <a class="product-img"
                                                    href="<?php echo htmlspecialchars($product['imgUrl']); ?>"
                                                    title="Product Image">
                                                    <img class="d-block w-100"
                                                        src="<?php echo htmlspecialchars($product['imgUrl']); ?>"
                                                        alt="<?php echo htmlspecialchars($product['name']); ?>">
                                                </a>
                                            </div>
                                            <div class="carousel-item">
                                                <a class="product-img"
                                                    href="<?php echo htmlspecialchars($product['imgUrl']); ?>"
                                                    title="Product Image">
                                                    <img class="d-block w-100"
                                                        src="<?php echo htmlspecialchars($product['imgUrl']); ?>"
                                                        alt="<?php echo htmlspecialchars($product['name']); ?>">
                                                </a>
                                            </div>
                                            <div class="carousel-item">
                                                <a class="product-img"
                                                    href="<?php echo htmlspecialchars($product['imgUrl']); ?>"
                                                    title="Product Image">
                                                    <img class="d-block w-100"
                                                        src="<?php echo htmlspecialchars($product['imgUrl']); ?>"
                                                        alt="<?php echo htmlspecialchars($product['name']); ?>">
                                                </a>
                                            </div>
                                        <?php endif; ?>
                                    </div>
                                    <?php if (!empty($product['imgUrl'])): ?>
                                        <ol class="carousel-indicators">
                                            <li class="active" data-target="#product_details_slider" data-slide-to="0"
                                                style="background-image: url('<?php echo htmlspecialchars($product['imgUrl']); ?>');">
                                            </li>
                                            <li data-target="#product_details_slider" data-slide-to="1"
                                                style="background-image: url('<?php echo htmlspecialchars($product['imgUrl']); ?>');">
                                            </li>
                                            <li data-target="#product_details_slider" data-slide-to="2"
                                                style="background-image: url('<?php echo htmlspecialchars($product['imgUrl']); ?>');">
                                            </li>
                                        </ol>
                                    <?php endif; ?>
                                </div>
                            </div>
                        </div>

                        <div class="col-12 col-md-6">
                            <div class="single_product_desc">
                                <h4 class="title"><?php echo htmlspecialchars($product['name']); ?></h4>
                                <h4 class="price">&#8377;&nbsp;<?php echo number_format($product['price'], 2); ?></h4>
                                <div class="short_overview">
                                    <p><?php echo htmlspecialchars($product['description']); ?></p>
                                </div>

                                <div class="cart--area d-flex flex-wrap align-items-center">
                                    <!-- Add to Cart Form -->
                                    <form class="cart clearfix d-flex align-items-center" method="post"
                                        action="/greenworld/functions/addtocart.php">
                                        <input type="hidden" name="product_id"
                                            value="<?php echo htmlspecialchars($product['id']); ?>">
                                            <input type="hidden" name="user_id"
                                            value="<?php echo $isUserLoggedIn ? htmlspecialchars($_SESSION['user_id']) : ''; ?>">
                                        <div class="quantity">
                                            <span class="qty-minus"
                                                onclick="var effect = document.getElementById('qty'); var qty = effect.value; if( !isNaN( qty ) && qty > 1 ) effect.value--;return false;"><i
                                                    class="fa fa-minus" aria-hidden="true"></i></span>
                                            <input type="number" class="qty-text" id="qty" step="1" min="1" max="12"
                                                name="quantity" value="1" <?php echo !$isInStock ? 'disabled' : ''; ?>>
                                            <span class="qty-plus"
                                                onclick="var effect = document.getElementById('qty'); var qty = effect.value; if( !isNaN( qty )) effect.value++;return false;"><i
                                                    class="fa fa-plus" aria-hidden="true"></i></span>
                                        </div>
                                        <button type="submit" name="addtocart" class="btn alazea-btn ml-15" 
                                        <?php echo !$isUserLoggedIn || !$isInStock ? 'disabled' : ''; ?>>
                                            Add to cart
                                        </button>
                                    </form>
                                </div>

                                <div class="products--meta">
                                    <p><span>SKU:</span> <span><?php echo htmlspecialchars($product['sku']); ?></span></p>
                                    <p><span>Category:</span>
                                        <span><?php echo htmlspecialchars($product['category']); ?></span>
                                    </p>
                                    <p><Span>Stock:</Span><span><?php echo $isInStock ? 'In Stock' : 'Out of Stock'; ?></span></p>
                                    <?php
                                    // Decode the JSON string to an array
                                    $tags = json_decode($product['tags'], true);

                                    // Check if decoding was successful and it's an array
                                    if (is_array($tags)) {
                                        // Join the tags with ', '
                                        $tagsList = htmlspecialchars(implode(', ', $tags));
                                    } else {
                                        // Handle the case where $tags is not a valid array
                                        $tagsList = 'No tags available';
                                    }
                                    ?>
                                    <p><span>Tags:</span> <span><?php echo $tagsList; ?></span></p>
                                    <span>Share on:</span>&nbsp;&nbsp;
                                    <span>
                                        <a href="#"><i class="fa fa-facebook"></i></a>&nbsp;&nbsp;
                                        <a href="#"><i class="fa fa-twitter"></i></a>&nbsp;&nbsp;
                                        <a href="#"><i class="fa fa-pinterest"></i></a>&nbsp;&nbsp;
                                        <a href="#"><i class="fa fa-google-plus"></i></a>&nbsp;&nbsp;
                                    </span>
                                    </p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="container">
                <div class="row">
                    <div class="col-12">
                        <div class="product_details_tab clearfix">
                            <!-- Tabs -->
                            <ul class="nav nav-tabs" role="tablist" id="product-details-tab">
                                <li class="nav-item">
                                    <a href="#description" class="nav-link active" data-toggle="tab"
                                        role="tab">Description</a>
                                </li>
                                <li class="nav-item">
                                    <a href="#addi-info" class="nav-link" data-toggle="tab" role="tab">Additional
                                        Information</a>
                                </li>
                                <?php
                                $reviews = json_decode($product['reviews'], true);
                                if (is_array($reviews)) {
                                    $reviewCount = count($reviews);
                                } else {
                                    $reviewCount = 0;
                                }
                                ?>
                                <li class="nav-item">
                                    <a href="#reviews" class="nav-link" data-toggle="tab" role="tab">Reviews <span
                                            class="text-muted">(<?php echo $reviewCount; ?>)</span></a>
                                </li>
                            </ul>
                            <!-- Tab Content -->
                            <div class="tab-content">
                                <div role="tabpanel" class="tab-pane fade show active" id="description">
                                    <div class="description_area">
                                        <p><?php echo htmlspecialchars($product['full_description']); ?></p>
                                    </div>
                                </div>
                                <div role="tabpanel" class="tab-pane fade" id="addi-info">
                                    <?php
                                    if (is_string($product['additional_info'])) {
                                        $additionalInfo = json_decode($product['additional_info'], true);
                                        if (json_last_error() === JSON_ERROR_NONE && is_array($additionalInfo)) {
                                            $product['additional_info'] = $additionalInfo;
                                        } else {
                                            $product['additional_info'] = [];
                                        }
                                    } else {
                                        $product['additional_info'] = is_array($product['additional_info']) ? $product['additional_info'] : [];
                                    }
                                    ?>

                                    <div class="additional_info_area">
                                        <?php foreach ($product['additional_info'] as $info): ?>
                                            <p><?php echo htmlspecialchars($info['question']); ?><br>
                                                <span><?php echo htmlspecialchars($info['answer']); ?></span>
                                            </p>
                                        <?php endforeach; ?>
                                    </div>

                                </div>
                                <div role="tabpanel" class="tab-pane fade" id="reviews">
                                    <div class="reviews_area">
                                        <?php
                                        if (is_string($product['reviews'])) {
                                            $reviews = json_decode($product['reviews'], true);

                                            if (json_last_error() === JSON_ERROR_NONE && is_array($reviews)) {
                                                $product['reviews'] = $reviews;
                                            } else {
                                                $product['reviews'] = [];
                                            }
                                        } else {
                                            $product['reviews'] = is_array($product['reviews']) ? $product['reviews'] : [];
                                        }
                                        ?>

                                        <ul>
                                            <?php foreach ($product['reviews'] as $review): ?>
                                                <li>
                                                    <div class="single_user_review mb-15">
                                                        <div class="review-rating">
                                                            <?php for ($i = 0; $i < 5; $i++): ?>
                                                                <i class="fa fa-star<?php echo $i < $review['rating'] ? '' : '-o'; ?>"
                                                                    aria-hidden="true"></i>
                                                            <?php endfor; ?>
                                                            <span>for
                                                                <?php echo htmlspecialchars($review['category']); ?></span>
                                                        </div>
                                                        <div class="review-details">
                                                            <p>by <a
                                                                    href="#"><?php echo htmlspecialchars($review['reviewer']); ?></a>
                                                                on <span><?php echo htmlspecialchars($review['date']); ?></span>
                                                            </p>
                                                        </div>
                                                    </div>
                                                </li>
                                            <?php endforeach; ?>
                                        </ul>
                                    </div>

                                    <div class="submit_a_review_area mt-50">
                                        <h4>Submit A Review</h4>
                                        <form action="#" method="post">
                                            <div class="row">
                                                <div class="col-md-6">
                                                    <div class="form-group">
                                                        <label for="name">Your Name <span>*</span></label>
                                                        <input type="text" class="form-control" id="name" name="name"
                                                            required>
                                                    </div>
                                                </div>
                                                <div class="col-md-6">
                                                    <div class="form-group">
                                                        <label for="email">Your Email <span>*</span></label>
                                                        <input type="email" class="form-control" id="email" name="email"
                                                            required>
                                                    </div>
                                                </div>
                                                <div class="col-12">
                                                    <div class="form-group">
                                                        <label for="review">Your Review <span>*</span></label>
                                                        <textarea class="form-control" id="review" name="review" rows="5"
                                                            required></textarea>
                                                    </div>
                                                </div>
                                                <div class="col-12">
                                                    <div class="form-group">
                                                        <label for="rating">Rating <span>*</span></label>
                                                        <select class="form-control" id="rating" name="rating" required>
                                                            <option value="5">5 Stars</option>
                                                            <option value="4">4 Stars</option>
                                                            <option value="3">3 Stars</option>
                                                            <option value="2">2 Stars</option>
                                                            <option value="1">1 Star</option>
                                                        </select>
                                                    </div>
                                                </div>
                                                <div class="col-12">
                                                    <button type="submit" class="btn alazea-btn">Submit Review</button>
                                                </div>
                                            </div>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>
        <!-- ##### Single Product Details Area End ##### -->

        <?php include '../components/userScripts.php'; ?>
    </body>

    </html>
