<?PHP
include '../inc/db.php';     # $host  -  $user  -  $pass  -  $db

$exid = $_GET['id'];

$eximages = db_query("select * from tbl_gallery where asset_type LIKE 'experience' AND asset_id = '$exid' AND bl_live = 1; ");

for($ci=0;$ci<count($eximages);$ci++){
    echo ('<div class="col-md-4 mb-1"><a href="delete.php?id='.$eximages[$ci]['id'].'&tbl=tbl_gallery" title="Delete" data-toggle="popover" data-trigger="hover" data-html="true" data-content="<b>Click to delete this image !</b>"><img src="'.$eximages[$ci]['image_loc_low'].'" alt="Gallery Image" style="width:90%;"/></a></div>');
}
?>