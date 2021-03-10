<?PHP
include 'inc/db.php';     # $host  -  $user  -  $pass  -  $db
require_once('pdf_gen/config/lang/eng.php');
require_once('pdf_gen/tcpdf.php');
ini_set ("display_errors", "1");
/*      
	
	error_reporting(E_ALL);
     */



$assetAr = explode(',',$_POST['selectedDocs']);
$company_id = $_SESSION['company_id'];

$pack_title = sps($_POST['packtitle']);
$pack_description = sps($_POST['packdescription']);

$pack_description = str_replace("\n","<br>",$pack_description);

// Connect and create the PDO object
$conn = new PDO("mysql:host=$host; dbname=$db", $user, $pass);
$conn->exec("SET CHARACTER SET $charset");      // Sets encoding UTF-8

$sql = "SELECT * FROM `tbl_companies` WHERE id = $company_id AND bl_live = 1;";

  $result = $conn->prepare($sql); 
  $result->execute();

  $q_string = '';
  // Parse returned data
  while($row = $result->fetch(PDO::FETCH_ASSOC)) {
      $company_logo = '../'. $row['company_logo'];
  }

$company_logo = "../companies//safari_x.jpg";

// #############################     Create Cover Page     ############################# //

// create new PDF document
$pdf = new TCPDF(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);

// set document information
$pdf->SetCreator(PDF_CREATOR);
$pdf->SetAuthor('C&P');
$pdf->SetTitle('Document Pack');
$pdf->SetSubject('Document Pack');

// set default header data
$pdf->SetHeaderData(PDF_HEADER_LOGO, PDF_HEADER_LOGO_WIDTH, PDF_HEADER_TITLE.' ', PDF_HEADER_STRING);

// set header and footer fonts
$pdf->setHeaderFont(Array(PDF_FONT_NAME_MAIN, '', PDF_FONT_SIZE_MAIN));
$pdf->setFooterFont(Array(PDF_FONT_NAME_DATA, '', PDF_FONT_SIZE_DATA));

// set default monospaced font
$pdf->SetDefaultMonospacedFont(PDF_FONT_MONOSPACED);

//set margins
$pdf->SetMargins(PDF_MARGIN_LEFT, PDF_MARGIN_TOP, PDF_MARGIN_RIGHT);
$pdf->SetHeaderMargin(PDF_MARGIN_HEADER);
$pdf->SetFooterMargin(PDF_MARGIN_FOOTER);

//set auto page breaks
$pdf->SetAutoPageBreak(TRUE, PDF_MARGIN_BOTTOM);

//set image scale factor
$pdf->setImageScale(PDF_IMAGE_SCALE_RATIO);

//set some language-dependent strings
$pdf->setLanguageArray($l);





// Instanciation of inherited class
// set font
$pdf->SetFont('helvetica', '', 9);

// remove headers and footers
$pdf->SetPrintHeader(false);
$pdf->SetPrintFooter(false);
// add a page
$pdf->AddPage();

$style = array(
	'border' => true,
	'vpadding' => 'auto',
	'hpadding' => 'auto',
	'fgcolor' => array(0,0,0),
	'bgcolor' => false, //array(255,255,255)
	'module_width' => 1, // width of a single module in points
	'module_height' => 1 // height of a single module in points
);

$html = '<table width="100%" border="0" cellspacing="0" cellpadding="8"><tbody> <tr><td  width="100%" align="center"><img src="'.$company_logo.'" alt="" width="300"/></td></tr><tr><td width="100%" align="center"><h1 style="font-size:24px">'.$pack_title.'</h1></td></tr><tr><td width="100%" align="center"><h3>'.date('l F jS Y', strtotime($str_date)).'</h3></td></tr><tr><td width="100%" align="center"><h4>created by '.$_SESSION['name'].'</h4></td></tr><tr><td  width="100%" align="center"><hr></td></tr><tr><td  width="100%" align="left"><p>'.$pack_description.'</p></td></tr><tr><td  width="100%" align="center"><hr></td></tr> </tbody> </table>';

// output the HTML content
$pdf->writeHTML($html, true, 0, true, 0);

// reset pointer to the last page
$pdf->lastPage();

// ---------------------------------------------------------

//Close and output PDF document
$covername = 'c'.time().'.pdf';
ob_clean();
$pdf->Output(__DIR__ . '/docpacks/' . $covername, 'F');


// #############################     Cover Page End     ############################# //

$fileArray = array("docpacks/".$covername);

$return = '<div class="doc-pack-summary"><p>&bull; Cover Page</p>';

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


/*
$zip = new ZipArchive;
$zipname = str_replace(" ","_",$pack_title).".zip";

if ($zip->open($zipname, ZipArchive::CREATE) === TRUE)
{
    // Add files to the zip file
    $zip->addFile('test.txt');
    foreach($fileArray as $file) {
        $zip->addFile($file);
    }

    // All files are added, so close the zip file.
    $zip->close();
}

echo ($return.'<a href="download.php?file='.$zip.'"><p class="button button__inline createpack mt1"><i class="fas fa-download"></i> Download: '.$zipname.'</p></a>');
*/


?>