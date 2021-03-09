<?PHP
include 'inc/db.php';     # $host  -  $user  -  $pass  -  $db
//ini_set ("display_errors", "1");
$user_id = $_SESSION['user_id'];

$conn = new PDO("mysql:host=$host;dbname=$db", $user, $pass);
$conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

$s_data = $_GET['s_data'];  $return = '<div class="col-12">';

$data = db_query("SELECT * FROM `tbl_flight_services`  where name LIKE '%$s_data%';");


    foreach ($data as $srch):

        $supplier_name = getField('tbl_air_suppliers','air_sup_name','pe_id',$srch['supplier_id']);
        $supplier_code = getField('tbl_air_suppliers','air_sup_code','pe_id',$srch['supplier_id']);
        
            $return .= '
                              <div class="row">
                                <div class="col-3"><p class="supsrch" data-id="'.$srch['pe_id'].'">'.$supplier_name.' '.$supplier_code.'</p></div>
                                <div class="col-6"><p>'.$srch['name'].'</p></div>
                                <div class="col-3"><p>'.$srch['departure_time'].' '.$srch['rate_plan'].'</p></div>
                              </div>';

    endforeach;

$return .= '</div>';

echo $return;
?>	