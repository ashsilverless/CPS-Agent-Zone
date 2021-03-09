<?PHP
include '../inc/db.php';     # $host  -  $user  -  $pass  -  $db

$destruct_date = date('Y-m-d',strtotime('+1 year',strtotime($str_date)));

if($_GET['rpp']!=""){
	$_SESSION["rpp"] = $_GET['rpp'];
}

if($_GET['page']!=""){
	$page=$_GET['page'];
}

if($page==""){
	$page = 0;
}

$recordsPerPage = $_SESSION["rpp"];

if($recordsPerPage==""){
	$recordsPerPage = 8;
}

try {
	// Connect and create the PDO object
	$conn = new PDO("mysql:host=$host; dbname=$db", $user, $pass);
	$conn->exec("SET CHARACTER SET $charset");      // Sets encoding UTF-8
	$result = $conn->prepare("SELECT * FROM tbl_users WHERE bl_live > 0 ORDER BY email_address ASC ");
	$result->execute();

	// Parse returned data
	while($row = $result->fetch(PDO::FETCH_ASSOC)) {
		$rows[] = $row;
	}

	$num_rows = count($rows);

	$totalPageNumber = ceil($num_rows / $recordsPerPage);
	$offset = $page*$recordsPerPage;

	$query = "SELECT * FROM tbl_users WHERE bl_live > 0 ORDER BY email_address ASC LIMIT $offset,$recordsPerPage;";

	$result = $conn->prepare($query);
	$result->execute();

	while($row = $result->fetch(PDO::FETCH_ASSOC)) {
		$info[] = $row;
	}

	$conn = null;        // Disconnect

}
catch(PDOException $e) {
  echo $e->getMessage();
}

$rspaging = '<div style="margin:auto; padding:15px 0 15px 0; text-align: center; font-size:16px; font-family: \'Ubuntu\',sans-serif;"><strong>'.$num_rows.'</strong> results in <strong>'.$totalPageNumber.'</strong> pages.&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Page : ';

if($page<3){
	$start=1;
	$end=7;
}else{
	$start=$page-2;
	$end=$page+4;
}


if($end >= $totalPageNumber){
  $endnotifier = "";
  $end = $totalPageNumber;
}else{
  $endnotifier = "...";
}

$frst = '<a href="?page=0" style="font-size:13px; margin:5px; padding:5px; font-weight:bold;">|&laquo;</a>';
$last = '<a href="?page='.($totalPageNumber-1).'" style="font-size:13px; margin:5px; padding:5px; font-weight:bold;">&raquo;|</a>';

$rspaging .=  $frst;
for($a=$start;$a<=$end;$a++){
	$a-1 == $page ? $lnk='<strong style="font-size:13px; border: solid 1px #BBB; margin:5px; padding:5px;">'.$a.'</strong>' : $lnk='<a href="?page='.($a-1).'" style="font-size:13px; margin:5px; padding:5px;">'.$a.'</a>';
	$rspaging .=  $lnk;
}

$ipp = '<span style="margin-left:35px;">Show <a href="?rpp=8">8</a>&nbsp;|&nbsp;<a href="?rpp=16">16</a>&nbsp;|&nbsp;<a href="?rpp=24">24</a>&nbsp;|&nbsp;<a href="?rpp=999"><strong>All</strong></a></span>';

$rspaging .= $endnotifier.$last.$ipp.'</div>';
?>

<?php $templateName = 'Administration Users';?>
<?php require_once('_header-admin.php'); ?>
<style>
	.user-table__body, .user-table__head,.user-table__body, .user-table__head, .topform {
		display: -ms-grid;
		display: grid;
		-ms-grid-columns: 2fr 2fr 1fr 1fr 1fr 1fr;
		grid-template-columns: 2fr 2fr 1fr 1fr 1fr 1fr;
	}
	.user-table__head{
		font-weight:bold;
	}
