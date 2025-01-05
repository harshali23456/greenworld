<?php
include_once $_SERVER['DOCUMENT_ROOT'] . '/greenworld/includes/session.php';
?>
<!-- ##### Header Area Start ##### -->
<header class="header-area">
  <!-- ***** Navbar Area ***** -->
  <div class="alazea-main-menu">
    <div class="classy-nav-container breakpoint-off">
      <div class="container">
        <!-- Menu -->
        <nav class="classy-navbar justify-content-between" id="alazeaNav">
          <!-- Nav Brand -->
          <a href="/" class="nav-brand">
            <span class="green">GREEN</span><span class="white">WORLD</span>
          </a>

          <!-- Navbar Toggler -->
          <div class="classy-navbar-toggler">
            <span class="navbarToggler"><span></span><span></span><span></span></span>
          </div>

          <!-- Menu -->
          <div class="classy-menu">
            <!-- Close Button -->
            <div class="classycloseIcon">
              <div class="cross-wrap">
                <span class="top"></span><span class="bottom"></span>
              </div>
            </div>

            <!-- Navbar Start -->
            <div class="classynav">
              <ul>
                <?php if (isset($_SESSION)): ?>
                  <!-- If user is logged in -->
                  <li class="nav-items">
                    <i class="bi bi-cart"></i>
                    <a href="/greenworld/admin/adminDashboard.php">View Order</a>
                  </li>
                  <li class="nav-items">
                    <a href="/greenworld/admin/add_plant.php">Add Plant</a>
                </li>
                <li class="nav-items">
                  <a href="/greenworld/admin/update_plant.php">Update Plant</a>
                </li>
                  <li class="nav-items">
                    <a href="/greenworld/functions/logout.php">Logout</a>
                  </li>
                <?php else: ?>
                  <!-- If user is not logged in -->
                  <li><a href="/login">Login</a></li>
                  <li><a href="/signup">Sign Up</a></li>
                <?php endif; ?>
              </ul>
            </div>
            <!-- Navbar End -->
          </div>
        </nav>
      </div>
    </div>
  </div>
</header>
<!-- ##### Header Area End ##### -->
