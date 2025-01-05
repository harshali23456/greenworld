<!DOCTYPE html>
<html lang="en">
<?php

include './components/meta.php';
include './includes/database.php';
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
  <!-- <div class="preloader d-flex align-items-center justify-content-center">
    <div class="preloader-circle"></div>
    <div class="preloader-img">
      <img src="images/img/core-img/leaf.png" alt="" />
    </div>
  </div> -->

  <!-- Header File -->
  <?php include 'components/header.php'; ?>

  <!-- Dynamic Page Content -->
  <?php
  // Determine which page to include
  $page = isset($_GET['page']) ? $_GET['page'] : 'index';

  // Path to the file
  $file = 'pages/' . $page . '.php';

  // Check if the file exists and include it, otherwise show 404 error
  if (file_exists($file)) {
    include $file;
  }
  ?>

  <!-- ##### Hero Area Start ##### -->
  <section class="hero-area">
    <div class="hero-post-slides owl-carousel">
      <!-- Single Hero Post -->
      <div class="single-hero-post bg-overlay">
        <!-- Post Image -->
        <div class="slide-img bg-img" style="background-image: url(./images/img/bg-img/1.jpg)"></div>
        <div class="container h-100">
          <div class="row h-100 align-items-center">
            <div class="col-12">
              <!-- Post Content -->
              <div class="hero-slides-content text-center">
                <h2>
                  I'm just a Plant Nerd, doing plant things.
                </h2>

              </div>
            </div>
          </div>
        </div>
      </div>

      <!-- Single Hero Post -->
      <div class="single-hero-post bg-overlay">
        <!-- Post Image -->
        <div class="slide-img bg-img" style="background-image: url(images/img/bg-img/2.jpg)"></div>
        <div class="container h-100">
          <div class="row h-100 align-items-center">
            <div class="col-12">
              <!-- Post Content -->
              <div class="hero-slides-content text-center">
                <h2>
                  IN every walk in the nature, one recieves far more than he seeks
                </h2>

              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </section>
  <!-- ##### Hero Area End ##### -->

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
                <select name="category" class="custom-select widget-title" onchange="this.form.submit()">
                  <option value="All" <?php echo $selectedCategory == 'All' ? 'selected' : ''; ?>>All
                    Plants</option>
                  <option value="Indoor Plants" <?php echo $selectedCategory == 'Indoor Plants' ? 'selected' : ''; ?>>
                    Indoor Plants</option>
                  <option value="Ayurvedic Plants" <?php echo $selectedCategory == 'Ayurvedic Plants' ? 'selected' : ''; ?>>Ayurvedic Plants</option>
                  <option value="Seeds" <?php echo $selectedCategory == 'Seeds' ? 'selected' : ''; ?>>
                    Seeds</option>
                  <option value="Flowers" <?php echo $selectedCategory == 'Flowers' ? 'selected' : ''; ?>>Flowers</option>
                  <option value="Climbers" <?php echo $selectedCategory == 'Climbers' ? 'selected' : ''; ?>>Climbers
                  </option>
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
                      <a href="/greenworld/login.php">
                        <img src="<?php echo str_replace('../', './', $product['imgUrl']); ?>" alt="" />

                      </a>
                    </div>
                    <div class="product-info mt-15 text-center">
                      <a href="/greenworld/user/shop-details.php?id=<?php echo urlencode($product['id']); ?>">
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
                  <a class="page-link" href="?page=<?php echo $page + 1; ?>"><i class="fa fa-angle-right"></i></a>
                </li>
              <?php endif; ?>
            </ul>
          </nav>
        </div>
      </div>
    </div>
  </section>
  <!-- ##### Shop Area End ##### -->


  <!-- ##### Contact Area Start ##### -->
  <section class="contact-area section-padding-100-0">
    <div class="container">
      <div class="row align-items-center justify-content-between">
        <div class="col-12 col-lg-5">
          <!-- Section Heading -->
          <div class="section-heading">
            <h2>GET IN TOUCH</h2>
            <p>Send us a message, we will call back later</p>
          </div>
          <!-- Contact Form Area -->
          <div class="contact-form-area mb-100">
            <form action="#" method="post">
              <div class="row">
                <div class="col-12 col-sm-6">
                  <div class="form-group">
                    <input type="text" class="form-control" id="contact-name" placeholder="Your Name" />
                  </div>
                </div>
                <div class="col-12 col-sm-6">
                  <div class="form-group">
                    <input type="email" class="form-control" id="contact-email" placeholder="Your Email" />
                  </div>
                </div>
                <div class="col-12">
                  <div class="form-group">
                    <input type="text" class="form-control" id="contact-subject" placeholder="Subject" />
                  </div>
                </div>
                <div class="col-12">
                  <div class="form-group">
                    <textarea class="form-control" name="message" id="message" cols="30" rows="10"
                      placeholder="Message"></textarea>
                  </div>
                </div>
                <div class="col-12">
                  <button type="submit" class="btn alazea-btn mt-15">
                    Send Message
                  </button>
                </div>
              </div>
            </form>
          </div>
        </div>

        <div class="col-12 col-lg-6">
          <!-- Google Maps -->
          <div class="map-area mb-100">
            <iframe
              src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d22236.40558254599!2d-118.25292394686001!3d34.057682914027104!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x80c2c75ddc27da13%3A0xe22fdf6f254608f4!2z4Kay4Ka4IOCmj-CmnuCnjeCmnOCnh-CmsuCnh-CmuCwg4KaV4KeN4Kav4Ka-4Kay4Ka_4Kar4KeL4Kaw4KeN4Kao4Ka_4Kav4Ka84Ka-LCDgpq7gpr7gprDgp43gppXgpr_gpqgg4Kav4KeB4KaV4KeN4Kak4Kaw4Ka-4Ka34KeN4Kaf4KeN4Kaw!5e0!3m2!1sbn!2sbd!4v1532328708137"
              allowfullscreen></iframe>
          </div>
        </div>
      </div>
    </div>
  </section>

  <!-- ##### Contact Area End ##### -->
  <!-- Footer -->
  <?php include 'components/footer.php'; ?>

  <!-- Scripts -->
  <?php include 'components/scripts.php'; ?>
</body>

</html>