<?php
// Initialize variables for alerts
$showAlert = false;
$showError = false;
$exists = false;
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Include file which makes the Database Connection.
    include 'includes/database.php';

    // Initialize variables
    $fullName = $_POST["fullName"];
    $phoneNo = $_POST["phoneNo"];
    $email = $_POST["email"];
    $password = $_POST["password"];

    // Initialize error message variables
    $exists = '';
    $showAlert = false;
    $showError = '';

    // Check password length
    if (strlen($password) < 8) {
        $showError = "Password length should be at least 8 characters.";
    } else {
        // Check if the email is already in use
        $sql = "SELECT * FROM users WHERE email=?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("s", $email);
        $stmt->execute();
        $result = $stmt->get_result();
        $num = $result->num_rows;

        if ($num > 0) {
            $exists = "Email already in use.";
        } else {
            // Hash the password before storing it
            $hashedPassword = password_hash($password, PASSWORD_BCRYPT);

            // Insert new user into the database
            $sql = "INSERT INTO users (fullName, phoneNo, email, password) VALUES (?, ?, ?, ?)";
            $stmt = $conn->prepare($sql);
            $stmt->bind_param("ssss", $fullName, $phoneNo, $email, $hashedPassword);
            $result = $stmt->execute();

            if ($result) {
                $showAlert = true;
                header("Location: /greenworld/login.php"); // Redirect to login page on successful signup
                exit();
            } else {
                $showError = "Something went wrong. Please try again.";
            }
        }
    }
    $stmt->close();
    $conn->close();
}
?>


<!DOCTYPE html>
<html lang="en">
<?php include_once 'components/meta.php'; ?>
<link rel="stylesheet" href="./stylesheets/style.css" />

<body>
    <!-- Preloader -->
    <div class="preloader d-flex align-items-center justify-content-center">
        <div class="preloader-circle"></div>
        <div class="preloader-img">
            <img src="./images/img/core-img/leaf.png" alt="Loading" />
        </div>
    </div>

    <?php include 'components/header.php'; ?>

    <section class="hero-area" style="height: 100vh">
        <div class="hero-post-slides owl-carousel">
            <!-- Single Hero Post -->
            <div class="single-hero-post bg-overlay">
                <!-- Post Image -->
                <div class="slide-img bg-img" style="background-image: url('./images/img/bg-img/1.jpg')"></div>
                <div class="container form-container">
                    <form class="login-form" method="POST" action="signup.php">
                        <!-- Full Name -->
                        <div class="form-group">
                            <label for="fullName">Full Name</label>
                            <input type="text" class="form-control" id="fullName" name="fullName"
                                placeholder="Enter Full Name" required>
                        </div>
                        <!-- Phone No. -->
                        <div class="form-group">
                            <label for="phoneNo">Phone No.</label>
                            <input type="tel" class="form-control" id="phoneNo" name="phoneNo" placeholder="99999 99999"
                                required>
                        </div>
                        <!-- Email -->
                        <div class="form-group">
                            <label for="email">Email</label>
                            <input type="email" class="form-control" id="email" name="email" placeholder="Enter Email"
                                required>
                        </div>
                        <!-- Password -->
                        <div class="form-group">
                            <label for="password">Password</label>
                            <input type="password" class="form-control" id="password" name="password"
                                placeholder="Enter Password" required>
                        </div>
                        <!-- Error Messages -->
                        <div class="error">
                            <?php if ($showAlert): ?>
                                <div class="alert alert-success alert-dismissible fade show" role="alert">
                                    <strong>Success!</strong> Your account is now created. You can <a
                                        href="/greenworld/login.php">login</a>.
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

                            <?php if ($exists): ?>
                                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                                    <strong>Error!</strong> <?php echo htmlspecialchars($exists); ?>
                                    <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                        <span aria-hidden="true">X</span>
                                    </button>
                                </div>
                            <?php endif; ?>
                        </div>
                        <!-- Submit and Reset Buttons -->
                        <div class="mr-auto ml-auto">
                            <button type="submit" class="btn btn-primary">Submit</button>
                            <button type="reset" class="btn btn-secondary">Reset</button>
                        </div>
                        <!-- Login Link -->
                        <p class="text-center mt-3">Already have an account? <a href="/greenworld/login.php">Login</a>
                        </p>
                    </form>
                </div>
            </div>
        </div>
    </section>

    <?php include 'components/scripts.php'; ?>
</body>

</html>