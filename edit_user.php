<?PHP
include '../inc/db.php';     # $host  -  $user  -  $pass  -  $db

$user_id = $_GET['id'];
try {
	// Connect and create the PDO object
	$conn = new PDO("mysql:host=$host; dbname=$db", $user, $pass);
	$conn->exec("SET CHARACTER SET $charset");      // Sets encoding UTF-8
	$query = "SELECT * FROM tbl_users WHERE id = $user_id;";

	$result = $conn->prepare($query);
	$result->execute();

	while($row = $result->fetch(PDO::FETCH_ASSOC)) {
		$info[] = $row;
	}

	$conn = null;        // Disconnect

}
catch(PDOException $e) {
  echo $e->getMessage();
}

$info[0]['agent_level'] <4 ? $utype = '' : $utype = 'checked';
?>
<script src="https://code.jquery.com/jquery-3.4.1.js" integrity="sha256-WpOohJOqMqqyKL9FccASB9O0KwACQJpFTUBLTYOVvVU=" crossorigin="anonymous"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-easing/1.4.1/jquery.easing.min.js"></script>
<script src="js/bootstrap.bundle.js"></script>
<link href="https://gitcdn.github.io/bootstrap-toggle/2.2.2/css/bootstrap-toggle.min.css" rel="stylesheet">
<script src="https://gitcdn.github.io/bootstrap-toggle/2.2.2/js/bootstrap-toggle.min.js"></script>
<form action="edituser.php?id=<?=$info[0]['id'];?>" method="POST">
					<table class="table" id="newuadmin" width="100%" cellspacing="0">
						  <thead>
							<tr>
							  <th>First Name</th>
							  <th>Last Name</th>
							  <th>Email Address</th>
							  <th>Telephone</th>
							</tr>
						  </thead>
						  <tbody>
                           <tr>
							   <td style="white-space:nowrap;"><input type="text" id="edit_first_name" name="edit_first_name" value="<?=$info[0]['first_name'];?>" class="brdr"></td>
                               <td><input type="text" id="edit_last_name" name="edit_last_name" value="<?=$info[0]['last_name'];?>" class="brdr"></td>
                               <td><input type="text" id="edit_email_address" name="edit_email_address" value="<?=$info[0]['email_address'];?>" class="brdr"></td>
                               <td><input type="text" id="edit_telephone" name="edit_telephone" value="<?=$info[0]['telephone'];?>" class="brdr"></td>
                           </tr>
							  <tr>
							  <th>User Name</th>
							  <th>Password</th>
							  <th>Destruct Date</th>
							  <th></th>
							</tr>
							  <tr>
							   <td style="white-space:nowrap;"><input type="text" id="edit_user_name" name="edit_user_name" value="<?=$info[0]['user_name'];?>" class="brdr"></td>
							   <td><input type="text" id="edit_password" name="edit_password" value="<?=$info[0]['password'];?>" class="brdr"></td>
                               <td><input type="text" id="edit_destruct_date" name="edit_destruct_date" title="Destruct Date" value="<?=$info[0]['destruct_date'];?>"  class="brdr"></td>
                               <td><p><a href="delete.php?id=<?=$user_id;?>&tbl=tbl_users" class="d-none d-sm-inline-block btn btn-sm shadow-sm deleteUser">Delete</a></p></td>
                               <td></td>
                           </tr>
						   <tr>
							   <td colspan="4" align="center" style="white-space:nowrap;"><input type="submit" value="Save" class="d-sm-inline-block btn shadow" style="width:200px;"></td>
                           </tr>

						  </tbody>
						</table>
</form>


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

    $(document).ready(function() {
		
		$('#edit_destruct_date').datepicker({  format: "yyyy-mm-dd" , todayHighlight: true });

	});

</script>