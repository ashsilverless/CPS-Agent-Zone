<?PHP
include 'inc/db.php';     # $host  -  $user  -  $pass  -  $db


$user_id = $_POST['user_id'];
$originalpassword = sanSlash($_POST['password']);
$confirmpassword = sanSlash($_POST['confirmpassword']);
$hashToStoreInDb = password_hash($confirmpassword, PASSWORD_DEFAULT);
$dbhash = getField('tbl_agents','password_hash','id',$user_id);

$conn = new PDO("mysql:host=$host;dbname=$db", $user, $pass);

    $name = $_SESSION['name'];

    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

	if($confirmpassword!=""){
		if(password_verify($originalpassword,$dbhash)){

			$sql = "UPDATE tbl_agents SET password_hash = '$hashToStoreInDb', modified_by = '$real_name', modified_date = '$str_date' WHERE id = $user_id;";
			
			$msg='<p style="color:green;">Details Saved</p>';

    		$conn->exec($sql);
		}else{
			$msg='<p style="color:red;">Incorrect Current Password !</p>';
		}
	}



$conn = null;

die($msg);
?>