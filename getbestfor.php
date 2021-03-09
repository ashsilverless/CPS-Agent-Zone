<?PHP
include '../inc/db.php';     # $host  -  $user  -  $pass  -  $db

$bestfor_id = $_GET['id'];


$data = getFields('tbl_bestfor','id',$bestfor_id,'=');       #  getField($tbl,$fld,$srch,$param)    "SELECT * FROM $tbl WHERE $srch = '$param';"


die('{"jsonrpc" : "2.0", "bestforicon" : "'.$data[0]['bestfor_icon'].'", "bestfortitle" : "'.$data[0]['bestfor_title'].'"}');
?>
