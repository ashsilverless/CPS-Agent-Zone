<?PHP
include '../inc/db.php';     # $host  -  $user  -  $pass  -  $db
$company_id = $_GET['id'];
if($_GET['n']!='1'){
	$info = getFields('tbl_company','id',$company_id);
	$users = getFields('tbl_agents','company_id',$company_id);
}else{
	$company_id = 0;
}
?>
<?php $templateName = 'company';?>
<?php require_once('_header-admin.php'); ?>
<style>
	.company-table__body, .company-table__head,.company-table__body, .company-table__head, .agents-table__body, .agents-table__head,.agents-table__body, .agents-table__head {
		display: -ms-grid;
		display: grid;
		-ms-grid-columns: 1fr 3fr 1fr 1fr 1fr;
		grid-template-columns: 1fr 3fr 1fr 1fr 1fr;
	}
	.company-table__head,.agents-table__head{
		font-weight:bold;
	}
	.color-panel-wrapper, .color-picker{
		width:120px; min-height:30px;
	}
</style>
<script type="text/javascript" src="js/plupload/plupload.full.min.js"></script>

<form action="editcompany.php" method="POST"> 
            <div class="col-md-9 mb-2" style="border-bottom:1px solid #AAA;">
              <a href="companies.php" class="d-none d-sm-inline-block btn btn-sm shadow-sm">Back to Company List</a>

              <!-- Company -->
                <div class="clearfix"></div>

				<div class="col-md-12 mt-2"><p><strong>Company Name</strong> : <input type="text" id="company_name" name="company_name" value="<?= $info[0]['company_name'];?>"></p></div>
				
				<div class="col-md-6 mt-2"><strong>Address</strong><br><textarea name="address" id="address" style="width:90%; height:200px;"><?= $info[0]['address'];?></textarea></div>
				
				<div class="col-md-6 mt-2"><p><strong>Telephone</strong> : <input type="text" id="telephone" name="telephone" value="<?= $info[0]['telephone'];?>"></p>
					<p><strong>Mobile</strong> : <input type="text" id="mobile" name="mobile" value="<?= $info[0]['mobile'];?>"></p>
				<p><strong>Fax</strong> : <input type="text" id="fax" name="fax" value="<?= $info[0]['fax'];?>"></p></div>
				
				
                <div class="col-md-12"><strong>Description</strong><br><textarea name="company_desc" id="company_desc" style="width:90%; height:220px;"><?= $info[0]['company_desc'];?></textarea></div>
				
				
				
				<div class="col-md-6 mt-2"><p><strong>Company Logo</strong></p>
				
					<p class="companylogo"><img src="<?=$info[0]['company_logo'];?>" width="175" alt="Logo"/></p><div id="containerLOGO" style="float:left;"><a id="pickfilesLOGO" href="javascript:;" class="d-sm-inline-block btn btn-sm shadow-sm">[Add Logo]</a></div><input type="hidden" id="company_logo" name="company_logo" value="<?= $info[0]['company_logo'];?>">
				
				</div>
				
				<div class="col-md-6 mt-2"><p><strong>Primary Colour</strong> : <div class="color-panel-wrapper">
                <input type="color" data-colour-id="<?=$info[0]['id'];?>" class="color-picker" name="primary_color" value="<?=$info[0]['primary_colour'];?>"></div></p>
					<p><strong>Seconday Colour</strong> : <div class="color-panel-wrapper">
                <input type="color" data-colour-id="<?=$info[0]['id'];?>" class="color-picker" name="secondary_color" value="<?=$info[0]['seconday_colour'];?>"></div></p>
				<p><strong>Tertiary Colour</strong> : <div class="color-panel-wrapper">
                <input type="color" data-colour-id="<?=$info[0]['id'];?>" class="color-picker" name="tertiary_color" value="<?=$info[0]['tertiary_colour'];?>"></div></p></div>
				
				</div>
				
				<div class="col-md-3">
					<?php   $info[0]['created_by'] != '' ? $created_by = $info[0]['created_by'] : $created_by = '&nbsp;';
							$info[0]['created_date'] != '' ? $created_date = date('jS M Y',strtotime($info[0]['created_date'])) : $created_date = '&nbsp;';
							$info[0]['modified_by'] != '' ? $modified_by = $info[0]['modified_by'] : $modified_by = '&nbsp;';
							$info[0]['modified_date'] != '' ? $modified_date = date('jS M Y',strtotime($info[0]['modified_date'])) : $modified_date = '&nbsp;';
					?>
					<div class="col-md-12 mb-3 brdr">
						<input type="hidden" id="company_id" name="company_id" value="<?=$company_id;?>">
						<div class="col-md-6 mb-2"><input type="submit" value="Save" class="d-sm-inline-block btn btn-sm shadow-sm"></div><div class="col-md-6 mb-2"><a href="delete.php?id=<?=$company_id;?>&tbl=tbl_company&d=1&loc=companies.php" class="d-none d-sm-inline-block btn btn-sm shadow-sm">Delete</a></div>
						<div class="clearfix"></div>
						
						 <div class="col-md-5 mb-1 smaller"><b>Status:</b></div><div class="col-md-7 mb-1 smaller"><b><select name="bl_live" id="bl_live"><option value="1" <?php if($info[0]['bl_live']=='1'){?>selected="selected"<?php }?>>Live</option><option value="2" <?php if($info[0]['bl_live']=='2' || $info[0]['bl_live']==''){?>selected="selected"<?php }?>>Pending</option></select></b></div>
						<div class="clearfix"></div>
						<div class="col-md-5 mb-1 smaller"><b>Created by:</b></div><div class="col-md-7 mb-1 smaller"><b><?=$created_by;?></b></div>
						<div class="clearfix"></div>
						<div class="col-md-5 mb-1 smaller"><b>Created on:</b></div><div class="col-md-7 mb-1 smaller"><b><?=$created_date;?></b></div><div class="clearfix"></div>
						<div class="col-md-5 mb-1 smaller"><b>Edited by:</b></div><div class="col-md-7 mb-1 smaller"><b><?=$modified_by?></b></div><div class="clearfix"></div>
						<div class="col-md-5 mb-1 smaller"><b>Edited on:</b></div><div class="col-md-7 mb-1 smaller"><b><?=$modified_date;?></b></div>
					</div>

            	</div>

				<div class="col-md-12 mt-4">
					<h2>Company Users</h2>
					<p><a href="#" class="d-none d-sm-inline-block btn btn-sm shadow-sm addUser">Add User</a></p>
					
					<div class="agentstable mt-2 mb-2">
						<input type="hidden" id="user_count" name="user_count" class="user_count" value="1">
						<table class="table brdr" id="newusers" width="100%" cellspacing="0">
						  <thead>
							<tr>
							  <th>Real Name</th>
							  <th>Username</th>
							  <th>Email Address</th>
							  <th>Telephone</th>
							  <th>Password</th>
							</tr>
						  </thead>
						  <tbody>
                           <tr>
							   <td style="white-space:nowrap;"><input type="text" id="real_name1" name="real_name1" value="" class="brdr"></td>
                               <td><input type="text" id="user_name1" name="user_name1" value="" class="brdr"></td>
                               <td><input type="text" id="email_address1" name="email_address1" value="" class="brdr"></td>
                               <td><input type="text" id="contact_telephone1" name="contact_telephone1" value="" class="brdr"></td>
                               <td><input type="text" id="password1" name="password1" value="" class="brdr"></td>
                           </tr>

						  </tbody>
						</table>
						<p><a href="#" class="d-none d-sm-inline-block btn btn-sm shadow-sm addAnotherUser">Add AnotherUser</a></p>
					</div>
					
                 <div class="company-table mt-3 mb-3">
                    
                    <div class="company-table__head">
                        <label>Real Name</label>
                        <label>Email Address</label>
						<label>Last Login</label>
                        <label>Status</label>
                        <label></label>
                    </div><!--head-->
                   <div id="blank">
                     <?php foreach ($users as $item):
					   if($item['bl_live']=='1'){
						    $status = '<strong>Live</strong>'; $u_checked = 'checked';
					   }else{
						   $status = '<em>Pending</em>'; $u_checked = '';
					   }

					   ?>
                            <div class="company-table__body company">
                                <p><?=$item['real_name'];?></p>
                                <p><?=$item['email_address'];?></p>
								<p><?=($item['last_logged_in']== Null) ? "Never" : date('j M y',strtotime($item['last_logged_in']));?></p>
								<p style="font-size:0.8em;"><input class="usercheck" type="checkbox" <?=$u_checked;?>  data-width="95" data-height="20" data-toggle="toggle" data-on="Live" data-off="Pending" data-onstyle="success" data-offstyle="danger" value="<?=$item['id'];?>"></p>
                                <p><a href="delete.php?id=<?=$item['id'];?>&tbl=tbl_agents" class="d-none d-sm-inline-block btn btn-sm shadow-sm editFlight">Delete</a></p>
                            </div><!--body-->
                    <?php endforeach; ?>
                   </div>
                </div>
                
              </div>

            </div>



            
