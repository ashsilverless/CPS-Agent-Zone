<?php
include '../inc/db.php';     # $host  -  $user  -  $pass  -  $db
require_once "simplexlsx.class.php";


ini_set ("display_errors", "1");

header("Expires: Mon, 26 Jul 1997 05:00:00 GMT");
header("Last-Modified: " . gmdate("D, d M Y H:i:s") . " GMT");
header("Cache-Control: no-store, no-cache, must-revalidate");
header("Cache-Control: post-check=0, pre-check=0", false);
header("Pragma: no-cache");

$targetDir = 'dataupload/';
$uploader_name = $_SESSION['name'];

$cleanupTargetDir = true; // Remove old files
$maxFileAge = 5 * 3600; // Temp file age in seconds

$my_t=getdate(date("U"));
$fdate=str_replace("20","",$my_t['year']).$my_t['mon'].$my_t['mday'].$my_t['hours'].$my_t['minutes'].$my_t['seconds'];

// 5 minutes execution time
@set_time_limit(5 * 60);

// Get parameters
$chunk = isset($_REQUEST["chunk"]) ? intval($_REQUEST["chunk"]) : 0;
$chunks = isset($_REQUEST["chunks"]) ? intval($_REQUEST["chunks"]) : 0;
$fileName = isset($_REQUEST["name"]) ? $_REQUEST["name"] : '';

// Clean the fileName for security reasons
$fileName = preg_replace('/[^\w\.]+/', '_', $fileName);

// Make sure the fileName is unique but only if chunking is disabled
if ($chunks < 2 && file_exists($targetDir . DIRECTORY_SEPARATOR . $fileName)) {
	$ext = strrpos($fileName, '.');
	$fileName_a = substr($fileName, 0, $ext);
	$fileName_b = substr($fileName, $ext);

	$count = 1;
	while (file_exists($targetDir . DIRECTORY_SEPARATOR . $fileName_a . '_' . $count . $fileName_b))
		$count++;

	$fileName = $fileName_a . '_' . $count . $fileName_b;
}

$filePath = $targetDir . DIRECTORY_SEPARATOR . $fileName;

// Create target dir
if (!file_exists($targetDir))
	@mkdir($targetDir);

// Remove old temp files
if ($cleanupTargetDir) {
	if (is_dir($targetDir) && ($dir = opendir($targetDir))) {
		while (($file = readdir($dir)) !== false) {
			$tmpfilePath = $targetDir . DIRECTORY_SEPARATOR . $file;

			// Remove temp file if it is older than the max age and is not the current file
			if (preg_match('/\.part$/', $file) && (filemtime($tmpfilePath) < time() - $maxFileAge) && ($tmpfilePath != "{$filePath}.part")) {
				@unlink($tmpfilePath);
			}
		}
		closedir($dir);
	} else {
		die('{"jsonrpc" : "2.0", "error" : {"code": 100, "message": "Failed to open temp directory."}, "id" : "id"}');
	}
}

// Look for the content type header
if (isset($_SERVER["HTTP_CONTENT_TYPE"]))
	$contentType = $_SERVER["HTTP_CONTENT_TYPE"];

if (isset($_SERVER["CONTENT_TYPE"]))
	$contentType = $_SERVER["CONTENT_TYPE"];

