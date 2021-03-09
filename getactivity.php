<?PHP
include '../inc/db.php';     # $host  -  $user  -  $pass  -  $db

$activity_id = $_GET['id'];


$data = getFields('tbl_activities','id',$activity_id,'=');       #  getField($tbl,$fld,$srch,$param)    "SELECT * FROM $tbl WHERE $srch = '$param';"


die('{"jsonrpc" : "2.0", "activityicon" : "'.$data[0]['activity_icon'].'", "activitytitle" : "'.$data[0]['activity_title'].'"}');
?>
