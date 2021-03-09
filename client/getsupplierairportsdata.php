<?PHP
include 'inc/db.php';     # $host  -  $user  -  $pass  -  $db

$supplier_id = $_POST['s_id'];
$departure_id = $_POST['d_id'];

$return = '';

#$data = db_query("SELECT fs.departure_id, ap.long, ap.lat FROM tbl_flight_services fs INNER JOIN tbl_airports ap ON fs.arrival_id = ap.id WHERE fs.supplier_id = $supplier_id AND fs.departure_id > 1 AND fs.arrival_id > 1 group by fs.arrival_id order by fs.departure_id ASC");
$dept = getFields('tbl_airports','id',$departure_id,'=');
$d_long = $dept[0]['long'];   $d_lat = $dept[0]['lat'];


$data = db_query("SELECT ap.id, ap.long, ap.lat FROM tbl_flight_services fs INNER JOIN tbl_airports ap ON fs.arrival_id = ap.id WHERE fs.supplier_id = $supplier_id AND fs.departure_id = $departure_id AND fs.arrival_id > 1 group by fs.arrival_id order by fs.arrival_id ASC");
    
    
foreach ($data as $service):

    $along = $service['long'];      $alat = $service['lat'];

    #$return .= '['.$d_long.','.$d_lat.'],['.$along.','.$alat.'],';

   # $return .= '{"dlong" : "'.$d_long.'", "dlat": "'.$d_lat.'", "along": "'.$along.'", "alat": "'.$alat.'"},';

     $return .= '{"dlong" : '.$d_long.', "dlat": '.$d_lat.', "along": '.$along.', "alat": '.$alat.'},';

endforeach;

$return = substr($return, 0, -1);

#die('{'.$return.'}');
 die('['.$return.']');

?>