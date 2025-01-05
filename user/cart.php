<?php
include '../includes/database.php';
include '../includes/session.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: /greenworld/login.php");
    exit;
}

$user_id = $_SESSION['user_id'];

$sql = "SELECT c.product_id, p.name AS productName, p.imgUrl AS productImages, p.price AS productPrice, c.quantity 
        FROM cart c
        JOIN plants p ON c.product_id = p.id
        WHERE c.user_id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $user_id);
$stmt->execute();

$result = $stmt->get_result();
$cartItems = [];
while ($row = $result->fetch_assoc()) {
    $images = explode(',', $row['productImages']);
    $row['images'] = trim($images[0]);
    $row['totalPrice'] = $row['productPrice'] * $row['quantity'];

    $cartItems[] = $row;
}

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
            <img src="../images/img/core-img/leaf.png" alt="Preloader Image" />
        </div>
    </div>

    <!-- Header -->
    <?php include ('../components/userHeader.php'); ?>

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

    <!-- Cart Area -->
    <div class="cart-area section-padding-0-100 clearfix">
        <div class="container">
            <div class="row">
                <div class="col-12">
                    <div class="cart-table clearfix">
                        <?php if (!empty($cartItems)): ?>
                            <form action="/greenworld/checkout.php" method="post">
                                <table class="table table-responsive">
                                    <thead>
                                        <tr>
                                            <th>Products</th>
                                            <th>Quantity</th>
                                            <th>Price</th>
                                            <th>Total</th>
                                            <th></th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php foreach ($cartItems as $item): ?>
                                            <tr>
                                                <td class="cart_product_img">
                                                    <?php if (!empty($item['images'])): ?>
                                                        <a href="#"><img src="<?= htmlspecialchars($item['images']) ?>"
                                                                alt="<?= htmlspecialchars($item['productName']) ?>" /></a>
                                                    <?php else: ?>
                                                        <a href="#"><img src="../images/default.jpg" alt="Default Image" /></a>
                                                    <?php endif; ?>
                                                    <h5><?= htmlspecialchars($item['productName']) ?></h5>
                                                </td>
                                                <td class="qty">
                                                    <div class="quantity">
                                                        <span class="qty-minus"
                                                            onclick="updateQuantity('<?= htmlspecialchars($item['product_id']) ?>', -1)"><i
                                                                class="fa fa-minus" aria-hidden="true"></i></span>
                                                        <input type="number" class="qty-text"
                                                            id="qty-<?= htmlspecialchars($item['product_id']) ?>" step="1"
                                                            min="1" max="99" name="quantity[]"
                                                            value="<?= htmlspecialchars($item['quantity']) ?>" />
                                                        <span class="qty-plus"
                                                            onclick="updateQuantity('<?= htmlspecialchars($item['product_id']) ?>', 1)"><i
                                                                class="fa fa-plus" aria-hidden="true"></i></span>
                                                    </div>
                                                </td>
                                                <td class="price"><span>₹<?= htmlspecialchars($item['productPrice']) ?></span>
                                                </td>
                                                <td class="total_price">
                                                    <span>₹<?= htmlspecialchars($item['totalPrice']) ?></span>
                                                </td>
                                                <td class="action">
                                                    <form action="/greenworld/cart_remove.php" method="post"
                                                        style="display:inline;">
                                                        <input type="hidden" name="productId"
                                                            value="<?= htmlspecialchars($item['product_id']) ?>" />
                                                        <button type="submit" class="btn btn-danger"><i
                                                                class="icon_close"></i></button>
                                                    </form>
                                                </td>
                                            </tr>
                                        <?php endforeach; ?>
                                    </tbody>
                                </table>

                                <!-- Shipping Address -->
                                <div class="shipping d-flex justify-content-between">
                                    <h5>Shipping</h5>
                                    <div class="shipping-address">
                                        <select class="custom-select" name="country">
                                            <option selected>State</option>
                                            <option value="MH">Maharashtra</option>
                                        </select>
                                        <input type="text" name="district" id="shipping-district" placeholder="District" />
                                        <input type="text" name="zip" id="shipping-zip" placeholder="ZIP" />
                                    </div>
                                </div>
                                <button onclick="validateShipping()">Submit</button>
                                <script>
                                    const validPincodes = {
                                        Mumbai: [
                                            400001, 400002, 400003, 400004, 400005, 400006, 400007, 400008, 400009, 400010,
                                            400011, 400012, 400013, 400014, 400015, 400016, 400017, 400018, 400019, 400020,
                                            400021, 400022, 400023, 400024, 400025, 400026, 400027, 400028, 400029, 400030,
                                            400031, 400032, 400033, 400034, 400035, 400036, 400037, 400038, 400039, 400040,
                                            400041, 400042, 400043, 400044, 400045, 400046, 400047, 400048, 400049, 400050,
                                            400051, 400052, 400053, 400054, 400055, 400056, 400057, 400058, 400059, 400060
                                        ],
                                        Pune: [
                                            411001, 411002, 411003, 411004, 411005, 411006, 411007, 411008, 411009, 411010,
                                            411011, 411012, 411013, 411014, 411015, 411016, 411017, 411018, 411019, 411020,
                                            411021, 411022, 411023, 411024, 411025, 411026, 411027, 411028, 411029, 411030
                                        ],
                                        Nagpur: [
                                            440001, 440002, 440003, 440004, 440005, 440006, 440007, 440008, 440009, 440010,
                                            440011, 440012, 440013, 440014, 440015, 440016, 440017, 440018, 440019, 440020
                                        ],
                                        Nashik: [
                                            422001, 422002, 422003, 422004, 422005, 422006, 422007, 422008, 422009, 422010
                                        ],
                                        Thane: [
                                            400601, 400602, 400603, 400604, 400605, 400606, 400607, 400608, 400609, 400610,
                                            400611, 400612, 400613, 400614, 400615, 400616, 400617, 400618, 400619, 400620
                                        ],
                                        Aurangabad: [
                                            431001, 431002, 431003, 431004, 431005, 431006, 431007, 431008, 431009, 431010
                                        ],
                                        Solapur: [
                                            413001, 413002, 413003, 413004, 413005, 413006, 413007, 413008, 413009, 413010
                                        ],
                                        Kolhapur: [
                                            416001, 416002, 416003, 416004, 416005, 416006, 416007, 416008, 416009, 416010
                                        ],
                                        Sangli: [
                                            416416, 416410, 416411, 416413, 416414, 416416, 416410, 416417, 416418, 416419
                                        ],
                                        Jalgaon: [
                                            425001, 425002, 425003, 425004, 425005, 425006, 425007, 425008, 425009, 425010
                                        ],
                                        Akola: [
                                            444001, 444002, 444003, 444004, 444005, 444006, 444007, 444008, 444009, 444010
                                        ],
                                        Amravati: [
                                            444601, 444602, 444603, 444604, 444605, 444606, 444607, 444608, 444609, 444610
                                        ],
                                        Satara: [
                                            415001, 415002, 415003, 415004, 415005, 415006, 415007, 415008, 415009, 415010
                                        ],
                                        Ratnagiri: [
                                            415612, 415621, 415620, 415713, 415617, 415619, 415712, 415709, 415718, 415706
                                        ]
                                    };


                                    function validateShipping() {
                                        const district = document.getElementById('shipping-district').value.trim();
                                        const zip = parseInt(document.getElementById('shipping-zip').value.trim(), 10);

                                        console.log("Entered district:", district);
                                        console.log("Entered ZIP code:", zip);

                                        if (validPincodes[district]) {
                                            console.log("Valid district found.");
                                            if (validPincodes[district].includes(zip)) {
                                                alert('Valid shipping address');
                                                // Proceed with form submission or other actions
                                            } else {
                                                alert('Invalid ZIP code for the selected district. Please enter a valid ZIP code of the entered district.');
                                            }
                                        } else {
                                            alert('Invalid district. Please enter a valid district.');
                                        }
                                    }
                                </script>

                                <!-- Payment Type -->
                                <div class="payment d-flex justify-content-between mt-20">
                                    <h5>Payment Type</h5>
                                    <select class="custom-select" name="payment_type">
                                        <option value="cod">Cash on Delivery</option>
                                    </select>
                                </div>

                                <div class="checkout-btn">
                                    <button type="submit" class="btn alazea-btn w-100">PROCEED TO CHECKOUT</button>
                                </div>
                            </form>
                        <?php else: ?>
                            <div class="alert alert-warning">Your cart is empty.</div>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <?php include ('../components/userScripts.php'); ?>

    <script>
        function updateQuantity(productId, delta) {
            const quantityInput = document.getElementById(`qty-${productId}`);
            let quantity = parseInt(quantityInput.value, 10);
            if (!isNaN(quantity) && (quantity > 1 || delta === 1)) {
                quantity += delta;
                quantityInput.value = quantity;

                fetch('/greenworld/cart_update.php', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-Token': '<?= $_SESSION['csrf_token'] ?>' // Ensure your CSRF token handling
                    },
                    body: JSON.stringify({ productId, quantity })
                })
                    .then(response => {
                        if (response.ok) {
                            window.location.reload();
                        } else {
                            console.error('Failed to update quantity');
                        }
                    })
                    .catch(error => console.error('Error:', error));
            }
        }
    </script>
</body>

</html>