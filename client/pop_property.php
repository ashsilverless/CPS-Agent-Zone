<?PHP
include 'inc/db.php';     # $host  -  $user  -  $pass  -  $db

$regionID = $_POST['region_id'];

$prop_sql = "SELECT * FROM `tbl_properties` WHERE bl_live = '1' AND region_id = '".$regionID."' AND rr_link_id > '0' ORDER BY prop_title ASC;";

$properties = db_query($prop_sql);

$return = '';
foreach ($properties as $property){
    
    $return .= '{"p_id" : "'.$property['pe_id'].'", "p_name": "'.$property['prop_title'].'"},';

}

$return = substr($return, 0, -1);

die('['.$return.']');
?>
