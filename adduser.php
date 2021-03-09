<?PHP
include '../inc/db.php';     # $host  -  $user  -  $pass  -  $db
/*
foreach($_POST as $key => $data) {
	
	echo ('$'.$key.' = sanSlash($_POST[\''.$key.'\'])<br>');

} 
*/

	
$first_name = sanSlash($_POST['first_name']);
$last_name = sanSlash($_POST['last_name']);
$email_address = sanSlash($_POST['email_address']);
$user_type = sanSlash($_POST['user_type']);


if($user_type == 4){
	$admin_type = "superadmin";
}else{
	$user_type = 2;
	$admin_type = "admin";
}

$user_name = sanSlash($_POST['user_name']);
$password = sanSlash($_POST['password']);
$telephone = sanSlash($_POST['telephone']);
$destruct_date = sanSlash($_POST['destruct_date']);

$conn = new PDO("mysql:host=$host;dbname=$db", $user, $pass);

    $name = $_SESSION['name'];

    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
	
		$hashToStoreInDb = password_hash($password, PASSWORD_DEFAULT);
		
		$sql_user = "INSERT INTO `tbl_users` (`user_type`, `first_name`, `last_name`, `user_name`, `password`, `email_address`, `agent_level`, `destruct_date`, `telephone`, `password_hash`, `bl_live`, `created_by`, `created_date`) VALUES ('$admin_type', '$first_name', '$last_name', '$user_name', '$password', '$email_address', '$user_type', '$destruct_date', '$telephone', '$hashToStoreInDb', '1', '$name', '$str_date')";


			$conn->exec($sql_user);

$conn = null;

header("location:admin_users.php");


?>