</form>
<?php require_once('_footer-admin.php'); ?>

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
		
		$('.agentstable').hide();
		
		var usercount = 1;
		
		$(document).on('click', '.addUser', function(e) {
            e.preventDefault();
			$('.agentstable').toggle('slow');
		});
		
		$(document).on('click', '.addAnotherUser', function(e) {
            e.preventDefault();
			usercount ++;
			$("#newusers").append('<tr><td style="white-space:nowrap;"><input type="text" id="real_name'+usercount+'" name="real_name'+usercount+'" value="" class="brdr"></td><td><input type="text" id="user_name'+usercount+'" name="user_name'+usercount+'" value="" class="brdr"></td><td><input type="text" id="email_address'+usercount+'" name="email_address'+usercount+'" value="" class="brdr"></td><td><input type="text" id="contact_telephone'+usercount+'" name="contact_telephone'+usercount+'" value="" class="brdr"></td><td><input type="text" id="password'+usercount+'" name="password'+usercount+'" value="" class="brdr"></td></tr>');
			
			$('.user_count').val(usercount);
		});

		
		 $('.usercheck').change(function() {
		     var chkd = $(this).prop('checked');
			 var chkdval = $(this).val();
			
			 chkd == true ? bllive = 1 : bllive = 2;
			 
			 $.ajax({
                type: "GET",
                url: 'update_agent.php',
                data: {agent_id: chkdval, agent_status: bllive},
                success: function(response)
                {
					// nothing really to do.
               }
           });
		})
  
		
        // Logo Upload
        var uploader = new plupload.Uploader({
            runtimes : 'html5,flash,silverlight,html4',
            browse_button : 'pickfilesLOGO',
            container: document.getElementById('containerLOGO'),
             url : 'upload.php?tbl=companies',
            flash_swf_url : 'js/plupload/Moxie.swf',
            silverlight_xap_url : '.js/plupload/Moxie.xap',
            unique_names : true,
            filters : {
                max_file_size : '10mb',
                mime_types: [
                    {title : "All files", extensions : "*"}
                ]
            },

            init: {

                FilesAdded: function(up, files) { uploader.start(); },


                FileUploaded: function(up, file, info) {
                    var myData;
                    try {  myData = eval(info.response);  } catch(err) {  myData = eval('(' + info.response + ')');  }
					
					$( "#company_logo" ).val(myData.result);
                    $(".companylogo").html('<img src="'+myData.result+'" alt="Logo" style="width:175px;"/>');
                },


                Error: function(up, err) { document.getElementById('console').appendChild(document.createTextNode("\nError #" + err.code + ": " + err.message)); }
            }
        });


        uploader.init();


});

</script>
</body>

</html>
