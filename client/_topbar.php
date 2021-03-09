<?php $current_page = substr($_SERVER["SCRIPT_NAME"],strrpos($_SERVER["SCRIPT_NAME"],"/")+1);?>
<nav> 
    <div class="top-bar">
        <div class="container">
            <div class="row">
                <div class="col-3">
                    <?php include 'images/chelipeacocklogo-text.svg';?>
                </div>
                <div class="col-8 offset-1 menu">
                    <a href="home.php" class="<?= $current_page == 'home.php' ? 'active':NULL; ?>"><i class="fas fa-home"></i></a>
                    <a href="crib-sheet.php" class="<?= $current_page == 'crib-sheet.php' ? 'active':NULL; ?>">Crib Sheet</a>
                    <a href="document-hub.php" class="<?= $current_page == 'document-hub.php' ? 'active':NULL; ?>">Document Hub</a>
                    <a href='maps.php' class="<?= $current_page == 'maps.php' ? 'active':NULL; ?>">Maps</a>
                    <a href="images.php" class="<?= $current_page == 'images.php' ? 'active':NULL; ?>">Images</a>
                    <a href="news.php" class="<?= $current_page == 'news.php' ? 'active':NULL; ?>">News</a>
                    <a href="account.php" class="<?= $current_page == 'account.php' ? 'active':NULL; ?>">My Account</a>
                    <a href="wishlist.php" class="<?= $current_page == 'wishlist.php' ? 'active':NULL; ?>">Wishlist</a>
                    <a class="" href="#" data-toggle="modal" data-target="#logoutModal">Log Out</a>
                </div>
            </div><!--r-->
        </div><!--c-->
    </div>
    <div class="main-nav">
        <div class="container">
            <div class="row">
                <div class="col-3"></div>
                <div class="col-8 offset-1 menu">
                    <a href="live_availability.php" class="<?= $current_page == 'live_availability.php' ? 'active':NULL; ?>">Live Availability</a>
                    <a href="rates.php?cntry=63129" class="<?= $current_page == 'rates.php' ? 'active':NULL; ?>">Rates</a>
                    <a href="flights_transfers.php" class="<?= $current_page == 'flights_transfers.php' ? 'active':NULL; ?>">Flights & Transfers</a>
                    <a href="itineraries.php" class="<?= $current_page == 'itineraries.php' ? 'active':NULL; ?>">Itineraries</a>
                    <a href="properties.php" class="<?= $current_page == 'properties.php' ? 'active':NULL; ?>">Properties</a>
                    <!--<a href="experiences.php" class="<?= $current_page == 'experiences.php' ? 'active':NULL; ?>">Experiences</a>-->
                    <a href="specials.php" class="<?= $current_page == 'specials.php' ? 'active':NULL; ?>">Specials</a>
                </div>
            </div><!--r-->
        </div><!--c-->
    </div>
    <?php if($_SESSION['cpadminloggedin']){?>
    <div style="width:100%; height:1px; background-color:red;"></div>
    <?php } ?>
</nav>

<!-- Sidebar Toggle (Topbar)
<button id="sidebarToggleTop" class="btn btn-link d-md-none rounded-circle mr-3">
<i class="fa fa-bars"></i>
</button>-->
