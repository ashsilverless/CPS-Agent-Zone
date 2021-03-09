<?PHP
include 'inc/db.php';     # $host  -  $user  -  $pass  -  $db
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
<style>
	.Short {
        width: 100%;
        background-color: #dc3545;
        margin: 10px 0;
        height: 3px;
        color: #dc3545;
        font-weight: 500;
        font-size: 1em;
    }
    .Weak {
        width: 100%;
        background-color: #ffc107;
        margin: 10px 0;
        height: 3px;
        color: #ffc107;
        font-weight: 500;
        font-size: 1em;
    }
    .Strong {
        width: 100%;
        background-color: #28a745;
        margin: 10px 0;
        height: 3px;
        color: #28a745;
        font-weight: 500;
        font-size: 1em;
    }
</style>
    <!-- Begin Page Content -->
	<main>
		<div class="container">
			<h1 class="heading heading__1">Account Dashboard</h1>
			<div class="introduction"><?=$intro_text;?></div>
			<div class="row">
				<div class="col-md-2">
					<div class="sub-nav sidebar">
						<a href="account.php"><i class="fas fa-user"></i>Profile</a>
						<a href="dashboard.php"><i class="fas fa-tachometer-alt"></i>Dashboard</a>
						<a href="pwsecure.php"><i class="fas fa-lock"></i>Security</a>
					</div>
				</div>
				<div class="col-md-10">
					<div class="content-wrapper">
						<div class="msg"><p>&nbsp;</p></div>
							<form action="#" method="POST" id="passworddetails" name="passworddetails"><input name="user_id" type="hidden" id="user_id" value="<?=$user_id;?>">
								<div class="agent-table">
									<div class="agent-table__item current">
										<div class="agent-fieldname">Current Password</div>
										<div class="agent-data"><input type="password" id="password" name="password" value="" autocomplete="new-password" autofill="off" class="mb1"></div>
										<div id="blank"></div>
									</div>
									<div class="agent-table__item new">
										<div class="agent-fieldname">New Password</div>
										<div class="agent-data"><input type="password" id="newpassword" name="newpassword" value="" class="mb1"></div>
										<div class="confirm-message mb3" id="strengthMessage"></div>
									</div>
									<div class="agent-table__item confirm">
									    <div class="agent-fieldname">Confirm New Password</div>
										<div class="agent-data"><input type="password" id="confirmpassword" name="confirmpassword" value="" class="mb1"></div>
										<div class="confirm-message"><span id="message"></span></div>
									</div>

									<div class="agent-table__item submit">
										<div><button type="submit" value="Save" class="save button"><i class="far fa-save"></i> Save</button></div>
									</div>

							  </div>
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

	function checkPasswordStrength(password) {
        var numbers = /([0-9])/;
        var lettersLower = /([a-z])/;
        var lettersUpper = /([A-Z])/;
        var special_characters = /([~,!,@,#,$,%,^,&,*,-,_,+,=,>,<])/;

        if(password.length<6) {
            $('#strengthMessage').removeClass();
            $('#strengthMessage').addClass('Short');
            $('#submit').val('>> Disabled <<');
            $('#submit').prop('disabled', true);
            $('#strengthMessage').html("Weak (should be atleast 6 characters.)");
        } else {
            if(password.match(numbers) && password.match(lettersLower) && password.match(lettersUpper) && password.match(special_characters)) {
                $('#strengthMessage').removeClass();
                $('#strengthMessage').addClass('Strong');
                $('#strengthMessage').html("Strong Password");
            } else {
                $('#strengthMessage').removeClass();
                $('#strengthMessage').addClass('Weak');
                $('#strengthMessage').html("Weak (should include letters with mixed case, numbers and special characters.)");
                $('#submit').val('>> Disabled <<');
                $('#submit').prop('disabled', true);
            }
        }
    }

	$('#newpassword').on('keyup', function() {
        checkPasswordStrength($('#newpassword').val());
    });

    $('#confirmpassword').on('keyup', function() {

        if ($('#newpassword').val() == $('#confirmpassword').val()) {
            $('#message').html('<b>Matching</b>').css('color', 'green');
            $('#submit').val('Save Changes');
            $('#submit').prop('disabled', false);
        } else {
            $('#message').html('<b>Not Matching</b>').css('color', 'red');
            $('#submit').val('>> Disabled <<');
            $('#submit').prop('disabled', true);
        }

    });

	$("#passworddetails").submit(function(e) {
		e.preventDefault();
         var uid = ($("#user_id").val());
		 var pw = ($("#password").val());
		 var npw = ($("#confirmpassword").val());

         $.ajax({
            type: "POST",
            url: 'updateagentpw.php',
            data: {user_id: uid, password: pw, confirmpassword: npw},
            success: function(response)
            {
              $('.msg').html("'"+response+"'");

           }
       });
    })


});

</script>

</body>
</html>
