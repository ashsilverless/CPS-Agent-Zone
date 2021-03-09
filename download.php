<?PHP
include '../inc/db.php';     # $host  -  $user  -  $pass  -  $db
$asset_id = $_GET['id'];
$asset_id != '' ? $data = getFields('tbl_assets','id',$asset_id,'=') :  $data[] = '';    #   $tbl,$srch,$param,$condition



    // Get parameters
    $file = urldecode($data[0]['asset_loc']); // Decode URL-encoded string

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