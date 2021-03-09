<?PHP
include '../inc/db.php';     # $host  -  $user  -  $pass  -  $db
ini_set ("display_errors", "1");
#	error_reporting(E_ALL);
#   `tbl_itineraries`
#                      `id` `itinerary_title` `itinerary_desc` `itinerary_banner` `classic_factors` `best_for` `properties_inc` `duration` `arrival_airport` `cancellation_terms` `general_terms`

#                    tbl_itinerary_prop_dates` 
#                      `id` `itinerary_id` `prop_id` `date_from` `date_to` 

$itinerary_title = sanSlash($_POST['itinerary_title']);
$itinerary_desc = summerstrip($_POST['itinerary_desc']);
$special_interest = summerstrip($_POST['special_interest']);
$itinerary_banner = sanSlash($_POST['itinerary_banner']);
$classic_factors = summerstrip($_POST['classic_factors']);
$itinerary_id = onlyNum($_POST['itinerary_id']);
$arrival_airport = onlyNum($_POST['airport']);
$bl_live = onlyNum($_POST['bl_live']);

$itineraryIDs = explode("|",substr($_POST['itineraryIDs'], 0, -1));
$itineraryCount = count($itineraryIDs);

$docMeta = explode("|",substr($_POST['meta_data_name'], 0, -1));
$docCount = count($docMeta);

$cancellation_terms = summerstrip($_POST['cancellation_terms']);
$general_terms = summerstrip($_POST['general_terms']);

$rate1 = onlyNum($_POST['rate1']);
$rate2 = onlyNum($_POST['rate2']);
$rate3 = onlyNum($_POST['rate3']);
$currency = sanSlash($_POST['currency']);

$bestfor = getFields('tbl_bestfor','id','0','>');


$bestfor_sql = '';
for($b=0;$b<count($bestfor);$b++){
    $thisID = $bestfor[$b]['id'];
    if($_POST['bestfor'.$thisID] != ''){
        $bestfor_sql .= '|'.$thisID.'|';
    }
}

$countryAr = getFields('tbl_destinations','bl_live','1','=');

$country_sql = '';
for($b=0;$b<count($countryAr);$b++){
    $thisID = $countryAr[$b]['dest_id'];
    if($_POST['country'.$thisID] != ''){
        $country_sql .= '|'.$thisID.'|';
    }
}

debug($country_sql);

$travellerAr = getFields('tbl_travellers','id','0','>');

$traveller_sql = '';
for($b=0;$b<count($travellerAr);$b++){
    $thisID = $travellerAr[$b]['id'];
    if($_POST['travellers'.$thisID] != ''){
        $traveller_sql .= '|'.$thisID.'|';
    }
}

$experienceAr = getFields('tbl_experiences','id','0','>');

$experience_sql = '';
for($b=0;$b<count($experienceAr);$b++){
    $thisID = $experienceAr[$b]['id'];
    if($_POST['experiences'.$thisID] != ''){
        $experience_sql .= '|'.(int)$thisID.'|';
    }
}



$max = 0;
$p_dates = getFields('tbl_itinerary_prop_dates','itinerary_id',$itinerary_id); 
for($pn=0;$pn<count($p_dates);$pn++){
    if($p_dates[$pn]['day_to']>$max){ $max=$p_dates[$pn]['day_to']; };
} 

$conn = new PDO("mysql:host=$host;dbname=$db", $user, $pass);

    $name = $_SESSION['name'];

    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

		$sql = "UPDATE `tbl_itineraries` SET `itinerary_desc`=:idesc, `special_interest`=:speci, `classic_factors`=:cfactors, `itinerary_title` = '$itinerary_title', `itinerary_banner` = '$itinerary_banner', `cancellation_terms` = :cterms, `general_terms` = :gterms, `itinerary_countries` = '$country_sql',`experiences` = '$experience_sql',`travellers` = '$traveller_sql', `best_for` = '$bestfor_sql', `rate1` = '$rate1', `rate2` = '$rate2', `rate3` = '$rate3', `currency` = '$currency', `arrival_airport` = '$arrival_airport', `duration` = '$max', `modified_by` = '$name',`modified_date`='$str_date',`bl_live`='$bl_live' WHERE (`id`='$itinerary_id')";

		$b=$conn->prepare($sql);
		$b->bindParam(":idesc",$itinerary_desc);	$b->bindParam(":speci",$special_interest);	$b->bindParam(":cfactors",$classic_factors);
		$b->bindParam(":cterms",$cancellation_terms);	$b->bindParam(":gterms",$general_terms);
		$b->execute();


//  GalleryImages  //                                    

for($s=0;$s<$itineraryCount;$s++){
    $image_loc = $itineraryIDs[$s];
    $main = str_replace('thumbs/','',$image_loc);
      $countconn = new PDO("mysql:host=$host; dbname=$db", $user, $pass);
	  $countconn->exec("SET CHARACTER SET $charset");      // Sets encoding UTF-8

	  $countresult = $countconn->prepare("SELECT * FROM tbl_gallery WHERE asset_type LIKE 'itinerary' AND asset_id = $itinerary_id AND image_loc_low = '$image_loc' AND bl_live = 1 ;"); 
	  $countresult->execute();
      $count = $countresult->rowCount();
      
	  $countconn = null;        // Disconnect
    
        if($count==0 && $main!=''){
           $sql = "INSERT INTO `tbl_gallery` (`asset_type`,`asset_id`, `image_loc`, `image_loc_low`, `image_alt`, `created_by`, `created_date`, `modified_by`, `modified_date`) VALUES ('itinerary','$itinerary_id', '$main', '$image_loc', 'alt', '$name', '$str_date', '$name', '$str_date')";

            $conn->exec($sql); 
        }
}
/**/
//  Document Meta-Data  //

for($md=0;$md<count($docMeta);$md++){
        $metaData = $docMeta[$md];
        if($metaData != ''){
             $sql = "INSERT INTO `tbl_itinerary_docs` (`itinerary_id`, `data_loc`, `bl_live`,  `created_by`, `created_date`, `modified_by`, `modified_date`) VALUES ('$itinerary_id', '$metaData','$bl_live','$name','$str_date','$name','$str_date')";

            $conn->exec($sql);
        }

    }



$conn = null;

header("location:itineraries.php");


?>