// Handle non multipart uploads older WebKit versions didn't support multipart in HTML5
if (strpos($contentType, "multipart") !== false) {
	if (isset($_FILES['file']['tmp_name']) && is_uploaded_file($_FILES['file']['tmp_name'])) {
		// Open temp file
		$out = @fopen("{$filePath}.part", $chunk == 0 ? "wb" : "ab");
		if ($out) {
			// Read binary input stream and append it to temp file
			$in = @fopen($_FILES['file']['tmp_name'], "rb");

			if ($in) {
				while ($buff = fread($in, 4096))
					fwrite($out, $buff);
			} else
				die('{"jsonrpc" : "2.0", "error" : {"code": 101, "message": "Failed to open input stream."}, "id" : "id"}');
			@fclose($in);
			@fclose($out);
			@unlink($_FILES['file']['tmp_name']);
		} else
			die('{"jsonrpc" : "2.0", "error" : {"code": 102, "message": "Failed to open output stream."}, "id" : "id"}');
	} else
		die('{"jsonrpc" : "2.0", "error" : {"code": 103, "message": "Failed to move uploaded file."}, "id" : "id"}');
} else {
	// Open temp file
	$out = @fopen("{$filePath}.part", $chunk == 0 ? "wb" : "ab");
	if ($out) {
		// Read binary input stream and append it to temp file
		$in = @fopen("php://input", "rb");

		if ($in) {
			while ($buff = fread($in, 4096))
				fwrite($out, $buff);
		} else
			die('{"jsonrpc" : "2.0", "error" : {"code": 101, "message": "Failed to open input stream."}, "id" : "id"}');

		@fclose($in);
		@fclose($out);
	} else
		die('{"jsonrpc" : "2.0", "error" : {"code": 102, "message": "Failed to open output stream."}, "id" : "id"}');
}

// Check if file has been uploaded
if (!$chunks || $chunk == $chunks - 1) {
	// Strip the temp .part suffix off
	rename("{$filePath}.part", $filePath);

    /* ########################################    PARSE THE DATA     ############################ */

		$conn = new PDO("mysql:host=$host;dbname=$db", $user, $pass);
		$conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    
    ###########   Log the upload   #########
    
        $upload_date = date('Y-m-d H:i:s');     $uploader_name = $_SESSION['name'];     $uploader_id = $_SESSION['user_id'];
   
        $sql = "INSERT INTO `tbl_uploads` (`file_name`, `upload_date`, `uploader_name`, `uploader_id`) VALUES ('$fileName', '$upload_date', '$uploader_name', '$uploader_id');";

        $c=$conn->prepare($sql);
        $c->execute();

		if ( $xlsx = SimpleXLSX::parse("dataupload/".$fileName) ) {
			foreach ( $xlsx->rows() as $r => $arr ) {

				$head_office = $arr[0];     $head_office_code = $arr[1];     $supplier_name = $arr[2];     $supplier_code = $arr[3];
				$supplier_id = $arr[4];     $flight_no = $arr[5];     $flight_type = $arr[6];     
                $valid_from = date('Y-m-d H:i:s', strtotime($arr[7]));  $valid_to = date('Y-m-d H:i:s', strtotime($arr[8]));
				$departure_point = $arr[9];     $departure_time = date('H:i', strtotime($arr[10]));
				$arrival_point = $arr[11];  $arrival_time = date('H:i', strtotime($arr[12]));     $week_days = $arr[13];     $is_exception = $arr[14];
 
				if($r !== 0){
					$sql = "INSERT INTO `tbl_flight_management_upload` (`head_office`, `head_office_code`, `supplier_name`, `supplier_code`, `supplier_id`, `flight_no`, `flight_type`, `valid_from`, `valid_to`, `departure_point`, `departure_time`, `arrival_point`, `arrival_time`, `week_days`, `is_exception`, `upload_date`, `uploader_name`, `uploader_id`) VALUES ('$head_office', '$head_office_code', '$supplier_name', '$supplier_code', '$supplier_id', '$flight_no', '$flight_type', '$valid_from', '$valid_to', '$departure_point', '$departure_time', '$arrival_point', '$arrival_time', '$week_days', '$is_exception', '$upload_date', '$uploader_name', '$uploader_id')";

					$conn->exec($sql);
				}
                $error = 'NULL';
			}
		} else {
			$error = SimpleXLSX::parseError();
		}

		$conn = null;
	/* ########################################################################################### */

}

die('{"jsonrpc" : "2.0", "result" : "'.$fileName.'", "error" : "'.$error.'"}');
?>
