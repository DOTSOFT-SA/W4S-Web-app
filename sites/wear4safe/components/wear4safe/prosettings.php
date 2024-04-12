<?php
defined( '_VALID_PROCCESS' ) or die( 'Direct Access to this location is not allowed.' );
	include($config["physicalPath"]."/languages/".$auth->LanguageCode.".php");
	include($config["physicalPath"]."/perm.php");
//require_once(dirname(__FILE__) . "/common.php");
//if(($auth->UserRow['admin_type']=='LOCAL')) {
//	Redirect("index.php");
//}
if($auth->UserType != "Administrator" && $auth->UserRow['parent']>0) Redirect("index.php");
//if($auth->UserType != "Administrator" && !($_SESSION["permissions"] & $FLAG_300)) Redirect("index.php");
global $nav;
$nav = "Ρυθμίσεις ΜΑΠ";
$config["navigation"] = $nav;
$item = (intval($_GET["item"]) > 0 ? intval($_GET["item"]) : "");
$BaseUrl = "index.php?com=prosettings";
$command=array();
$command=explode("&",$_POST["Command"]);

//if( $auth->UserType == "Administrator" )
//{
	

	if(isset($_REQUEST["Command"]))
	{	
		if($_REQUEST["Command"] == "SAVE")
		{

			if((intval($_POST["organization_id"])==0 && $auth->UserType == "Administrator") || intval($_POST["profession_id"])==0){
				$messages->addMessage("Ο οργανισμός και το επάγγελμα δεν μπορούν να είναι κενά. Η εγγραφή δεν αποθηκευτηκε");
				Redirect($BaseUrl);
			} else {
				$check = $db->RowSelectorQuery("SELECT * FROM prosettings WHERE profession_id = '".$_POST["profession_id"]."' AND organization_id = '".$_POST["organization_id"]."'");
				//if(intval($check['prosetting_id'])>0){
				//	$messages->addMessage("Υπάρχει ήδη εγγραφή για το επάγγελμα στο συγκεκριμένο δήμο. Η εγγραφή δεν αποθηκευτηκε");
				//	Redirect($BaseUrl);
				//} else {
						
					$PrimaryKeys = array();
					$Collector = array();
					$QuotFields = array();
					
					if(isset($_GET["item"]) && $_GET["item"] != "" && intval($_GET["item"])> 0)
					{
						$PrimaryKeys["prosetting_id"] = intval($_GET["item"]);
						$QuotFields["prosetting_id"] = true; 
						
					} else {
						$Collector["date_insert"] = date('Y-m-d H:i:s');
						$QuotFields["date_insert"] = true;
					}

					$Collector["mandatory1"] = isset($_POST["mandatory1"]) && $_POST["mandatory1"] == "on" ? "True" : "False";
					$QuotFields["mandatory1"] = true;

					$Collector["mandatory2"] = isset($_POST["mandatory2"]) && $_POST["mandatory2"] == "on" ? "True" : "False";
					$QuotFields["mandatory2"] = true;

					$Collector["mandatory3"] = isset($_POST["mandatory3"]) && $_POST["mandatory3"] == "on" ? "True" : "False";
					$QuotFields["mandatory3"] = true;

					$Collector["mandatory4"] = isset($_POST["mandatory4"]) && $_POST["mandatory4"] == "on" ? "True" : "False";
					$QuotFields["mandatory4"] = true;

					$Collector["mandatory5"] = isset($_POST["mandatory5"]) && $_POST["mandatory5"] == "on" ? "True" : "False";
					$QuotFields["mandatory5"] = true;
								
					$Collector["organization_id"] = ($auth->UserType == "Administrator"?$_POST["organization_id"]:$auth->UserRow['organization_id']);
					$QuotFields["organization_id"] = true;		

					$Collector["profession_id"] = $_POST["profession_id"];
					$QuotFields["profession_id"] = true;		
					
					$Collector["sensor1_maptype_id"] = $_POST["sensor1_maptype_id"];
					$QuotFields["sensor1_maptype_id"] = true;	
					
					$Collector["sensor2_maptype_id"] = $_POST["sensor2_maptype_id"];
					$QuotFields["sensor2_maptype_id"] = true;	
					
					$Collector["sensor3_maptype_id"] = $_POST["sensor3_maptype_id"];
					$QuotFields["sensor3_maptype_id"] = true;	
					
					$Collector["sensor4_maptype_id"] = $_POST["sensor4_maptype_id"];
					$QuotFields["sensor4_maptype_id"] = true;	

					$Collector["sensor5_maptype_id"] = $_POST["sensor5_maptype_id"];
					$QuotFields["sensor5_maptype_id"] = true;	
						
					
					$Collector["condition1_id"] = $_POST["condition1_id"];
					$QuotFields["condition1_id"] = true;	
					
					$Collector["condition2_id"] = $_POST["condition2_id"];
					$QuotFields["condition2_id"] = true;	
					
					$Collector["condition3_id"] = $_POST["condition3_id"];
					$QuotFields["condition3_id"] = true;	
					
					$Collector["condition4_id"] = $_POST["condition4_id"];
					$QuotFields["condition4_id"] = true;	
					
					$Collector["condition5_id"] = $_POST["condition5_id"];
					$QuotFields["condition5_id"] = true;	
					
					
					if(intval($_POST["sensor1_maptype_id"])>0){
						$drMap1 = $db->RowSelectorQuery("SELECT * FROM maptypes WHERE maptype_id='".$_POST["sensor1_maptype_id"]."' LIMIT 1");
						$map1=$drMap1['map_id'];
						$Collector["sensor1_map_id"] = $map1;
						$QuotFields["sensor1_map_id"] = true;					
					}

					if(intval($_POST["sensor2_maptype_id"])>0){
						$drMap2 = $db->RowSelectorQuery("SELECT * FROM maptypes WHERE maptype_id='".$_POST["sensor2_maptype_id"]."' LIMIT 1");
						$map2=$drMap2['map_id'];
						$Collector["sensor2_map_id"] = $map2;
						$QuotFields["sensor2_map_id"] = true;	
					}
					
					if(intval($_POST["sensor3_maptype_id"])>0){
						$drMap3 = $db->RowSelectorQuery("SELECT * FROM maptypes WHERE maptype_id='".$_POST["sensor3_maptype_id"]."' LIMIT 1");
						$map3=$drMap3['map_id'];
						$Collector["sensor3_map_id"] = $map3;
						//$Collector["sensor3_map_id"] = $_POST["sensor3_map_id"];
						$QuotFields["sensor3_map_id"] = true;	
					}
					
					if(intval($_POST["sensor4_maptype_id"])>0){	
						$drMap4 = $db->RowSelectorQuery("SELECT * FROM maptypes WHERE maptype_id='".$_POST["sensor4_maptype_id"]."' LIMIT 1");
						$map4=$drMap4['map_id'];
						$Collector["sensor4_map_id"] = $map4;
						//$Collector["sensor4_map_id"] = $_POST["sensor4_map_id"];
						$QuotFields["sensor4_map_id"] = true;
					}
					
					if(intval($_POST["sensor5_maptype_id"])>0){	
						$drMap5 = $db->RowSelectorQuery("SELECT * FROM maptypes WHERE maptype_id='".$_POST["sensor5_maptype_id"]."' LIMIT 1");
						$map5=$drMap5['map_id'];
						$Collector["sensor5_map_id"] = $map5;
						//$Collector["sensor5_map_id"] = $_POST["sensor5_map_id"];
						$QuotFields["sensor5_map_id"] = true;	
					}
								
					$Collector["description"] = $_POST["description"];
					$QuotFields["description"] = true;	
					
					$db->ExecuteUpdater("prosettings",$PrimaryKeys,$Collector,$QuotFields);
					$messages->addMessage("SAVED!!!");
					Redirect($BaseUrl);
				//}
			}
				

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
					$db->sql_query("DELETE FROM prosettings WHERE prosetting_id=" . $item.$filter);
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
		$filter.=($auth->UserType != "Administrator"?' AND organization_id='.$auth->UserRow['organization_id'] :'');
		$query="SELECT * FROM prosettings WHERE prosetting_id=".$_GET['item'].$filter;
		$dr_e = $db->RowSelectorQuery($query);
		if (!isset($dr_e["prosetting_id"]) && intval($_GET["item"])> 0) {
			$messages->addMessage("NOT FOUND!!!");
			Redirect("index.php?com=prosettings");
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
				
				
						<? if($auth->UserType == "Administrator") { ?>
						<div class="form-group row">
							<label class="col-lg-3 control-label text-lg-right pt-2" for="organization_id">Οργανισμός</label>
							<div class="col-lg-6">
								<select name="organization_id" id="organization_id" class="form-control mb-3">
									<option value="0">Επιλογή</option>
									<?
										$filter=" AND is_valid='True'";
										//$filter.=($auth->UserType != "Administrator"?' AND region_id='.$auth->UserRow['region_id']:'');
										$resultOrg = $db->sql_query("SELECT * FROM organizations WHERE 1=1 ".$filter." ORDER BY organization_name ");
										while ($drOrg = $db->sql_fetchrow($resultOrg)){
											echo '<option value="'.$drOrg['organization_id'].'" '.($drOrg['organization_id']==$dr_e['organization_id']?' selected':'').'>'.$drOrg['organization_name'].'</option>';
										}
									?>
								</select>
							</div>
						</div>	
						<? } ?>
						
						<div class="form-group row">
							<label class="col-lg-3 control-label text-lg-right pt-2">Επάγγελμα</label>
							<div class="col-lg-6">
								<select name="profession_id" id="profession_id" class="form-control mb-3" required>
									<option value="">Επιλογή</option>
									<? 
										$filter="";
										$filter.=($auth->UserType != "Administrator"?" AND profession_id NOT IN (SELECT profession_id FROM prosettings WHERE organization_id='".$auth->UserRow['organization_id']."')":'');
										$resultProfessions = $db->sql_query("SELECT * FROM professions WHERE 1=1 ".$filter." AND is_valid='True' ".$filter." ORDER BY profession_name");
										while ($drProfessions = $db->sql_fetchrow($resultProfessions)){
											echo '<option value="'.$drProfessions['profession_id'].'" '.($drProfessions['profession_id']==$dr_e['profession_id']?' selected':'').'>'.$drProfessions['profession_name'].'</option>';
										}
									?>
									
								</select>
							</div>
						</div>
					
						<?
						$filterProfession="";
						if(isset($dr_e['profession_id']) && intval($dr_e['profession_id'])>0){
							$filterProfession = " AND maptype_id IN (SELECT maptype_id FROM professionsmap WHERE profession_id='".$dr_e['profession_id']."')";
						}
						
						?>
						
						<div class="form-group row">
							<label class="col-lg-3 control-label text-lg-right pt-2">ΜΑΠ 1</label>
							<div class="col-lg-3">
								<select name="sensor1_maptype_id" id="sensor1_maptype_id" class="form-control mb-3">
									<option value="">Επιλογή</option>
									<? 
										$resultSensor1 = $db->sql_query("SELECT * FROM maptypes WHERE 1=1 AND is_valid='True' ".$filterProfession." ORDER BY maptype_name");
										while ($drSensor1 = $db->sql_fetchrow($resultSensor1)){
											echo '<option value="'.$drSensor1['maptype_id'].'" '.($drSensor1['maptype_id']==$dr_e['sensor1_maptype_id']?' selected':'').'>'.$drSensor1['maptype_name'].'</option>';
										}
									?>
									
								</select>
							</div>
							<div class="col-lg-2">
								<div class="col-lg-6">
									<div class="checkbox-custom checkbox-default">
										<input type="checkbox" name="mandatory1" id="mandatory1" <?=(isset($dr_e["mandatory1"]) && $dr_e["mandatory1"]=='True' ? 'checked':'')?>>
										<label for="mandatory1">Υποχρεωτικό</label>
									</div>
								</div>							
							</div>
							<div class="col-lg-4">
								<div class="col-lg-9">
								<!-- <label class="control-label text-lg-right pt-2">Συνθήκες</label>-->
									<select name="condition1_id" id="condition1_id" class="form-control mb-3">
										<option value="0" <?=(intval($dr_e['condition1_id']==0)?' selected':'')?>>Χωρίς συνθήκη</option>
										<?
											$filter=" AND is_valid='True'";
											$resultCondition1 = $db->sql_query("SELECT * FROM conditions WHERE 1=1 ".$filter." ORDER BY condition_name ");
											while ($drCondition1 = $db->sql_fetchrow($resultCondition1)){
												echo '<option value="'.$drCondition1['condition_id'].'" '.($dr_e['condition1_id']==$drCondition1['condition_id']?' selected':'').'>'.$drCondition1['condition_name'].'</option>';
											}
										?>
									</select>
								</div>
							</div>
						</div>
						
						
						<div class="form-group row">
							<label class="col-lg-3 control-label text-lg-right pt-2">ΜΑΠ 2</label>
							<div class="col-lg-3">
								<select name="sensor2_maptype_id" id="sensor2_maptype_id" class="form-control mb-3">
									<option value="">Επιλογή</option>
									<? 
										$resultSensor2 = $db->sql_query("SELECT * FROM maptypes WHERE 1=1 AND is_valid='True' ".$filterProfession." ORDER BY maptype_name");
										while ($drSensor2 = $db->sql_fetchrow($resultSensor2)){
											echo '<option value="'.$drSensor2['maptype_id'].'" '.($drSensor2['maptype_id']==$dr_e['sensor2_maptype_id']?' selected':'').'>'.$drSensor2['maptype_name'].'</option>';
										}
									?>
									
								</select>
							</div>
							<div class="col-lg-2">
								<div class="col-lg-6">
									<div class="checkbox-custom checkbox-default">
										<input type="checkbox" name="mandatory2" id="mandatory2" <?=(isset($dr_e["mandatory2"]) && $dr_e["mandatory2"]=='True' ? 'checked':'')?>>
										<label for="mandatory2">Υποχρεωτικό</label>
									</div>
								</div>							
							</div>
							<div class="col-lg-4">
								<div class="col-lg-9">
								<!-- <label class="control-label text-lg-right pt-2">Συνθήκες</label>-->
									<select name="condition2_id" id="condition2_id" class="form-control mb-3">
										<option value="0" <?=(intval($dr_e['condition_id']==0)?' selected':'')?>>Χωρίς συνθήκη</option>
										<?
											$filter=" AND is_valid='True'";
											$resultCondition2 = $db->sql_query("SELECT * FROM conditions WHERE 1=1 ".$filter." ORDER BY condition_name ");
											while ($drCondition2 = $db->sql_fetchrow($resultCondition2)){
												echo '<option value="'.$drCondition2['condition_id'].'" '.($dr_e['condition2_id']==$drCondition2['condition_id']?' selected':'').'>'.$drCondition2['condition_name'].'</option>';
											}
										?>
									</select>
								</div>
							</div>
						</div>
						
						
						<div class="form-group row">
							<label class="col-lg-3 control-label text-lg-right pt-2">ΜΑΠ 3</label>
							<div class="col-lg-3">
								<select name="sensor3_maptype_id" id="sensor3_maptype_id" class="form-control mb-3">
									<option value="">Επιλογή</option>
									<? 
										$resultSensor3 = $db->sql_query("SELECT * FROM maptypes WHERE 1=1 AND is_valid='True' ".$filterProfession." ORDER BY maptype_name");
										while ($drSensor3 = $db->sql_fetchrow($resultSensor3)){
											echo '<option value="'.$drSensor3['maptype_id'].'" '.($drSensor3['maptype_id']==$dr_e['sensor3_maptype_id']?' selected':'').'>'.$drSensor3['maptype_name'].'</option>';
										}
									?>
									
								</select>
							</div>
							<div class="col-lg-2">
								<div class="col-lg-6">
									<div class="checkbox-custom checkbox-default">
										<input type="checkbox" name="mandatory3" id="mandatory3" <?=(isset($dr_e["mandatory3"]) && $dr_e["mandatory3"]=='True' ? 'checked':'')?>>
										<label for="mandatory3">Υποχρεωτικό</label>
									</div>
								</div>							
							</div>
							<div class="col-lg-4">
								<div class="col-lg-9">
								<!-- <label class="control-label text-lg-right pt-2">Συνθήκες</label>-->
									<select name="condition3_id" id="condition3_id" class="form-control mb-3">
										<option value="0" <?=(intval($dr_e['condition_id']==0)?' selected':'')?>>Χωρίς συνθήκη</option>
										<?
											$filter=" AND is_valid='True'";
											$resultCondition3 = $db->sql_query("SELECT * FROM conditions WHERE 1=1 ".$filter." ORDER BY condition_name ");
											while ($drCondition3 = $db->sql_fetchrow($resultCondition3)){
												echo '<option value="'.$drCondition3['condition_id'].'" '.($dr_e['condition_id']==$drCondition3['condition_id']?' selected':'').'>'.$drCondition3['condition_name'].'</option>';
											}
										?>
									</select>
								</div>
							</div>
						</div>
						
						
						<div class="form-group row">
							<label class="col-lg-3 control-label text-lg-right pt-2">ΜΑΠ 4</label>
							<div class="col-lg-3">
								<select name="sensor4_maptype_id" id="sensor4_maptype_id" class="form-control mb-3">
									<option value="">Επιλογή</option>
									<? 
										$resultSensor4 = $db->sql_query("SELECT * FROM maptypes WHERE 1=1 AND is_valid='True' ".$filterProfession." ORDER BY maptype_name");
										while ($drSensor4 = $db->sql_fetchrow($resultSensor4)){
											echo '<option value="'.$drSensor4['maptype_id'].'" '.($drSensor4['maptype_id']==$dr_e['sensor4_maptype_id']?' selected':'').'>'.$drSensor4['maptype_name'].'</option>';
										}
									?>
									
								</select>
							</div>
							<div class="col-lg-2">
								<div class="col-lg-6">
									<div class="checkbox-custom checkbox-default">
										<input type="checkbox" name="mandatory4" id="mandatory4" <?=(isset($dr_e["mandatory4"]) && $dr_e["mandatory4"]=='True' ? 'checked':'')?>>
										<label for="mandatory4">Υποχρεωτικό</label>
									</div>
								</div>							
							</div>
							<div class="col-lg-4">
								<div class="col-lg-9">
								<!-- <label class="control-label text-lg-right pt-2">Συνθήκες</label>-->
									<select name="condition4_id" id="condition4_id" class="form-control mb-3">
										<option value="0" <?=(intval($dr_e['condition4_id']==0)?' selected':'')?>>Χωρίς συνθήκη</option>
										<?
											$filter=" AND is_valid='True'";
											$resultCondition4 = $db->sql_query("SELECT * FROM conditions WHERE 1=1 ".$filter." ORDER BY condition_name ");
											while ($drCondition4 = $db->sql_fetchrow($resultCondition4)){
												echo '<option value="'.$drCondition4['condition_id'].'" '.($dr_e['condition4_id']==$drCondition4['condition_id']?' selected':'').'>'.$drCondition4['condition_name'].'</option>';
											}
										?>
									</select>
								</div>
							</div>
						</div>
						
						
						
						<div class="form-group row">
							<label class="col-lg-3 control-label text-lg-right pt-2">ΜΑΠ 5</label>
							<div class="col-lg-3">
								<select name="sensor5_maptype_id" id="sensor5_maptype_id" class="form-control mb-3">
									<option value="">Επιλογή</option>
									<? 
										$resultSensor5 = $db->sql_query("SELECT * FROM maptypes WHERE 1=1 AND is_valid='True' ".$filterProfession." ORDER BY maptype_name");
										while ($drSensor5 = $db->sql_fetchrow($resultSensor5)){
											echo '<option value="'.$drSensor5['maptype_id'].'" '.($drSensor5['maptype_id']==$dr_e['sensor5_maptype_id']?' selected':'').'>'.$drSensor5['maptype_name'].'</option>';
										}
									?>
								</select>
							</div>
							<div class="col-lg-2">
								<div class="col-lg-6">
									<div class="checkbox-custom checkbox-default">
										<input type="checkbox" name="mandatory5" id="mandatory5" <?=(isset($dr_e["mandatory5"]) && $dr_e["mandatory5"]=='True' ? 'checked':'')?>>
										<label for="mandatory5">Υποχρεωτικό</label>
									</div>
								</div>							
							</div>
							<div class="col-lg-4">
								<div class="col-lg-9">
								<!-- <label class="control-label text-lg-right pt-2">Συνθήκες</label>-->
									<select name="condition5_id" id="condition5_id" class="form-control mb-3">
										<option value="0" <?=(intval($dr_e['condition5_id']==0)?' selected':'')?>>Χωρίς συνθήκη</option>
										<?
											$filter=" AND is_valid='True'";
											$resultCondition5 = $db->sql_query("SELECT * FROM conditions WHERE 1=1 ".$filter." ORDER BY condition_name ");
											while ($drCondition5 = $db->sql_fetchrow($resultCondition5)){
												echo '<option value="'.$drCondition5['condition_id'].'" '.($dr_e['condition5_id']==$drCondition5['condition_id']?' selected':'').'>'.$drCondition5['condition_name'].'</option>';
											}
										?>
									</select>
								</div>
							</div>
						</div>
						
						<div class="form-group row">
							<label class="col-lg-3 control-label text-lg-right pt-2" for="sensor_description">Περιγραφή</label>
							<div class="col-lg-6">
								<textarea class="form-control" name="sensor_description" id="sensor_description" rows="3"  data-plugin-textarea-autosize><?=$dr_e["sensor_description"]?></textarea>
							</div>
						</div>
					</div>
					<div class="row-fluid" style="margin-top:20px;">
						<a href="#" onClick="checkFields();">   <button type="button" class="btn btn-primary">Αποθήκευση</button></a>
						<a href="index.php?com=prosettings"><button type="button" class="btn btn-primary">Επιστροφή</button></a>
					</div>
				</div>
			</section>
		</div>
	</div>
	
	

	<script>
		//document.getElementById("submitBtn").disabled = true;
		function checkFields(){
			//var imei = $('#imei').val();
			//var user_name = $('#user_name').val();

			//if ( imei.length >= 2 ){ //&& user_name.length >= 5 && user_password.length >= 5
					cm('SAVE',1,0,'');//document.getElementById("submitBtn").disabled = false;
			//} else {
				//document.getElementById("submitBtn").disabled = true;
			//	alert('2 chars');
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
						<h2 class="card-title"><?=$nav?></h2>
					</header>
					<div class="card-body">
						<!-- <table class="table table-bordered table-striped mb-0" id="datatable-default">-->
						<table class="table table-responsive-lg  table-bordered table-striped mb-0" id="datatable-filters">
							<thead>
								<tr>
									<!-- <th>#</th>-->
									<? if($auth->UserType == "Administrator"){?> <th>Οργανισμός</th><? } ?>
									<th>Επάγγελμα</th>
									<th>ΜΑΠ 1</th>
									<th>ΜΑΠ 2</th>
									<th>ΜΑΠ 3</th>
									<th>ΜΑΠ 4</th>
									<th>ΜΑΠ 5</th>
									<th>Ημ/νία εισαγωγής</th>
									<th>Ενέργεια</th>
								</tr>
							</thead>
							<tbody>
								<?	
									$filter.=($auth->UserType != "Administrator"?' AND t1.organization_id='.$auth->UserRow['organization_id']:'');
									$query = "SELECT t1.*,t2.organization_name,t8.profession_name,t3.maptype_name AS sensor1_name,t4.maptype_name AS sensor2_name,t5.maptype_name AS sensor3_name,t6.maptype_name AS sensor4_name,t7.maptype_name AS sensor5_name,
									t9.condition_name AS condition1_name,
									t10.condition_name AS condition2_name,
									t11.condition_name AS condition3_name,
									t12.condition_name AS condition4_name,
									t13.condition_name AS condition5_name
									FROM prosettings t1 
									INNER JOIN organizations t2 ON t1.organization_id=t2.organization_id
									INNER JOIN professions t8 ON t1.profession_id=t8.profession_id
									LEFT JOIN maptypes t3 ON t1.sensor1_maptype_id=t3.maptype_id
									LEFT JOIN maptypes t4 ON t1.sensor2_maptype_id=t4.maptype_id
									LEFT JOIN maptypes t5 ON t1.sensor3_maptype_id=t5.maptype_id
									LEFT JOIN maptypes t6 ON t1.sensor4_maptype_id=t6.maptype_id
									LEFT JOIN maptypes t7 ON t1.sensor5_maptype_id=t7.maptype_id
									LEFT JOIN conditions t9 ON t1.condition1_id=t9.condition_id
									LEFT JOIN conditions t10 ON t1.condition2_id=t10.condition_id
									LEFT JOIN conditions t11 ON t1.condition3_id=t11.condition_id
									LEFT JOIN conditions t12 ON t1.condition4_id=t12.condition_id
									LEFT JOIN conditions t13 ON t1.condition5_id=t13.condition_id
									WHERE 1=1 ".$filter."  ";
									
									$result = $db->sql_query($query);
									$counter = 0;
									while ($dr = $db->sql_fetchrow($result))
									{
										?>
											<tr>
												<? $desc1 = ($dr["mandatory1"]=='True'?" (!)":($dr['condition1_name']!=""?" (".$dr['condition1_name'].")":""))?>
												<? $desc2 = ($dr["mandatory2"]=='True'?" (!)":($dr['condition2_name']!=""?" (".$dr['condition2_name'].")":""))?>
												<? $desc3 = ($dr["mandatory3"]=='True'?" (!)":($dr['condition3_name']!=""?" (".$dr['condition3_name'].")":""))?>
												<? $desc4 = ($dr["mandatory4"]=='True'?" (!)":($dr['condition4_name']!=""?" (".$dr['condition4_name'].")":""))?>
												<? $desc5 = ($dr["mandatory5"]=='True'?" (!)":($dr['condition5_name']!=""?" (".$dr['condition5_name'].")":""))?>
												<?=($auth->UserType == "Administrator"?"<td>".$dr['organization_name']."</td>":"")?> 
												<td><?=$dr["profession_name"]?></td>
												<td><?=$dr["sensor1_name"].$desc1?></td>
												<td><?=$dr["sensor2_name"].$desc2?></td>
												<td><?=$dr["sensor3_name"].$desc3?></td>
												<td><?=$dr["sensor4_name"].$desc4?></td>
												<td><?=$dr["sensor5_name"].$desc5?></td>
												<td><?=$dr["date_insert"]?></td>
												<td>
													<a style="padding:4px"  href="index.php?com=prosettings&Command=edit&item=<?=$dr["prosetting_id"]?>"><i style="font-size:24px;" class="fas fa-edit"></i></a>
													<a href="#" onclick="ConfirmDelete('Επιβεβαίωση διαγραφής','index.php?com=prosettings&Command=DELETE&item=<?=$dr["prosetting_id"]?>');"><span><i style="font-size:24px;" class="fas fa-trash"></i> </a></span></a>
												</td>
											</tr>
										<?
									}
									$db->sql_freeresult($result);
								?>
							</tbody>
						</table>
						<div class="row-fluid" style="margin-top:20px;">
							<a href="index.php?com=prosettings&item="><button type="button" class="btn btn-primary">Νέα εγγραφή</button></a>
						</div>
					</div>
				</section>
			</div>
		</div>

<? } ?> 

		<script>
		// In your Javascript (external .js resource or <script> tag)
		$(document).ready(function() {
			$('#profession_id').select2();
			$('#organization_id').select2();
			$('#sensor1_maptype_id').select2();
			$('#sensor2_maptype_id').select2();
			$('#sensor3_maptype_id').select2();
			$('#sensor4_maptype_id').select2();
			$('#sensor5_maptype_id').select2();
			$('#condition1_id').select2();
			$('#condition2_id').select2();
			$('#condition3_id').select2();
			$('#condition4_id').select2();
			$('#condition5_id').select2();
			
		});

		$(document).ready(function(){
			$('#profession_id').on('change', function(){
				var professionID = $(this).val();
				if(professionID){
					$.ajax({
						type:'POST',
						url:'/sites/wear4safe/components/wear4safe/ajaxDataProfessions.php',
						data:'profession_id='+professionID,
						success:function(html){
							$('#sensor1_maptype_id').html(html);
							$('#sensor2_maptype_id').html(html);
							$('#sensor3_maptype_id').html(html);
							$('#sensor4_maptype_id').html(html);
							$('#sensor5_maptype_id').html(html);
						}
					}); 
				}else{
					$('#sensor1_maptype_id').html('<option value="">Επιλέξτε επάγγελμα</option>');
					$('#sensor2_maptype_id').html('<option value="">Επιλέξτε επάγγελμα</option>');
					$('#sensor3_maptype_id').html('<option value="">Επιλέξτε επάγγελμα</option>');
					$('#sensor4_maptype_id').html('<option value="">Επιλέξτε επάγγελμα</option>');
					$('#sensor5_maptype_id').html('<option value="">Επιλέξτε επάγγελμα</option>'); 
				}
			});
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