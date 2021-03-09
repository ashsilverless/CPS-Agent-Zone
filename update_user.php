<?PHP
include '../inc/db.php';     # $host  -  $user  -  $pass  -  $db


$user_id = $_GET['user_id'];
$user_status = $_GET['user_status'];
$admin_status = $_GET['admin_status'];

if($admin_status != ""){
	$admin_status == 4 ? $user_type = 'superadmin' : $user_type = 'admin';
}

$conn = new PDO("mysql:host=$host;dbname=$db", $user, $pass);

    $name = $_SESSION['name'];

    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

	$user_status != '' ? $sql = "UPDATE tbl_users SET bl_live = $user_status, modified_by = '$name', modified_date = '$str_date' WHERE id = $user_id;" : $sql = "UPDATE tbl_users SET agent_level = $admin_status, user_type = '$user_type', modified_by = '$name', modified_date = '$str_date' WHERE id = $user_id;";


	debug($sql);

    $conn->exec($sql);

$conn = null;

die('success');
?>