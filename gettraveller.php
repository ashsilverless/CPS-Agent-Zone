<?PHP
include '../inc/db.php';     # $host  -  $user  -  $pass  -  $db

$traveller_id = $_GET['id'];


$data = getFields('tbl_travellers','id',$traveller_id,'=');       #  getField($tbl,$fld,$srch,$param)    "SELECT * FROM $tbl WHERE $srch = '$param';"


die('{"jsonrpc" : "2.0", "travellericon" : "'.$data[0]['traveller_icon'].'", "travellertitle" : "'.$data[0]['traveller_title'].'"}');
?>
