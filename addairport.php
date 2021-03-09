<?PHP
include '../inc/db.php';     # $host  -  $user  -  $pass  -  $db

$airport_id = $_POST['airport_id'];
$region_id = onlyNum($_POST['region_id']);
$airport_name = ($_POST['airport_name']);
$airport_code = ($_POST['airport_code']);
$lat = ($_POST['lat']);
$long = ($_POST['long']);

$destdata = array_flatten(getFields('tbl_destinations','dest_id',$region_id,'=','dest_name ASC'));

$country_id = $destdata['super_parent_id'];

$conn = new PDO("mysql:host=$host;dbname=$db", $user, $pass);

    $name = $_SESSION['name'];

    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    
	if($airport_id != ''){
		$sql = "UPDATE `tbl_airports` SET `region_id`='$region_id',`country_id`='$country_id', `airport_name`=:an, `airport_code`=:ac, `lat`=:lt, `long`=:lng,`modified_by`='$name', `modified_date` = '$str_date' WHERE (`id`='$airport_id')";
	}else{
		$sql = "INSERT INTO tbl_airports (`airport_name`, `airport_code`, `region_id`, `country_id`, `lat`, `long`,`created_by`, `created_date`, `modified_by`, `modified_date`) VALUES(:an,:ac,'$region_id','$country_id',:lt,:lng,'$name','$str_date','$name','$str_date')";
	}

    $b=$conn->prepare($sql);
	$b->bindParam(":an",$airport_name);	$b->bindParam(":lt",$lat);	$b->bindParam(":lng",$long);

    $b->bindParam(":ac",$airport_code);

	$b->execute();

$conn = null;



header("location:airports.php");
?>