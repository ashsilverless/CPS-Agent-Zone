<?PHP
include '../inc/db.php';     # $host  -  $user  -  $pass  -  $db


$agent_id = $_GET['agent_id'];
$agent_status = $_GET['agent_status'];

$conn = new PDO("mysql:host=$host;dbname=$db", $user, $pass);

    $name = $_SESSION['name'];

    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

		$sql = "UPDATE tbl_agents SET bl_live = $agent_status, modified_by = '$name', modified_date = '$str_date' WHERE id = $agent_id;";


	debug($sql);

    $conn->exec($sql);

$conn = null;

die('success');
?>