</style>
<script type="text/javascript" src="js/plupload/plupload.full.min.js"></script>
            <div class="col-md-12 mb-3" style="border-bottom:1px solid #AAA;">
				<h2><a href="#" class="d-none d-sm-inline-block btn btn-sm shadow-sm addUser">Add User</a></h2>
				<div class="userstable mt-2 mb-2">
				<form action="adduser.php" method="POST">
					<table class="table" id="newuadmin" width="100%" cellspacing="0">
						  <thead>
							<tr>
							  <th>First Name</th>
							  <th>Last Name</th>
							  <th>Email Address</th>
							  <th>User Type</th>
							</tr>
						  </thead>
						  <tbody>
                           <tr>
							   <td style="white-space:nowrap;"><input type="text" id="first_name" name="first_name" value="" class="brdr"></td>
                               <td><input type="text" id="last_name" name="last_name" value="" class="brdr"></td>
                               <td><input type="text" id="email_address" name="email_address" value="" class="brdr"></td>
                               <td><input id="user_type" name="user_type" type="checkbox" class="usertype btn btn-small" value="4" data-width="120" data-height="20" data-toggle="toggle" data-on="Super Admin" data-off="Admin" data-onstyle="danger" data-offstyle="success"></td>
                           </tr>
							  <tr>
							  <th>User Name</th>
							  <th>Password</th>
							  <th>Telephone</th>
							  <th>Destruct Date</th>
							</tr>
							  <tr>
							   <td style="white-space:nowrap;"><input type="text" id="user_name" name="user_name" value="" class="brdr"></td>
							   <td><input type="text" id="password" name="password" value="" class="brdr"></td>
                               <td><input type="text" id="telephone" name="telephone" value="" class="brdr"></td>
                               <td><input type="text" id="destruct_date" name="destruct_date" title="Destruct Date" value="<?=$destruct_date;?>"  class="brdr"></td>
                               <td></td>
                               <td></td>
                           </tr>
						   <tr>
							   <td colspan="4" align="center" style="white-space:nowrap;"><input type="submit" value="Save" class="d-sm-inline-block btn shadow" style="width:200px;"></td>
                           </tr>

						  </tbody>
						</table>
                   </div><!--account table-->
				</form>
				</div>

				<div class="usersedittable mt-2 mb-2">
				
				</form>
				</div>

				<div class="clearfix"></div>
				<div class="list-admin" style="display:block;">
					
					<div class="user-table mt-5">
							<h2>Admin Users</h2>
                            <div class="user-table__head">
								<label>Name / Username</label>
                                <label>Email Address</label>
								<label>Destruct Date</label>
                                <label>User Type</label>
                                <label>Status</label>
								<label></label>
                            </div><!--head-->
                           <div id="blank">
							 <?php foreach ($info as $item):
							    $item['bl_live']=='1' ? $u_checked = 'checked' : $u_checked = '';
							    $item['agent_level'] <4 ? $utype = '' : $utype = 'checked';
							   ?>
									<div class="user-table__body company">
										<p><?=$item['first_name'].' '.$item['last_name'];?>&emsp;/&emsp;<?=$item['user_name'];?></p>
										<p><?=$item['email_address'];?></p>
										<p><?=date('D M j Y', strtotime($item['destruct_date']));?></p>
										<p><input class="usertype btn btn-small" type="checkbox" <?=$utype;?> data-size="mini" data-width="120" data-height="20" data-toggle="toggle" data-on="Super Admin" data-off="Admin" data-onstyle="danger" data-offstyle="success" value="<?=$item['id'];?>"></p>
										<p><input class="usercheck btn btn-small" type="checkbox" <?=$u_checked;?> data-size="mini" data-width="95" data-height="20" data-toggle="toggle" data-on="Live" data-off="Pending" data-onstyle="success" data-offstyle="danger" value="<?=$item['id'];?>"></p>
										<p><a href="<?=$item['id'];?>" class="d-none d-sm-inline-block btn btn-sm shadow-sm editUser">Edit</a></p>
									</div><!--body-->
    			            <?php endforeach; ?>
						   </div>
                        </div><!--account table-->
						<?=$rspaging;?>
				</div>
				
			</div>	


<?php require_once('_footer-admin.php'); ?>
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/datepicker/0.6.5/datepicker.min.css">
<script src="https://cdnjs.cloudflare.com/ajax/libs/datepicker/0.6.5/datepicker.min.js"></script>
<script type="text/javascript">

    function getParameterByName(name, url) {
			if (!url) url = window.location.href;
			name = name.replace(/[\[\]]/g, "\\$&");
			var regex = new RegExp("[?&]" + name + "(=([^&#]*)|&|#|$)"),
				results = regex.exec(url);
			if (!results) return null;
			if (!results[2]) return '';
			return decodeURIComponent(results[2].replace(/\+/g, " "));
		}

    $(document).ready(function() {
		
		$('#destruct_date').datepicker({  format: "yyyy-mm-dd" , todayHighlight: true });

		$('.userstable').hide();	$('.usersedittable').hide();
		
		$(document).on('click', '.addUser', function(e) {
            e.preventDefault();
			$('.userstable').toggle('slow');
		});
		
		$(document).on('click', '.editUser', function(e) {
            e.preventDefault();
			var uid = $(this).attr('href');
			$(".usersedittable").load("edit_user.php?id="+uid);
			$('.usersedittable').toggle('slow');
		});
		
		$('.usercheck').change(function() {
		     var chkd = $(this).prop('checked');
			 var chkdval = $(this).val();
			
			 chkd == true ? bllive = 1 : bllive = 2;
			 
			 $.ajax({
                type: "GET",
                url: 'update_user.php',
                data: {user_id: chkdval, user_status: bllive},
                success: function(response)
                {
					// nothing really to do.
               }
           });
		});
		
		$('.usertype').change(function() {
		     var chkd = $(this).prop('checked');
			 var chkdval = $(this).val();
			
			 chkd == true ? admin = 4 : admin = 2;
			 
			 $.ajax({
                type: "GET",
                url: 'update_user.php',
                data: {user_id: chkdval, admin_status: admin},
                success: function(response)
                {
					// nothing really to do.
               }
           });
		});

	});

</script>
</body>

</html>
