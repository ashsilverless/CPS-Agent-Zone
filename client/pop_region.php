<?PHP
include 'inc/db.php';     # $host  -  $user  -  $pass  -  $db

$countryID = $_POST['countryID'];
$data = '<option value="" disabled selected>Select</option>';
$regions = getFields('tbl_regions','country_id',$countryID,'=');     #   $tbl,$srch,$param,$condition

foreach ($regions as $region){

	$region_id = $region['id'];
	$region_name = $region['region_name'];
	 
	$data .= '<option value="'.$region['id'].'">'.$region['region_name'].'</option>';
	
}

echo($data);
?>
