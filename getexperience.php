<?PHP
include '../inc/db.php';     # $host  -  $user  -  $pass  -  $db

$experience_id = $_GET['id'];


$data = getFields('tbl_experiences','id',$experience_id,'=');       #  getField($tbl,$fld,$srch,$param)    "SELECT * FROM $tbl WHERE $srch = '$param';"


die('{"jsonrpc" : "2.0", "experienceicon" : "'.$data[0]['experience_icon'].'", "experiencetitle" : "'.$data[0]['experience_title'].'", "experiencebody" : "'.$data[0]['experience_body'].'", "experiencebanner" : "'.$data[0]['experience_banner'].'"}');
?>
