<?PHP
include 'inc/db.php';     # $host  -  $user  -  $pass  -  $db
$asset_id = $_GET['id'];
$d=$_GET['d'];


if($d == 'md'){
	$asset_id != '' ? $data = getFields('tbl_metadata_docs','id',$asset_id,'=') :  $data[] = '';    #   $tbl,$srch,$param,$condition
	$fld = 'data_loc';
}else{
	$asset_id != '' ? $data = getFields('tbl_assets','id',$asset_id,'=') :  $data[] = '';    #   $tbl,$srch,$param,$condition
	$fld = 'asset_loc';
}

    // Get parameters
    $file = urldecode('../'.$data[0][$fld]); // Decode URL-encoded string

    // Process download
    if(file_exists($file)) {
        header('Content-Description: File Transfer');
        header('Content-Type: application/octet-stream');
        header('Content-Disposition: attachment; filename="'.basename($file).'"');
        header('Expires: 0');
        header('Cache-Control: must-revalidate');
        header('Pragma: public');
        header('Content-Length: ' . filesize($file));
        flush(); // Flush system output buffer
        readfile($file);
        exit;
    }

?>