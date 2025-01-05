<?php
$showAlert = false;
$showError = false;

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Include file which makes the Database Connection.
    include 'includes/database.php';

    $email = $_POST["email"];
    $password = $_POST["password"];

    // Check if the email is the admin email
    if ($email === 'admin@gmail.com') {
        if ($password === 'admin') { 
            session_start();
            $_SESSION['admin'] = true; 
            header("Location: /greenworld/admin/adminDashboard.php"); 
            exit();
        } else {
            $showError = "Invalid admin password. Please try again.";
        }
    } else {
        // Check if the email exists in the database
        $sql = "SELECT * FROM users WHERE email='$email'";
        $result = mysqli_query($conn, $sql);
        $user = mysqli_fetch_assoc($result);

        if ($user) {
            // Verify the password
            if ($password === $user['password']) {
                // Start a session and set user session variables
                session_start();
                $_SESSION['user_id'] = $user['id'];
                $_SESSION['user_email'] = $user['email'];
                $_SESSION['user_name'] = $user['fullName'];

                // Redirect to user dashboard
                header("Location: /greenworld/user/dashboard.php");
                exit();
            } else {
                $showError = "Invalid password. Please try again.";
            }
        } else {
            $showError = "No account found with that email.";
        }
    }

    mysqli_close($conn);
}
?>


<!DOCTYPE html>
<html lang="en">
  <!-- Meta Information -->
  <?php include_once 'components/meta.php'; ?>
  <link rel="stylesheet" href="./stylesheets/style.css" />
<body>
  <!-- Preloader -->
  <div class="preloader d-flex align-items-center justify-content-center">
    <div class="preloader-circle"></div>
    <div class="preloader-img">
      <img src="/images/img/core-img/leaf.png" alt="Loading" />
    </div>
  </div>

  <!-- Header -->
  <?php include 'components/header.php'; ?>

  <!-- Hero Area Start -->
  <section class="hero-area" style="height: 100vh">
    <div class="hero-post-slides owl-carousel">
      <!-- Single Hero Post -->
      <div class="single-hero-post bg-overlay">
        <!-- Post Image -->
        <div class="slide-img bg-img" style="background-image: url('./images/img/bg-img/1.jpg')"></div>
        <div class="container form-container">
          <form class="login-form" method="POST" action="login.php">
            <!-- Email -->
            <div class="form-group">
              <label for="email">Email</label>
              <input type="email" class="form-control" id="email" name="email" placeholder="Enter Email" required />
            </div>
            <!-- Password -->
            <div class="form-group">
              <label for="password">Password</label>
              <input type="password" class="form-control" id="password" name="password" placeholder="Enter Password" required />
            </div>
            <!-- Error and Success Messages -->
            <div class="error">
                <?php if ($showAlert) : ?>
                    <div class="alert alert-success alert-dismissible fade show" role="alert">
                        <strong>Success!</strong> You are now logged in.
                        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                            <span aria-hidden="true">X</span>
                        </button>
                    </div>
                <?php endif; ?>

                <?php if ($showError) : ?>
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
            <!-- Sign Up Link -->
            <p class="text-center mt-3">
              Don't have an account? <a href="/signup.php">Sign Up</a>
            </p>
          </form>
        </div>
      </div>
    </div>
  </section>

  <!-- Scripts -->
  <?php include 'components/scripts.php'; ?>
</body>
</html>
