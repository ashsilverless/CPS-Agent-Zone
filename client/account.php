<?PHP
include 'inc/db.php';     # $host  -  $user  -  $pass  -  $db

$intro_text = nl2br(getField('tbl_page_data','intro_text','page_name','account'));

try {
	// Connect and create the PDO object
	$conn = new PDO("mysql:host=$host; dbname=$db", $user, $pass);
	$conn->exec("SET CHARACTER SET $charset");      // Sets encoding UTF-8

	$query = "SELECT * FROM tbl_agents WHERE id = ".$_SESSION['user_id'].";";

	$result = $conn->prepare($query);
	$result->execute();

	  // Parse returned data
	  while($row = $result->fetch(PDO::FETCH_ASSOC)) {
		  // Verification success!
			session_regenerate_id();
			$real_name = $row['real_name'];
            $user_name = $row['user_name'];
            $telephone = $row['contact_telephone'];
		    $email_address = $row['email_address'];
            $user_id = $row['id'];
	  }

	$conn = null;        // Disconnect
}
catch(PDOException $e) {
  echo $e->getMessage();
}?>
<?php $templateName = 'account';?>
<?php require_once('_header.php'); ?>
<!-- Begin Page Content -->
	<main>
		<div class="container">
			<h1 class="heading heading__1">Account Dashboard</h1>
			<div class="introduction"><?=$intro_text;?></div>
			<div class="row">
				<div class="col-md-2">
					<div class="sub-nav sidebar">
						<a href="account.php" class="active"><i class="fas fa-user"></i>Profile</a>
						<a href="pwsecure.php"><i class="fas fa-lock"></i>Security</a>
						<a href="dashboard.php"><i class="fas fa-chart-line"></i>Reports</a>

					</div>
				</div>
				<div class="col-md-10">
					<div class="content-wrapper">
						<div class="msg"></div>
							<form action="#" method="POST" id="agentdetails" name="agentdetails"><input name="user_id" type="hidden" id="user_id" value="<?=$user_id;?>">
								<div class="agent-table">
									<div class="agent-table__item">
										<div class="agent-fieldname">Agent Name</div>
										<div class="agent-data"><input type="text" id="real_name" name="real_name" value="<?=$real_name;?>"></div>
									</div>

									<div class="agent-table__item">
										<div class="agent-fieldname">Username</div>
										<div class="agent-data"><input type="text" id="user_name" name="user_name" value="<?=$user_name;?>"></div>
									</div>

									<div class="agent-table__item">
										<div class="agent-fieldname">Email Address</div>
										 <div class="agent-data"><input type="text" id="email_address" name="email_address" value="<?=$email_address;?>"></div>
									</div>

									<div class="agent-table__item">
										<div class="agent-fieldname">Telephone</div>
										<div class="agent-data"><input type="text" id="contact_telephone" name="contact_telephone" value="<?=$telephone;?>"></div>
									</div>

									<div class="agent-table__item submit">
										<div><button type="submit" value="Save" class="save button"><i class="far fa-save"></i> Save</button></div>
									</div>


							  </div><!--head-->
						</form>
					</div><!-- End of content-wrapper -->
				</div>
			</div>
		</div>
  	</main>
	<!-- End of Page Content -->

	<!-- Footer -->
	<?php require_once('_footer.php'); ?>
	<!-- End of Footer -->

<?php require_once('modals/logout.php'); ?>
<?php require_once('_global-scripts.php'); ?>

<script type="text/javascript">
function getParameterByName(name, url) {
	if (!url) url = window.location.href;
	name = name.replace(/[\[\]]/g, "\\$&");
	var regex = new RegExp("[?&]" + name + "(=([^&#]*)|&|#|$)"),
		results = regex.exec(url);
	if (!results) return null;
	if (!results[2]) return '';
	return decodeURIComponent(results[2].replace(/\+/g, " "));
}

$(function () {
  $('[data-toggle="tooltip"]').tooltip()
})

// Initialize popover component
$(function () {
  $('[data-toggle="popover"]').popover({html : true})
})

$(document).ready(function() {

	$('.msg').hide();

	$("#agentdetails").submit(function(e) {
		e.preventDefault();
         var uid = ($("#user_id").val());
		 var rn = ($("#real_name").val());
		 var un = ($("#user_name").val());
		 var ea = ($("#email_address").val());
		 var ct = ($("#contact_telephone").val());

         $.ajax({
            type: "POST",
            url: 'updateagent.php',
            data: {user_id: uid, real_name: rn, user_name: un, email_address: ea, contact_telephone: ct},
            success: function(response)
            {
              $(".msg").html("<p>Details Saved</p>");
			  $('.msg').show().delay(2000).hide();
           }
       });
    })


});

</script>

</body>
</html>
