<?PHP
include '../inc/db.php';     # $host  -  $user  -  $pass  -  $db
?>

<?php $templateName = 'locations';?>
<?php require_once('_header-admin.php'); ?>

          <!-- Countries Row -->
          <div class="row">
            <div class="clearfix"></div>
            <div class="card-body">
                <h6 class="mb-0 text-gray-800 "><strong>Countries</strong> <a href="addnewcountry.php" class="d-none d-sm-inline-block btn btn-sm shadow-sm">Add Country</a></h6>
              <div class="table-responsive">
            <table class="table" id="countriesTable" width="100%" cellspacing="0">
                  <thead>
                    <tr>
                      <th>Name</th>
                      <th>Regions</th>
                      <th>Properties</th>
                      <th></th>
                    </tr>
                  </thead>
                  <tbody>
                      <?= getCountries(); ?>
                  </tbody>
                </table>
                  </div>
                </div>
          </div>

        <!-- Regions Row -->
          <div class="row">
            <div class="clearfix"></div>
            <div class="card-body">
                <h6 class="mb-0 text-gray-800 "><strong>Regions</strong> <a href="addnewregion.php" class="d-none d-sm-inline-block btn btn-sm shadow-sm">Add Region</a></h6>
              <div class="table-responsive">
            <table class="table" id="regionsTable" width="100%" cellspacing="0">
                  <thead>
                    <tr>
                      <th>Name</th>
                      <th>Properties</th>
                      <th></th>
                    </tr>
                  </thead>
                   <tbody>
                      <?= getRegions(); ?>
                  </tbody>
                </table>
                  </div>
                </div>
          </div>

<?php require_once('_footer-admin.php'); ?>

</body>

</html>
