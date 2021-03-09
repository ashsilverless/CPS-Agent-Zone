<?PHP
include '../inc/db.php';     # $host  -  $user  -  $pass  -  $db
/*
foreach($_POST as $key => $data) {
	
	echo ('$'.$key.' = sanSlash($_POST[\''.$key.'\'])<br>');

} 
*/
$user_id = $_GET['id'];
	
$first_name = sanSlash($_POST['edit_first_name']);
$last_name = sanSlash($_POST['edit_last_name']);
$email_address = sanSlash($_POST['edit_email_address']);
$user_type = sanSlash($_POST['edit_user_type']);


if($user_type == 4){
	$admin_type = "superadmin";
}else{
	$user_type = 2;
	$admin_type = "admin";
}

$user_name = sanSlash($_POST['edit_user_name']);
$password = sanSlash($_POST['edit_password']);
$telephone = sanSlash($_POST['edit_telephone']);
$destruct_date = sanSlash($_POST['edit_destruct_date']);

$conn = new PDO("mysql:host=$host;dbname=$db", $user, $pass);

    $name = $_SESSION['name'];

    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
	
		$hashToStoreInDb = password_hash($password, PASSWORD_DEFAULT);
		
		$sql_user = "UPDATE `tbl_users` SET `user_type`='$admin_type', `first_name`='$first_name', `last_name`='$last_name', `user_name`='$user_name', `password`='$password', `email_address`='$email_address', `telephone`='$telephone', `agent_level`='$user_type', `destruct_date`='$destruct_date', `modified_by`='$name', `modified_date`='$str_date' WHERE (`id`='$user_id')";


			$conn->exec($sql_user);

$conn = null;

header("location:admin_users.php");


?>