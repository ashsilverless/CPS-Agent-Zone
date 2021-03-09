<?PHP
include '../inc/db.php';     # $host  -  $user  -  $pass  -  $db
//ini_set ("display_errors", "1"); 
# Property Details       $prop_id     ->    tbl_properties        

#       `id` `prop_id` `region_id` `prop_title`  `prop_desc`  `banner_image` `camp_layout`  `classic_factors`  `transfer_terms`  `included`  `excluded`  `access_details`  `children`     
#       `check_in`  `check_out`  `checkinout_restrictions`  `cancellation_terms`  `general_terms`  `capacity`  `facilities`  `activities`  `best_for`


$region_id = onlyNum($_POST['region_id']);


#######################     Get the Destinations String   ##########################
$destdata = db_query("SELECT * FROM `tbl_destinations`  ORDER BY dest_id ASC;");

$key = array_search($region_id, array_column($destdata, 'dest_id'));
$parent = $destdata[$key]['parent_id'];
$superparent = $destdata[$key]['super_parent_id'];
$name = $destdata[$key]['dest_name'];
$destcount = 0;
$parent_str = ','.$parent.',';
$namestr = $name;

while ($superparent != 0 || $destcount > 15) :

    $key = array_search($parent, array_column($destdata, 'dest_id'));
    $parent = $destdata[$key]['parent_id'];
    $superparent = $destdata[$key]['super_parent_id'];
    $name = $destdata[$key]['dest_name'];

    $parent_str .= $parent.',';
    $namestr .= ' - '.$name;
    $destcount ++;


endwhile;

####################################################################################






$prop_title = sanSlash($_POST['prop_title']);
$rr_id = sanSlash($_POST['rr_id']);
$rr_link_id = sanSlash($_POST['rr_link_id']);
$pe_id = sanSlash($_POST['pe_id']);
$prop_desc = summerstrip($_POST['prop_desc']);
$itinerary_text = summerstrip($_POST['itinerary_text']);
$prop_banner = sanSlash($_POST['prop_banner']);
$camp_layout = sanSlash($_POST['camp_layout']);
$classic_factors = summerstrip($_POST['classic_factors']);
$transfer_terms = summerstrip($_POST['transferterms']);
$prop_id = onlyNum($_POST['prop_id']);
$bl_live = onlyNum($_POST['bl_live']);
$country_id = onlyNum($_POST['country_id']);

$prop_capacity = summerstrip($_POST['capacity']);
$prop_lat = sanSlash($_POST['prop_lat']);
$prop_long =  sanSlash($_POST['prop_long']);
$propimagesIDs = explode("|",substr($_POST['propimagesIDs'], 0, -1));
$propimagesCount = count($propimagesIDs);

$docMeta = explode("|",substr($_POST['meta_data_name'], 0, -1));
$docCount = count($docMeta);

$ratesName = explode("|",substr($_POST['ratesIDs'], 0, -1));
$ratesTITLE = explode("|",substr($_POST['ratesTITLEs'], 0, -1));
$ratesCount = count($ratesName);



$included = summerstrip($_POST['included']);
$excluded = summerstrip($_POST['excluded']);
$access_details = summerstrip($_POST['access_details']);
$children = summerstrip($_POST['children']);
$cancellation_terms = summerstrip($_POST['cancellation_terms']);
$check_in = sanSlash($_POST['check_in']);
$check_out = sanSlash($_POST['check_out']);
$checkinout_restrictions = ($_POST['checkinout_restrictions']);
$general_terms = summerstrip($_POST['general_terms']);

$com_con = summerstrip($_POST['com_con']);
$closure_details = summerstrip($_POST['closure_details']);


$bestfor = getFields('tbl_bestfor','bl_live','1','=');
$facilities = getFields('tbl_facilities','bl_live','1','=');
$activities = getFields('tbl_activities','bl_live','1','=');
$travellers = getFields('tbl_travellers','bl_live','1','=');
$experiences = getFields('tbl_experiences','bl_live','1','=');


 $conn = new PDO("mysql:host=$host;dbname=$db", $user, $pass);

    $name = $_SESSION['name'];

    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);   



