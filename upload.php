<?php
include '../inc/db.php';     # $host  -  $user  -  $pass  -  $db

header("Expires: Mon, 26 Jul 1997 05:00:00 GMT");
header("Last-Modified: " . gmdate("D, d M Y H:i:s") . " GMT");
header("Cache-Control: no-store, no-cache, must-revalidate");
header("Cache-Control: post-check=0, pre-check=0", false);
header("Pragma: no-cache");

require_once('php_image_magician.php');


function createImageCrop($orig,$pathToImage,$width,$height){
    
	$magicianObj = new imageLib($pathToImage."/".$orig);
	$magicianObj -> resizeImage($width, $height, 'crop');
	$magicianObj -> saveImage($pathToImage."/thumbs/".$orig, 80);
	
	
	return ($dest);
}

function IconResize($orig,$pathToImage){

	  // *** Open image
	  $magicianObj = new imageLib($pathToImage."/".$orig);
	
	
	
	  $magicianObj -> resizeImage(32, 32, 'auto');
	  $magicianObj -> saveImage($pathToImage."/".$orig);

	return ($orig);
}

$target = $_GET['tbl'];
$sub = $_GET['sub'];
$type = $_GET['type'];
$ignore = $_GET['ignore'];

$targetDir = $target;


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

        if($sub!=''){ 
            $newImageLo = createImageCrop($fileName,$target,350,350);
        };
    
		if($type=='icon'){
            $filetpe = pathinfo($filePath, PATHINFO_EXTENSION);
            if(strtolower($filetpe)!='svg'){
                $newImageLo = IconResize($fileName,$target);
            }
		}
    
        $filesize = formatBytes(filesize($filePath));
	
        /*         */
	if($type!='icon'){
		$my_t=getdate(date("U"));
		$str_date=$my_t[year]."-".$my_t[mon]."-".$my_t[mday]." ".$my_t[hours].":".$my_t[minutes].":".$my_t[seconds];
		$name = $_SESSION['name'];
		
		$filetpe = pathinfo($filePath, PATHINFO_EXTENSION);
		$filesize = formatBytes(filesize($filePath));

		$asset_attributes = $filetpe . ' - ' . $filesize;
		
		if(exif_imagetype($filePath)) {
			$asset_type = 'Image';
		}else{
			$asset_type = 'Document';
		}
		
		if($ignore!=1){
			$conn = new PDO("mysql:host=$host;dbname=$db", $user, $pass);

				$conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

				$sql = "INSERT INTO `tbl_assets` (`asset_title`, `asset_type`, `asset_attributes`,`asset_loc`, `bl_live`, `created_by`, `created_date`, `modified_by`, `modified_date`) VALUES ('$fileName', '$asset_type', '$asset_attributes', '$filePath', '1', '$name', '$str_date', '$name', '$str_date')";

				$conn->exec($sql);

			$conn = null;
		}
	}

}

die('{"jsonrpc" : "2.0", "result" : "'.$filePath.'", "filename" : "'.$fileName.'", "filesize" : "'.$filesize.'"}');
?>