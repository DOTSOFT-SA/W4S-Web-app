<?php
defined( '_VALID_PROCCESS' ) or die( 'Direct Access to this location is not allowed.' );
	include($config["physicalPath"]."/languages/".$auth->LanguageCode.".php");
//require_once(dirname(__FILE__) . "/common.php");
//if(($auth->UserRow['admin_type']=='LOCAL')) {
//	Redirect("index.php");
//}


if($auth->UserType != "Administrator" &&  $auth->UserRow['parent']!=0) Redirect("index.php");

global $nav;
$nav = "Χρήστες";
$config["navigation"] = "Χρήστες";
$item = (intval($_GET["item"]) > 0 ? intval($_GET["item"]) : "");
$BaseUrl = "index.php?com=users";
$command=array();
$command=explode("&",$_POST["Command"]);

//if( $auth->UserType == "Administrator" )
//{
	if(isset($_REQUEST["Command"]))
	{	
		if($_REQUEST["Command"] == "SAVE")
		{
			$PrimaryKeys = array();
			$Collector = array();
			$QuotFields = array();
			
			if(isset($_GET["item"]) && $_GET["item"] != "" && intval($_GET["item"])> 0)
			{
				$PrimaryKeys["user_id"] = intval($_GET["item"]);
				$QuotFields["user_id"] = true;
				
			} else {
				$Collector["date_insert"] = date('Y-m-d H:i:s');
				$QuotFields["date_insert"] = true;
			}

			$Collector["is_valid"] = isset($_POST["is_valid"]) && $_POST["is_valid"] == "on" ? "True" : "False";
			$QuotFields["is_valid"] = true;
			
			$Collector["user_fullname"] = $_POST["user_fullname"];
			$QuotFields["user_fullname"] = true;
			
			$Collector["email"] = $_POST["email"];
			$QuotFields["email"] = true;
			
			$Collector["user_auth"] = "Register";
			$QuotFields["user_auth"] = true;
			
			$Collector["user_name"] = $_POST["email"];
			$QuotFields["user_name"] = true;
			
			$Collector["user_password"] = $_POST["user_password"];
			$QuotFields["user_password"] = true;
			
			$Collector["phone"] = $_POST["phone"];
			$QuotFields["phone"] = true;
			
			$Collector["parent"] = ($auth->UserType == "Administrator"?'0':$auth->UserId);
			$QuotFields["parent"] = true;
			
			//$Collector["role_id"] =  $_POST["role_id"]; //($auth->UserType == "Administrator"?$_POST["role_id"]:$auth->UserRow['role_id']);
			$Collector["role_id"] =  ($auth->UserType == "Administrator"?'1':$_POST["role_id"]);
			$QuotFields["role_id"] = true;

			//$Collector["organization_id"] =  $_POST["organization_id"]; //($auth->UserType == "Administrator"?$_POST["role_id"]:$auth->UserRow['role_id']);
			$Collector["organization_id"] =  ($auth->UserType == "Administrator"?$_POST["organization_id"]:$auth->UserRow['organization_id']);
			$QuotFields["organization_id"] = true;
			
			if($auth->UserType != "Administrator" && $auth->UserRow['parent']=='0'){
				$Collector["sector_id"] = $_POST["sector_id"];
				$QuotFields["sector_id"] = true;
			}
			
			
			$Collector["description"] =  $_POST["description"]; 
			$QuotFields["description"] = true;
			
			$db->ExecuteUpdater("users",$PrimaryKeys,$Collector,$QuotFields);
			$messages->addMessage("SAVED!!!");
			Redirect($BaseUrl);
		} else if($_REQUEST["Command"] ==  "DELETE") { //$command[0] ==
			if($item != "")
			{
				$error=0;
				//$result = $db->sql_query('SELECT * FROM devices WHERE user_id='.$item);
				//if($db->sql_numrows($result) > 0) $error++;
				//$result = $db->sql_query('SELECT * FROM messages WHERE user_id='.$item);
				//if($db->sql_numrows($result) > 0) $error++;
				if($error==0) {	
					//$filter=($auth->UserType != "Administrator"?' AND user_id IN (SELECT user_id FROM users WHERE parent='.$auth->UserId.')':'');
					$filter="";
					$db->sql_query("DELETE FROM users WHERE user_id=" . $item.$filter);
					$messages->addMessage("DELETE!!!");
					Redirect($BaseUrl);
				} else {
					$messages->addMessage("Υπάρχουν συνδεδεμένες εγγραφές. Η διαγραφή δεν μπορεί να ολοκληρωθεί");
					Redirect($BaseUrl);
				}
			}
		}
	}
