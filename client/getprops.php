<?PHP
include 'inc/db.php';     # $host  -  $user  -  $pass  -  $db

$c_id = $_POST['c_id'];

$data = getFields('tbl_properties','country_id',$c_id);

$return = '';
foreach ($data as $props){
	
	$return .= '{"camps" : "'.$props['prop_title'].'", "id": "'.$props['id'].'"},';
	
	
}
$return = substr($return, 0, -1);

die('['.$return.']');
?>
