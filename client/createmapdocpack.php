<?PHP
include 'inc/db.php';     # $host  -  $user  -  $pass  -  $db
require_once('pdf_gen/config/lang/eng.php');
require_once('pdf_gen/tcpdf.php');
ini_set ("display_errors", "1");
/*      
	
	error_reporting(E_ALL);
     */

$assets = $_GET['assets'];

if($assets!=''){


    $assetAr = explode(',',$assets);

    $pack_title = "Map Pack";

    for($a=0;$a<count($assetAr);$a++){
          $result = $conn->prepare("SELECT * FROM tbl_assets WHERE id = ".$assetAr[$a].";");
          $result->execute();
          $count = $result->rowCount();
          while($row = $result->fetch(PDO::FETCH_ASSOC)) {
              $fileArray[] = "../".$row['asset_loc'];
              $name = $row['asset_title'];
              $attr = $row['asset_attributes'];
          }
          $return .='<p>&bull; ' . $name.' ('.$attr . ')</p>';
        }
    $conn = null;        // Disconnect


    $merge_name = str_replace(" ","_",$pack_title).".pdf";
    $datadir = "docpacks/";
    $outputName = $datadir.$merge_name;

    $cmd = "gs -q -dNOPAUSE -dBATCH -sDEVICE=pdfwrite -sOutputFile=$outputName ";
    //Add each pdf file to the end of the command
    foreach($fileArray as $file) {
        $cmd .= $file." ";
    }
    $result = shell_exec($cmd);



    echo ($return.'<a href="download.php?file='.$outputName.'"><p class="button button__inline createpack mt1"><i class="fas fa-download"></i> Download: '.$merge_name.'</p></a>');
    
}else{
    echo ('<p>No documents selected</p>');
}

?>