//}

if(isset($_GET["item"])) {
	//$filter=" WHERE 1=1 AND user_auth!='Administrator '";
	$filter.=($auth->UserType != "Administrator"?' AND user_id IN (SELECT user_id FROM users WHERE parent='.$auth->UserId.')':'');
	$query="SELECT * FROM users WHERE user_id=".$_GET['item'].$filter;
	$dr_e = $db->RowSelectorQuery($query);
	if (!isset($dr_e["user_id"]) && intval($_GET["item"])> 0) {
		$messages->addMessage("NOT FOUND!!!");
		Redirect("index.php?com=users");
	}

	?>
	<!-- 
    <div class="breadcrumbs">
        <ul>
            <li>
                <a href="index.php">Home</a>
                <i class="icon-angle-right"></i>
            </li>
            <li>
                <a href="<? //=$BaseUrl?>"><? //=$nav?></a>
            </li>
        </ul>
    </div>	
	-->


	
	<div class="row">
		<div class="col">
			<section class="card">
				<header class="card-header">
					<div class="card-actions">
						<a href="#" class="card-action card-action-toggle" data-card-toggle></a>
						<a href="#" class="card-action card-action-dismiss" data-card-dismiss></a>
					</div>
					<h2 class="card-title"><?=edit?></h2>
				</header>
				<div class="card-body">
					<div class="form-horizontal form-bordered" method="get">
						<div class="form-group row">
							<label class="col-lg-3 control-label text-lg-right pt-2" for="inputDefault"><?=active?></label>
							<div class="col-lg-6">
								<div class="checkbox-custom checkbox-default">
									<input type="checkbox" checked="" name="is_valid" id="is_valid" <?=((isset($dr_e["is_valid"]) && $dr_e["is_valid"]=='True') ? 'checked':'')?>>
									<label for="is_valid"></label>
								</div>
							</div>
						</div>
						<? if($auth->UserType == "Administrator"){?>
						<div class="form-group row">
							<label class="col-lg-3 control-label text-lg-right pt-2">Οργανισμός</label>
							<div class="col-lg-6">
								<select name="organization_id" id="organization_id" class="form-control mb-3">
									<option value="">Επιλογή Οργανισμού</option>
									<?
										$filter=" AND is_valid='True'";
										$resultOrganizations = $db->sql_query("SELECT * FROM organizations WHERE 1=1 ".$filter." ORDER BY organization_name ");
										while ($drOrganizations = $db->sql_fetchrow($resultOrganizations)){
											echo '<option value="'.$drOrganizations['organization_id'].'" '.($drOrganizations['organization_id']==$dr_e['organization_id']?' selected':'').'>'.$drOrganizations['organization_name'].'</option>';
										}
									?>
								</select>
							</div>
						</div>
						<? } ?>
						
						<? if($auth->UserType != "Administrator" && $auth->UserRow['parent']=='0'){ ?>
						<div class="form-group row">
							<label class="col-lg-3 control-label text-lg-right pt-2" for="sector_id">Τομέας</label>
							<div class="col-lg-6">
								<select name="sector_id" id="sector_id" class="form-control mb-3">
									<option value="0">Επιλογή</option>
									<?
										$filter=" AND is_valid='True'";
										$filter.=($auth->UserType != "Administrator"?' AND organization_id='.$auth->UserRow['organization_id']:'');
										$resultSectors = $db->sql_query("SELECT * FROM sectors WHERE 1=1 ".$filter." ORDER BY sector_name ");
										while ($drSectors = $db->sql_fetchrow($resultSectors)){
											echo '<option value="'.$drSectors['sector_id'].'" '.($drSectors['sector_id']==$dr_e['sector_id']?' selected':'').'>'.$drSectors['sector_name'].'</option>';
										}
									?>
								</select>
							</div>
						</div>	
						<? } ?>
						
						
						<? if($auth->UserType != "Administrator"){?>
						<div class="form-group row">
							<label class="col-lg-3 control-label text-lg-right pt-2">Ρόλοι</label>
							<div class="col-lg-6">
								<select name="role_id" id="role_id" class="form-control mb-3">
									<option value="">Επιλογή Ρόλου</option>
									<?
										$filter=" AND is_valid='True'";
										$resultRoles = $db->sql_query("SELECT * FROM roles WHERE 1=1 ".$filter."  ");
								
										while ($drRoles = $db->sql_fetchrow($resultRoles)){
											echo '<option value="'.$drRoles['role_id'].'" '.($drRoles['role_id']==$dr_e['role_id']?' selected':'').'>'.$drRoles['role_name'].'</option>';
										}
									?>
								</select>
							</div>
						</div>
						<? } ?>
						<div class="form-group row">
							<label class="col-lg-3 control-label text-lg-right pt-2" for="user_fullname">Ονοματεπώνυμο</label>
							<div class="col-lg-6">
								<input type="text" class="form-control" id="user_fullname" name="user_fullname" value="<?=(isset($dr_e["user_fullname"]) ? $dr_e["user_fullname"]:'')?>">
							</div>
						</div>
						<div class="form-group row">
							<label class="col-lg-3 control-label text-lg-right pt-2" for="user_fullname">email</label>
							<div class="col-lg-6">
								<input type="text" class="form-control" id="email" name="email" value="<?=(isset($dr_e["email"]) ? $dr_e["email"]:'')?>">
							</div>
						</div>
						
						<!-- 
						<div class="form-group row">
							<label class="col-lg-3 control-label text-lg-right pt-2" for="user_name">Ονομα χρήστη</label>
							<div class="col-lg-6">
								<input type="text" class="form-control" id="user_name" name="user_name" readonly value="<? //=(isset($dr_e["user_name"]) ? $dr_e["user_name"]:'')?>">
							</div>
						</div>
						-->

						
						<div class="form-group row">
							<label class="col-lg-3 control-label text-lg-right pt-2" for="user_name">Τηλέφωνο</label>
							<div class="col-lg-6">
								<input type="text" class="form-control" id="phone" name="phone" value="<?=(isset($dr_e["phone"]) ? $dr_e["phone"]:'')?>">
							</div>
						</div>
						<div class="form-group row">
							<label class="col-lg-3 control-label text-lg-right pt-2" for="inputPassword">Password</label>
							<div class="col-lg-6">
								<input type="password" class="form-control" placeholder="" id="user_password" name="user_password" value="<?=(isset($dr_e["user_password"]) ? $dr_e["user_password"] : "")?>">
							</div>
						</div>
						<div class="form-group row">
							<label class="col-lg-3 control-label text-lg-right pt-2" for="description">Περιγραφή</label>
							<div class="col-lg-6">
								<textarea class="form-control" name="description" id="description" rows="3"  data-plugin-textarea-autosize><?=$dr_e["description"]?></textarea>
							</div>
						</div>
					</div>
					<div class="row-fluid" style="margin-top:20px;">
						<a href="#" onClick="checkFields();"><button type="button" class="btn btn-primary">Αποθήκευση</button></a>
						<a href="index.php?com=users"><button type="button" class="btn btn-primary">Επιστροφή</button></a>
					</div>
				</div>
			</section>
		</div>
	</div>
	
	

	<script>
		//document.getElementById("submitBtn").disabled = true;
		function checkFields(){
		var user_fullname = $('#user_fullname').val();
		var email = $('#email').val();
		//var user_name = $('#user_name').val();
		var user_password = $('#user_password').val();
			if ( user_fullname.length >= 2 && email.length >= 3 ){ //&& user_name.length >= 5 && user_password.length >= 5
					cm('SAVE',1,0,'');//document.getElementById("submitBtn").disabled = false;
			} //else {
				//document.getElementById("submitBtn").disabled = true;
				//alert('2 chars');
			//}
		}
	</script>    
	<?
} else 	{
	?>    
    
    <?
    
	//echo 'usertype: '.$auth->UserRow['admin_type']
	
	?>
	
		<div class="row">
			<div class="col">
				<section class="card">
					<header class="card-header">
						<div class="card-actions">
							<a href="#" class="card-action card-action-toggle" data-card-toggle></a>
							<a href="#" class="card-action card-action-dismiss" data-card-dismiss></a>
						</div>
		
						<h2 class="card-title">Χρήστες</h2>
					</header>
					<div class="card-body">
						<!-- <table class="table table-bordered table-striped mb-0" id="datatable-default">-->
						<table class="table table-responsive-lg  table-bordered table-striped mb-0" id="datatable-filters">
							<thead>
								<tr>
									<th><?=active?></th>
									<?=($auth->UserType == "Administrator"?'<th>Οργανισμός</th>':'');?>
									<th>Ονοματεπώνυμο</th>
									<th>Τομέας</th>
									<th>email</th>
									<th>Τηλέφωνο</th>
									<th><?=action?></th>
								</tr>
							</thead>
							<tbody>
								<?	
									$filter=" WHERE 1=1 AND user_auth!='Administrator '";
									$filter.=($auth->UserType != "Administrator"?' AND parent='.$auth->UserId:' AND parent=0');
									$query = "SELECT * FROM users ".$filter." ORDER BY user_fullname ";
				
									$result = $db->sql_query($query);
									$counter = 0;
									while ($dr = $db->sql_fetchrow($result))
									{
										?>
											<tr>
												<td><?=$dr["is_valid"]?></td>
												<?
												if($auth->UserType == "Administrator"){
													$drOrganization=$db->RowSelectorQuery("SELECT * FROM organizations WHERE organization_id=".$dr['organization_id']);
													echo '<td>'.$drOrganization['organization_name'].'</td>';
												}
												?>

												<td><?=$dr["user_fullname"]?></td>
												<?
												
													$drSector=$db->RowSelectorQuery("SELECT * FROM sectors WHERE sector_id=".$dr['sector_id']);
													echo '<td>'.$drSector['sector_name'].'</td>';
												
												?>
												<td><?=$dr["email"]?></td>
												<td><?=$dr["phone"]?></td>
												<td>
													<a style="padding:4px"  href="index.php?com=users&Command=edit&item=<?=$dr["user_id"]?>"><i style="font-size:24px;" class="fas fa-edit"></i></a>
													<a href="#" onclick="ConfirmDelete('Επιβεβαίωση διαγραφής','index.php?com=users&Command=DELETE&item=<?=$dr["user_id"]?>');"><span><i style="font-size:24px;" class="fas fa-trash"></i> </a></span></a>
												</td>
											</tr>
										<?
									}
									$db->sql_freeresult($result);
								?>
							</tbody>
						</table>
						
						<div class="row-fluid" style="margin-top:20px;">
							<a href="index.php?com=users&item="><button type="button" class="btn btn-primary">Νέα εγγραφή</button></a>
						</div>
					</div>
				</section>
			</div>
		</div>

<p class="text-center text-muted mt-3 mb-3">Development by <a target="_blank" href="http://www.dotsoft.gr/">Dotsoft</a> &copy; Copyright 2022. All Rights Reserved.</p>
<? } ?> 

