<?PHP
include '../inc/db.php';     # $host  -  $user  -  $pass  -  $db

$flight_id = onlyNum($_POST['flight_id']);
$bl_live = onlyNum($_POST['bl_live']);
$schedule_name = sanSlash($_POST['schedule_name']);
$intro_text = summerstrip($_POST['intro_text']);
$schedule_doc = sanSlash($_POST['schedule_doc']);

$filetpe = pathinfo($schedule_doc, PATHINFO_EXTENSION);
$filesize = formatBytes(filesize($schedule_doc));

$asset_attributes = $filetpe . ' - ' . $filesize;


$conn = new PDO("mysql:host=$host;dbname=$db", $user, $pass);

	$name = $_SESSION['name'];

	$conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

	if($flight_id == ''){
		//$sql = "UPDATE `tbl_flight_schedules` SET `bl_live`='2' WHERE (`id`>'0')";
		//$conn->exec($sql);
		
		$sql = "INSERT INTO tbl_flight_schedules (`schedule_name`, `intro_text`, `schedule_doc`,`asset_attributes`, `bl_live`,  `created_by`, `created_date`, `modified_by`, `modified_date`) VALUES('$schedule_name',:intro,'$schedule_doc','$asset_attributes','$bl_live','$name','$str_date','$name','$str_date')";
		
		$b=$conn->prepare($sql);
		$b->bindParam(":intro",$intro_text);
		$b->execute();

		$flight_id = $conn->lastInsertId();
		
	}else{
		
	/*	if($bl_live==1){
			$sql = "UPDATE `tbl_flight_schedules` SET `bl_live`='2' WHERE (`id`!='$flight_id')";
			$conn->exec($sql);
		}*/
		
		$sql = "UPDATE `tbl_flight_schedules` SET `schedule_name` = '$schedule_name',`intro_text` = :intro, `schedule_doc`='$schedule_doc', `modified_by` = '$name',`modified_date`='$str_date',`asset_attributes`='$asset_attributes',`bl_live`='$bl_live' WHERE (`id`='$flight_id')";
		
		$b=$conn->prepare($sql);
		$b->bindParam(":intro",$intro_text);
		$b->execute();
		
	}

$conn = null;

header("location:flights.php");
?>