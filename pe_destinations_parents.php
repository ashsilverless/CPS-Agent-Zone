<?PHP
include '../inc/db.php';     # $host  -  $user  -  $pass  -  $db


ini_set ("display_errors", "1");

$did = $_GET['d'];

$time_start = microtime(true); 

##################################################################################################	

$data = db_query("SELECT * FROM `tbl_destinations`  ORDER BY dest_id ASC;");




$key = array_search($did, array_column($data, 'dest_id'));
$parent = $data[$key]['parent_id'];
$superparent = $data[$key]['super_parent_id'];
$name = $data[$key]['dest_name'];
$count = 0;
$parent_str = $parent;
$namestr = $name;

echo ('<p>'.$namestr.'</p>');

while ($superparent != 0 || $count > 15) :

    $key = array_search($parent, array_column($data, 'dest_id'));
    $parent = $data[$key]['parent_id'];
    $superparent = $data[$key]['super_parent_id'];
    $name = $data[$key]['dest_name'];

    $parent_str .= ','.$parent;
    $namestr .= ' - '.$name;
    $count ++;

    echo ('<p>'.$namestr.'</p>');

endwhile;

echo ('<p>'.$parent_str.'</p>');

	#########################################################################################################	
	/*                             -----------    End Of Pink Elephant Prices    -----------               */
	#########################################################################################################

$time_end = microtime(true);
$execution_time = ($time_end - $time_start);

echo ('Finished : '.$execution_time);
?>