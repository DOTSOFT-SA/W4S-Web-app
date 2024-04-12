<?php
defined( '_VALID_PROCCESS' ) or die( 'Direct Access to this location is not allowed.' );
	include($config["physicalPath"]."/languages/".$auth->LanguageCode.".php");
	include($config["physicalPath"]."/perm.php");
//require_once(dirname(__FILE__) . "/common.php");
//if(($auth->UserRow['admin_type']=='LOCAL')) {
//	Redirect("index.php");
//}
if($auth->UserType != "Administrator") Redirect("index.php");
//if($auth->UserType != "Administrator" && !($_SESSION["permissions"] & $FLAG_300)) Redirect("index.php");
global $nav;
$nav = "Τύποι αισθητήρων";
$config["navigation"] = $nav;
$item = (intval($_GET["item"]) > 0 ? intval($_GET["item"]) : "");
$BaseUrl = "index.php?com=sensortypes";
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
				$PrimaryKeys["sensortype_id"] = intval($_GET["item"]);
				$QuotFields["sensortype_id"] = true; 
				
			} else {
				$Collector["date_insert"] = date('Y-m-d H:i:s');
				$QuotFields["date_insert"] = true;
			}

			$Collector["is_valid"] = isset($_POST["is_valid"]) && $_POST["is_valid"] == "on" ? "True" : "False";
			$QuotFields["is_valid"] = true;
			
			$Collector["sensortype_name"] = $_POST["sensortype_name"];
			$QuotFields["sensortype_name"] = true;			
			
			$Collector["min"] = $_POST["min"];
			$QuotFields["min"] = true;		
			
			$Collector["max"] = $_POST["max"];
			$QuotFields["max"] = true;		
			
			$Collector["sensortype_description"] = $_POST["sensortype_description"];
			$QuotFields["sensortype_description"] = true;	
			
			$db->ExecuteUpdater("sensortypes",$PrimaryKeys,$Collector,$QuotFields);
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
					$db->sql_query("DELETE FROM sensortypes WHERE sensortype_id=" . $item.$filter);
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
	if(intval($_GET['item'])>0){
		$filter="";
		//$filter.=($auth->UserType != "Administrator"?' AND region_id='.$auth->UserRow['region_id'] :'');
		$query="SELECT * FROM sensortypes WHERE sensortype_id=".$_GET['item'].$filter;
		$dr_e = $db->RowSelectorQuery($query);
		if (!isset($dr_e["sensortype_id"]) && intval($_GET["item"])> 0) {
			$messages->addMessage("NOT FOUND!!!");
			Redirect("index.php?com=sensortypes");
		}
	}
	?>
	
	<div class="row">
		<div class="col">
			<section class="card">
				<header class="card-header">
					<div class="card-actions">
						<a href="#" class="card-action card-action-toggle" data-card-toggle></a>
						<a href="#" class="card-action card-action-dismiss" data-card-dismiss></a>
					</div>
					<h2 class="card-title"><?=$nav?> / <?=edit?></h2>
				</header>
				<div class="card-body">
					<div class="form-horizontal form-bordered" method="get">
						<div class="form-group row">
							<label class="col-lg-3 control-label text-lg-right pt-2" for="inputDefault"><?=active?></label>
							<div class="col-lg-6">
								<div class="checkbox-custom checkbox-default">
									<input type="checkbox" name="is_valid" id="is_valid" <?=((isset($dr_e["is_valid"]) && $dr_e["is_valid"]=='True') ? 'checked':'')?>>
									<label for="is_valid"></label>
								</div>
							</div>
						</div>
					
						<div class="form-group row">
							<label class="col-lg-3 control-label text-lg-right pt-2" for="sensortype_name">Ονομασία</label>
							<div class="col-lg-6">
								<input type="text" class="form-control" id="sensortype_name" name="sensortype_name" value="<?=(isset($dr_e["sensortype_name"]) ? $dr_e["sensortype_name"]:'')?>">
							</div>
						</div>

						<div class="form-group row">
							<label class="col-lg-3 control-label text-lg-right pt-2" for="min">Ελάχιστη τιμή</label>
							<div class="col-lg-6">
								<input type="text" class="form-control" id="min" name="min" value="<?=(isset($dr_e["min"]) ? $dr_e["min"]:'')?>">
							</div>
						</div>

						<div class="form-group row">
							<label class="col-lg-3 control-label text-lg-right pt-2" for="max">Μέγιστη τιμή</label>
							<div class="col-lg-6">
								<input type="text" class="form-control" id="max" name="max" value="<?=(isset($dr_e["max"]) ? $dr_e["max"]:'')?>">
							</div>
						</div>						
						
						
						<div class="form-group row">
							<label class="col-lg-3 control-label text-lg-right pt-2" for="sensortype_description">Περιγραφή</label>
							<div class="col-lg-6">
								<textarea class="form-control" name="sensortype_description" id="sensortype_description" rows="3"  data-plugin-textarea-autosize><?=$dr_e["sensortype_description"]?></textarea>
							</div>
						</div>
					</div>
					<div class="row-fluid" style="margin-top:20px;">
						<a href="#" onClick="checkFields();">   <button type="button" class="btn btn-primary">Αποθήκευση</button></a>
						<a href="index.php?com=sensortypes"><button type="button" class="btn btn-primary">Επιστροφή</button></a>
					</div>
				</div>
			</section>
		</div>
	</div>
	
	

	<script>
		//document.getElementById("submitBtn").disabled = true;
		function checkFields(){
		var sensortype_name = $('#sensortype_name').val();
		//var user_name = $('#user_name').val();

			if ( sensortype_name.length >= 2 ){ //&& user_name.length >= 5 && user_password.length >= 5
					cm('SAVE',1,0,'');//document.getElementById("submitBtn").disabled = false;
			} else {
				//document.getElementById("submitBtn").disabled = true;
				alert('2 chars');
			}
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
		
						<h2 class="card-title"><?=$nav?></h2>
					</header>
					<div class="card-body">
						<table class="table table-bordered table-striped mb-0" id="datatable-default">
							<thead>
								<tr>
									<th>#</th>
									<th><?=active?></th>
									<th>Ονομασία</th>
									<th>Ελάχιστη</th>
									<th>Μέγιστη</th>
									<th>Ημ/νία εισαγωγής</th>
									<th>Ενέργεια</th>
								</tr>
							</thead>
							<tbody>
								<?	
									//$filter.=($auth->UserType != "Administrator"?' AND t1.region_id='.$auth->UserRow['region_id']:'');
									$query = "SELECT * FROM sensortypes WHERE 1=1 ".$filter." ORDER BY sensortype_name ";
									$result = $db->sql_query($query);
									$counter = 0;
									while ($dr = $db->sql_fetchrow($result))
									{
										?>
											<tr>
												<td><?=$dr["sensortype_id"]?></td>
												<td><?=($dr["is_valid"]=='True'?'Ναι':'Οχι')?></td>
												<td><?=$dr["sensortype_name"]?></td>
												<td><?=$dr["min"]?></td>
												<td><?=$dr["max"]?></td>
												<td><?=$dr["date_insert"]?></td>
												<td>
													<a style="padding:4px"  href="index.php?com=sensortypes&Command=edit&item=<?=$dr["sensortype_id"]?>"><i style="font-size:24px;" class="fas fa-edit"></i></a>
													<a href="#" onclick="ConfirmDelete('Επιβεβαίωση διαγραφής','index.php?com=sensortypes&Command=DELETE&item=<?=$dr["sensortype_id"]?>');"><span><i style="font-size:24px;" class="fas fa-trash"></i> </a></span></a>
												</td>
											</tr>
										<?
									}
									$db->sql_freeresult($result);
								?>
							</tbody>
						</table>
						<div class="row-fluid" style="margin-top:20px;">
							<a href="index.php?com=sensortypes&item="><button type="button" class="btn btn-primary">Νέα εγγραφή</button></a>
						</div>
					</div>
				</section>
			</div>
		</div>

<? } ?> 