###########################################################################################

		 $prop_sql = "UPDATE `tbl_properties` SET `prop_title` = '$prop_title',`pe_id` = '$pe_id',`rr_id` = '$rr_id',`rr_link_id` = '$rr_link_id',`prop_desc` = :propdesc, `itinerary_text` = :itindesc, `prop_banner` = '$prop_banner', `camp_layout` = '$camp_layout', `classic_factors`=:cfact,`country_id` = '$country_id',`region_id` = '$region_id',`prop_lat` = '$prop_lat',`prop_long` = '$prop_long',`transfer_terms` = :tterms, `included` = :inc,`excluded` = :exc,`access_details` = :access,`children` = :child,`cancellation_terms` = :cancelterms,`check_in` = '$check_in',`check_out` = '$check_out',`checkinout_restrictions` = :crestrict,`general_terms` = :terms, `capacity` = :cap, `modified_by` = '$name',`modified_date`='$str_date', `bl_live`='$bl_live',`destination_str` = '$parent_str',`closure_details` = :closure,`com_con` = :com_con WHERE (`id`='$prop_id')";

		$b=$conn->prepare($prop_sql);

		$b->bindParam(":propdesc",$prop_desc);	$b->bindParam(":cfact",$classic_factors);		
        $b->bindParam(":tterms",$transfer_terms);
		$b->bindParam(":inc",$included);		$b->bindParam(":exc",$excluded);			$b->bindParam(":access",$access_details);
		$b->bindParam(":child",$children);		$b->bindParam(":crestrict",$checkinout_restrictions);		
        $b->bindParam(":terms",$general_terms); $b->bindParam(":cap",$prop_capacity);
		$b->bindParam(":cancelterms",$cancellation_terms);    $b->bindParam(":itindesc",$itinerary_text);
        $b->bindParam(":com_con",$com_con);    $b->bindParam(":closure",$closure_details);



		$b->execute();

############################################################################################


    

$experiences_sql = '';

$b=$conn->prepare("DELETE FROM `tbl_prop_exp` WHERE (`prop_pe_id`='$pe_id')");
$b->execute();

for($f=0;$f<count($experiences);$f++){
    $thisID = $experiences[$f]['id'];
    if($_POST['experience'.$thisID] != ''){

        $ttitle = $experiences[$f]['experience_title'];
    
        $experiences_sql = "INSERT INTO `tbl_prop_exp` (`prop_id`,`prop_pe_id`, `exp_id`, `exp_name`, `modified_by`, `modified_date`) VALUES ('$prop_id','$pe_id', '$thisID', :tname, '$name', '$str_date')";
            
        $b=$conn->prepare($experiences_sql);
        
        $b->bindParam(":tname",$ttitle);

		$b->execute();
    }
}


$facilities_sql = '';

$b=$conn->prepare("DELETE FROM `tbl_prop_facilities` WHERE (`prop_pe_id`='$pe_id')");
$b->execute();

for($f=0;$f<count($facilities);$f++){
    $thisID = $facilities[$f]['id'];
    
    if($_POST['facilities'.$thisID] != ''){

        $ttitle = $facilities[$f]['facility_title'];
        $main = $facilities[$f]['main_area'];
        $inroom = $facilities[$f]['in_room'];
                
        $facilities_sql = "INSERT INTO `tbl_prop_facilities` (`prop_id`,`prop_pe_id`, `facility_id`, `facility_name`, `main_area`, `in_room`, `modified_by`, `modified_date`) VALUES ('$prop_id','$pe_id', '$thisID', :tname, '$main', '$inroom', '$name', '$str_date')";
            
        $b=$conn->prepare($facilities_sql);
        
        $b->bindParam(":tname",$ttitle);
        
		$b->execute();
    }
}



$activities_sql = '';

$b=$conn->prepare("DELETE FROM `tbl_prop_activities` WHERE (`prop_pe_id`='$pe_id')");
$b->execute();

for($a=0;$a<count($activities);$a++){
    $thisID = $activities[$a]['id'];
    if($_POST['activities'.$thisID] != ''){

        $ttitle = $activities[$a]['activity_title'];
                
        $activities_sql = "INSERT INTO `tbl_prop_activities` (`prop_id`,`prop_pe_id`, `activity_id`, `activity_name`, `modified_by`, `modified_date`) VALUES ('$prop_id','$pe_id', '$thisID', :tname, '$name', '$str_date')";
            
        $b=$conn->prepare($activities_sql);
        
        $b->bindParam(":tname",$ttitle);
        
        $b->execute();
    }
}



$travellers_sql = '';

$b=$conn->prepare("DELETE FROM `tbl_prop_travellers` WHERE (`prop_pe_id`='$pe_id')");
$b->execute();

