asdsaddasd        <nav class="navbar navbar-expand navbar-light bg-grey1 topbar static-top">

          <!-- Sidebar Toggle (Topbar) -->
          <button id="sidebarToggleTop" class="btn btn-link d-md-none rounded-circle mr-3">
            <i class="fa fa-bars"></i>
          </button>
		<?php $current_page = substr($_SERVER["SCRIPT_NAME"],strrpos($_SERVER["SCRIPT_NAME"],"/")+1);?>
         <div class="col-md-12 text-right"><p class="m-top"><a href="../home.php" class="smaller toplnk  <?= $current_page == 'home.php' ? 'active':NULL; ?>">CRIB SHEET</a><a href="../hub.php" class="smaller toplnk  <?= $current_page == 'hub.php' ? 'active':NULL; ?>">DOCUMENT HUB</a><a href="../maps.php" class="smaller toplnk  <?= $current_page == 'maps.php' ? 'active':NULL; ?>">MAPS</a><a href="../images.php" class="smaller toplnk  <?= $current_page == 'images.php' ? 'active':NULL; ?>">IMAGES</a><a href="../news.php" class="smaller toplnk <?= $current_page == 'news.php' ? 'active':NULL; ?>">NEWS</a><a href="../account.php" class="smaller toplnk  <?= $current_page == 'account.php' ? 'active':NULL; ?>">MY ACCOUNT</a><a href="../wishlist.php" class="smaller toplnk  <?= $current_page == 'wishlist.php' ? 'active':NULL; ?>">WISHLIST</a><a class="d-none d-sm-inline-block btn btn-sm shadow-sm" href="#" data-toggle="modal" data-target="#logoutModal">Log Out</a></div>

        </nav>

		<nav class="navbar navbar-expand navbar-light bg-grey2 topbar mb-4 static-top">

         <div class="col-md-3 small">LOGO</div>
        <div class="col-md-9 small text-right"><a href="../availability14_rr.php" class="small midlnk  <?= $current_page == 'availability14_rr.php' ? 'active':NULL; ?>">14 DAY AVAILABILITY</a><a href="../availability60_rr.php" class="small midlnk  <?= $current_page == 'availability60_rr.php' ? 'active':NULL; ?>">60 DAY AVAILABILITY</a><a href="../rates.php" class="small midlnk  <?= $current_page == 'rates.php' ? 'active':NULL; ?>">RATES</a><a href="../flights.php" class="small midlnk  <?= $current_page == 'flights.php' ? 'active':NULL; ?>">FLIGHTS & TRANSFERS</a><a href="../itineraries.php" class="small midlnk  <?= $current_page == 'itineraries.php' ? 'active':NULL; ?>">ITINERARIES</a><a href="../maps.php" class="small midlnk  <?= $current_page == 'maps.php' ? 'active':NULL; ?>">MAPS</a><a href="../gallery.php" class="small midlnk  <?= $current_page == 'gallery.php' ? 'active':NULL; ?>">GALLERY</a></div>

        </nav>
