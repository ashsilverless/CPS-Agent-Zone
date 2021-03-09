<?PHP
include 'inc/db.php';     # $host  -  $user  -  $pass  -  $db

$supplier_id = $_POST['s_id'];   $return = '';

$data = db_query("SELECT * FROM `tbl_supplier_routes` where supplier_id = '$supplier_id' ORDER BY id ASC;");


    foreach ($data as $supplier):
        
        //$return .= '['.$supplier['from_coords'].','.$supplier['to_coords'].']';

        $return .= '{"f_long" : '.$supplier['f_long'].', "f_lat": '.$supplier['f_lat'].',"t_long" : '.$supplier['t_long'].', "t_lat": '.$supplier['t_lat'].', "f_id": '.$supplier['airport_from'].', "t_id": '.$supplier['airport_to'].'},';

endforeach;

$return = substr($return, 0, -1);


die('['.$return.']');
?>	