for($f=0;$f<count($travellers);$f++){
    $thisID = $travellers[$f]['id'];
    if($_POST['traveller'.$thisID] != ''){
        
        $ttitle = $travellers[$f]['traveller_title'];

                
        $travellers_sql = "INSERT INTO `tbl_prop_travellers` (`prop_id`,`prop_pe_id`, `traveller_id`, `traveller_name`, `modified_by`, `modified_date`) VALUES ('$prop_id','$pe_id', '$thisID', :tname, '$name', '$str_date')";
            
        $b=$conn->prepare($travellers_sql);
        
        $b->bindParam(":tname",$ttitle);

		$b->execute();
        
    }
}


$bestfor_sql = '';

$b=$conn->prepare("DELETE FROM `tbl_prop_bestfor` WHERE (`prop_pe_id`='$pe_id')");
$b->execute();

for($bf=0;$bf<count($bestfor);$bf++){
    $thisID = $bestfor[$bf]['id'];
    if($_POST['bestfor'.$thisID] != ''){
        $ttitle = $bestfor[$bf]['bestfor_title'];
      
        $bestfor_sql = "INSERT INTO `tbl_prop_bestfor` (`prop_id`,`prop_pe_id`, `bestfor_id`, `bestfor_name`, `modified_by`, `modified_date`) VALUES ('$prop_id','$pe_id', '$thisID', :tname, '$name', '$str_date')";
            
        $b=$conn->prepare($bestfor_sql);
        
        $b->bindParam(":tname",$ttitle);

		$b->execute();
    }
}


//  GalleryImages  //

for($s=0;$s<$propimagesCount;$s++){
    $image_loc = $propimagesIDs[$s];
    $main = str_replace('thumbs/','',$image_loc);
      $countconn = new PDO("mysql:host=$host; dbname=$db", $user, $pass);
	  $countconn->exec("SET CHARACTER SET $charset");      // Sets encoding UTF-8

	  $countresult = $countconn->prepare("SELECT * FROM tbl_gallery WHERE asset_type LIKE 'property' AND asset_id = $prop_id AND image_loc_low = '$image_loc' AND bl_live = 1 ;"); 
	  $countresult->execute();
      $count = $countresult->rowCount();
      
	  $countconn = null;        // Disconnect
    
        if($count==0 && $main!=''){
           $sql = "INSERT INTO `tbl_gallery` (`asset_type`,`asset_id`,`country_id`,`region_id`, `image_loc`, `image_loc_low`, `image_alt`, `created_by`, `created_date`, `modified_by`, `modified_date`) VALUES ('property','$prop_id','$country_id','$region_id', '$main', '$image_loc', 'alt', '$name', '$str_date', '$name', '$str_date')";

            $conn->exec($sql); 
        }
}

//  Document Meta-Data  //

for($md=0;$md<count($docMeta);$md++){
        $metaData = $docMeta[$md];
        if($metaData != ''){
             $sql = "INSERT INTO `tbl_metadata_docs` (`parent_id`, `data_type`, `data_title`, `data_loc`, `bl_live`,  `created_by`, `created_date`, `modified_by`, `modified_date`) VALUES ('$prop_id', 'document', '', '$metaData','$bl_live','$name','$str_date','$name','$str_date')";

            $conn->exec($sql);
            
            $sql2 = "UPDATE `tbl_assets` SET property_id = '$prop_id', country_id = '$country_id',`region_id` = '$region_id',`asset_cat` = '10' WHERE asset_loc LIKE '$metaData';";

            $conn->exec($sql2);
        }

    }






//  Rates Documents  //

for($r=0;$r<$ratesCount;$r++){
        $rName = $ratesName[$r];
		$rTitle = $ratesTITLE[$r];
        if($rName != ''){
            
              $countconn = new PDO("mysql:host=$host; dbname=$db", $user, $pass);
              $countconn->exec("SET CHARACTER SET $charset");      // Sets encoding UTF-8

              $countresult = $countconn->prepare("SELECT * FROM tbl_rates_docs WHERE property_id = $prop_id AND asset_loc LIKE '$rName' ;"); 
              $countresult->execute();
              $count = $countresult->rowCount();

              $countconn = null;        // Disconnect

			  if($count==0){
                  $asset_attributes = pathinfo($rName, PATHINFO_EXTENSION) . ' - ' . formatBytes(filesize($rName));
			
			      $sql = "INSERT INTO `tbl_rates_docs` (`asset_title`, `asset_attributes`, `property_id`, `asset_loc`, `created_by`, `created_date`, `modified_by`, `modified_date`) VALUES ('$rTitle', '$asset_attributes', '$prop_id', '$rName', '$name', '$str_date', '$name', '$str_date')";
                  
                  $conn->exec($sql);
              }
	
		}

    }



$conn = null;

header("location:edit_property.php?id=".$prop_id);

?>