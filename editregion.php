<?PHP
include '../inc/db.php';     # $host  -  $user  -  $pass  -  $db

$region_id = onlyNum($_POST['region_id']);
$bl_live = onlyNum($_POST['bl_live']);
$region_name = summerstrip($_POST['region_name']);
$region_desc = summerstrip($_POST['region_desc']);
$region_icon = sanSlash($_POST['region_icon']);
$region_banner = sanSlash($_POST['region_banner']);
$region_icon = sanSlash($_POST['region_icon']);
$country_id = onlyNum($_POST['country_id']);
$seasonIDs = explode("|",substr($_POST['seasonIDs'], 0, -1));
$seasonCount = count($seasonIDs);

$conn = new PDO("mysql:host=$host;dbname=$db", $user, $pass);

    $name = $_SESSION['name'];

    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
	
	$sql = "UPDATE `tbl_regions` SET `country_id` = '$country_id',`region_name` =:rn, `region_desc`=:rd, `region_icon`='$region_icon', `region_banner` = '$region_banner', `modified_by` = '$name',`modified_date`='$str_date',`bl_live`='$bl_live' WHERE (`id`='$region_id')";

	$b=$conn->prepare($sql);
	$b->bindParam(":rn",$region_name);		$b->bindParam(":rd",$region_desc);
	$b->execute();


//  Seasonal Information  //

for($s=0;$s<count($seasonIDs);$s++){
    $s_id = $seasonIDs[$s];
    $season_name = sanSlash($_POST['season_title'.$s_id]);
    $month_from = sanSlash($_POST['month_from'.$s_id]);
    $month_to = sanSlash($_POST['month_to'.$s_id]);
    $max_temp = sanSlash($_POST['max_temp'.$s_id]);
    $min_temp = sanSlash($_POST['min_temp'.$s_id]);

$sql = "UPDATE `tbl_seasons` SET `season_title` = '$season_name',`region_id` = '$region_id',`country_id` = '$country_id', `month_from`='$month_from', `month_to`='$month_to', `max_temp` = '$max_temp', `min_temp` = '$min_temp', `modified_by` = '$name',`modified_date`='$str_date' WHERE (`id`='$s_id'); ";
    
    $conn->exec($sql);

}


$conn = null;

header("location:regions.php");
?>