<script>
	$(document).ready(function() {
		$('#organization_id').select2();
		$('#role_id').select2();
		$('#sector_id').select2();
	});
</script>


<script>
$(document).ready(function () {
    // Setup - add a text input to each footer cell
    $('#datatable-filters thead tr')
        .clone(true)
        .addClass('filters')
        .appendTo('#datatable-filters thead');
 
    var table = $('#datatable-filters').DataTable({
		"pageLength": 50,
		 "lengthMenu": [[10, 25, 50, -1], [10, 25, 50, "All"]],
		orderCellsTop: true,
        fixedHeader: true,

        initComplete: function () {
            var api = this.api();
 
            // For each column
            api
                .columns()
                .eq(0)
                .each(function (colIdx) {
                    // Set the header cell to contain the input element
                    var cell = $('.filters th').eq(
                        $(api.column(colIdx).header()).index()
                    );
                    //var title = $(cell).text();
                    //$(cell).html('<input type="text" placeholder="' + title + '" />');
					$(cell).html('<input type="text" placeholder="" / style="width:100%;border:1px solid #aaa;">');
 
                    // On every keypress in this input
                    $(
                        'input',
                        $('.filters th').eq($(api.column(colIdx).header()).index())
                    )
                        .off('keyup change')
                        .on('change', function (e) {
                            // Get the search value
                            $(this).attr('title', $(this).val());
                            var regexr = '({search})'; //$(this).parents('th').find('select').val();
 
                            var cursorPosition = this.selectionStart;
                            // Search the column for that value
                            api
                                .column(colIdx)
                                .search(
                                    this.value != ''
                                        ? regexr.replace('{search}', '(((' + this.value + ')))')
                                        : '',
                                    this.value != '',
                                    this.value == ''
                                )
                                .draw();
                        })
                        .on('keyup', function (e) {
                            e.stopPropagation();
 
                            $(this).trigger('change');
                            $(this)
                                .focus()[0]
                                .setSelectionRange(cursorPosition, cursorPosition);
                        });
                });
        },
    });
});
</script>