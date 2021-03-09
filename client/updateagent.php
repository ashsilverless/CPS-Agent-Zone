<?PHP
include 'inc/db.php';     # $host  -  $user  -  $pass  -  $db


$user_id = $_POST['user_id'];
$real_name = sanSlash($_POST['real_name']);
$user_name = sanSlash($_POST['user_name']);
$email_address = sanSlash($_POST['email_address']);
$contact_telephone = sanSlash($_POST['contact_telephone']);



$conn = new PDO("mysql:host=$host;dbname=$db", $user, $pass);

    $name = $_SESSION['name'];

    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

	$sql = "UPDATE tbl_agents SET real_name = '$real_name', user_name = '$user_name', email_address = '$email_address', contact_telephone = '$contact_telephone', modified_by = '$real_name', modified_date = '$str_date' WHERE id = $user_id;";

debug($sql);

    $conn->exec($sql);

$conn = null;

die('success');
?>