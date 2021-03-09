<?PHP
include '../inc/db.php';     # $host  -  $user  -  $pass  -  $db

$asset_id = sanSlash($_POST['asset_id']);
$asset_title = sanSlash($_POST['asset_title']);
$asset_loc = sanSlash($_POST['asset_loc']);
$asset_type = sanSlash($_POST['asset_type']);
$asset_cat = sanSlash($_POST['asset_cat']);
$property_id = onlyNum($_POST['property_id']);
$country_id = onlyNum($_POST['country_id']);
$region_id = onlyNum($_POST['region_id']);
$asset_tags = sanSlash($_POST['asset_tags']);
$bl_live =  onlyNum($_POST['bl_live']);


$filetpe = pathinfo($asset_loc, PATHINFO_EXTENSION);
$filesize = formatBytes(filesize($asset_loc));

$asset_attributes = $filetpe . ' - ' . $filesize;


if($property_id!=0){
	$country_id = getField('tbl_properties','country_id','id',$property_id);
	$region_id = getField('tbl_properties','region_id','id',$property_id);
}





$conn = new PDO("mysql:host=$host;dbname=$db", $user, $pass);

    $name = $_SESSION['name'];

    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    if($asset_id!=''){
        $sql = "UPDATE `tbl_assets` SET `asset_title`='$asset_title', `asset_tags`='$asset_tags', `asset_loc`='$asset_loc', `asset_type`='$asset_type', `asset_cat`='$asset_cat', `asset_attributes`='$asset_attributes',`property_id`='$property_id',`country_id`='$country_id',`region_id`='$region_id', `modified_by` = '$name',`modified_date`='$str_date',`bl_live`='$bl_live' WHERE (`id`='$asset_id')";
    }else{
        $sql = "INSERT INTO `tbl_assets` (`asset_title`, `asset_tags`, `asset_type`, `asset_cat`, `asset_attributes`, `property_id`, `country_id`, `region_id`, `asset_loc`, `bl_live`, `created_by`, `created_date`, `modified_by`, `modified_date`) VALUES ('$asset_title','$asset_tags', '$asset_type', '$asset_cat', '$asset_attributes', '$property_id', '$country_id', '$region_id', '$asset_loc', '$bl_live', '$name', '$str_date', '$name', '$str_date')";
    }
        
        $conn->exec($sql);

$conn = null;

header("location:assets.php");

?>