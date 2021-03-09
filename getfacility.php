<?PHP
include '../inc/db.php';     # $host  -  $user  -  $pass  -  $db

$facility_id = $_GET['id'];


$data = getFields('tbl_facilities','id',$facility_id,'=');       #  getField($tbl,$fld,$srch,$param)    "SELECT * FROM $tbl WHERE $srch = '$param';"


die('{"jsonrpc" : "2.0", "facilityicon" : "'.$data[0]['facility_icon'].'", "facilitytitle" : "'.$data[0]['facility_title'].'", "in_room" : "'.$data[0]['in_room'].'", "main_area" : "'.$data[0]['main_area'].'"}');
?>
