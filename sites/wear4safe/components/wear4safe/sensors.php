<?php
defined( '_VALID_PROCCESS' ) or die( 'Direct Access to this location is not allowed.' );
	include($config["physicalPath"]."/languages/".$auth->LanguageCode.".php");
	include($config["physicalPath"]."/perm.php");
//require_once(dirname(__FILE__) . "/common.php");
//if(($auth->UserRow['admin_type']=='LOCAL')) {
//	Redirect("index.php");
//}
if($auth->UserType != "Administrator" && $auth->UserRow['parent']>0) Redirect("index.php");
//if($auth->UserType != "Administrator") Redirect("index.php");
//if($auth->UserType != "Administrator" && !($_SESSION["permissions"] & $FLAG_300)) Redirect("index.php");
global $nav;
$nav = "Αισθητήρες";
$config["navigation"] = $nav;
$item = (intval($_GET["item"]) > 0 ? intval($_GET["item"]) : "");
$BaseUrl = "index.php?com=sensors";
$command=array();
$command=explode("&",$_POST["Command"]);

//if( $auth->UserType == "Administrator" )
//{
	if(isset($_REQUEST["Command"]))
	{	
		if($_REQUEST["Command"] == "SAVE")
		{
			
			$org=intval($auth->UserType == "Administrator"?$_POST["organization_id"]:$auth->UserRow['organization_id']);
			$pro = intval($_POST["profession_id"]);
			
			if($org==0 || $pro==0){
				$messages->addMessage("Δεν έχουν συμπληρωθεί όλα τα πεδία. Η εγγραφή δεν αποθηκευτηκε");
				Redirect($BaseUrl);
			} else {
				$row = $db->RowSelectorQuery("SELECT * FROM prosettings WHERE organization_id='".$org."' AND profession_id='".$pro."'");
				if(intval($row['prosetting_id'])==0){
					$messages->addMessage("Η σχετιζόμενη εγγραφή δεν βρέθηκε");
					Redirect($BaseUrl);
				}
				
				$PrimaryKeys = array();
				$Collector = array();
				$QuotFields = array();
				
				if(isset($_GET["item"]) && $_GET["item"] != "" && intval($_GET["item"])> 0)
				{
					$PrimaryKeys["sensor_id"] = intval($_GET["item"]);
					$QuotFields["sensor_id"] = true; 
					
				} else {
					$Collector["date_insert"] = date('Y-m-d H:i:s');
					$QuotFields["date_insert"] = true;
				}

				$Collector["is_valid"] = isset($_POST["is_valid"]) && $_POST["is_valid"] == "on" ? "True" : "False";
				$QuotFields["is_valid"] = true;
				
				if($auth->UserType == "Administrator" ){
					$Collector["imei"] = $_POST["imei"];
					$QuotFields["imei"] = true;		
				}
				
				$Collector["sensor_name"] = $_POST["sensor_name"];
				$QuotFields["sensor_name"] = true;	
				
				$Collector["organization_id"] = ($auth->UserType == "Administrator"?$_POST["organization_id"]:$auth->UserRow['organization_id']);
				$QuotFields["organization_id"] = true;		

				$Collector["profession_id"] = $_POST["profession_id"];
				$QuotFields["profession_id"] = true;		
				
				$sensor1_maptype_id = (intval($row['sensor1_maptype_id'])>0?$row['sensor1_maptype_id']:null);
				$sensor2_maptype_id = (intval($row['sensor2_maptype_id'])>0?$row['sensor2_maptype_id']:null);
				$sensor3_maptype_id = (intval($row['sensor3_maptype_id'])>0?$row['sensor3_maptype_id']:null);
				$sensor4_maptype_id = (intval($row['sensor4_maptype_id'])>0?$row['sensor4_maptype_id']:null);
				$sensor5_maptype_id = (intval($row['sensor5_maptype_id'])>0?$row['sensor5_maptype_id']:null);
				
				//$Collector["sensor1_maptype_id"] = $_POST["sensor1_maptype_id"];
				$Collector["sensor1_maptype_id"] = $sensor1_maptype_id;
				$QuotFields["sensor1_maptype_id"] = true;	
				
				//$Collector["sensor2_maptype_id"] = $_POST["sensor2_maptype_id"];
				$Collector["sensor2_maptype_id"] = $sensor2_maptype_id;
				$QuotFields["sensor2_maptype_id"] = true;	
				
				//$Collector["sensor3_maptype_id"] = $_POST["sensor3_maptype_id"];
				$Collector["sensor3_maptype_id"] = $sensor3_maptype_id;
				$QuotFields["sensor3_maptype_id"] = true;	
				
				//$Collector["sensor4_maptype_id"] = $_POST["sensor4_maptype_id"];
				$Collector["sensor4_maptype_id"] = $sensor4_maptype_id;
				$QuotFields["sensor4_maptype_id"] = true;	

				//$Collector["sensor5_maptype_id"] = $_POST["sensor5_maptype_id"];
				$Collector["sensor5_maptype_id"] = $sensor5_maptype_id;
				$QuotFields["sensor5_maptype_id"] = true;	
				
				//if(intval($_POST["sensor1_maptype_id"])>0){
				if(intval($sensor1_maptype_id)>0){
					//$drMap1 = $db->RowSelectorQuery("SELECT * FROM maptypes WHERE maptype_id='".$_POST["sensor1_maptype_id"]."' LIMIT 1");
					$drMap1 = $db->RowSelectorQuery("SELECT * FROM maptypes WHERE maptype_id='".$sensor1_maptype_id."' LIMIT 1");
					$map1=$drMap1['map_id'];
					//$Collector["sensor1_map_id"] = $_POST["sensor1_map_id"];
					$Collector["sensor1_map_id"] = $map1;
					$QuotFields["sensor1_map_id"] = true;					
				}


				//if(intval($_POST["sensor2_maptype_id"])>0){
				if(intval($sensor2_maptype_id)>0){
					//$drMap2 = $db->RowSelectorQuery("SELECT * FROM maptypes WHERE maptype_id='".$_POST["sensor2_maptype_id"]."' LIMIT 1");
					$drMap2 = $db->RowSelectorQuery("SELECT * FROM maptypes WHERE maptype_id='".$sensor2_maptype_id."' LIMIT 1");
					$map2=$drMap2['map_id'];
					$Collector["sensor2_map_id"] = $map2;
					//$Collector["sensor2_map_id"] = $_POST["sensor2_map_id"];
					$QuotFields["sensor2_map_id"] = true;	
				}
				
				//if(intval($_POST["sensor3_maptype_id"])>0){
				if(intval($sensor3_maptype_id)>0){
					//$drMap3 = $db->RowSelectorQuery("SELECT * FROM maptypes WHERE maptype_id='".$_POST["sensor3_maptype_id"]."' LIMIT 1");
					$drMap3 = $db->RowSelectorQuery("SELECT * FROM maptypes WHERE maptype_id='".$sensor3_maptype_id."' LIMIT 1");
					$map3=$drMap3['map_id'];
					$Collector["sensor3_map_id"] = $map3;
					//$Collector["sensor3_map_id"] = $_POST["sensor3_map_id"];
					$QuotFields["sensor3_map_id"] = true;	
				}
				
				//if(intval($_POST["sensor4_maptype_id"])>0){	
				if(intval($sensor4_maptype_id)>0){	
					//$drMap4 = $db->RowSelectorQuery("SELECT * FROM maptypes WHERE maptype_id='".$_POST["sensor4_maptype_id"]."' LIMIT 1");
					$drMap4 = $db->RowSelectorQuery("SELECT * FROM maptypes WHERE maptype_id='".$sensor4_maptype_id."' LIMIT 1");
					$map4=$drMap4['map_id'];
					$Collector["sensor4_map_id"] = $map4;
					//$Collector["sensor4_map_id"] = $_POST["sensor4_map_id"];
					$QuotFields["sensor4_map_id"] = true;
				}
				
				//if(intval($_POST["sensor5_maptype_id"])>0){	
				if(intval($sensor5_maptype_id)>0){	
					//$drMap5 = $db->RowSelectorQuery("SELECT * FROM maptypes WHERE maptype_id='".$_POST["sensor5_maptype_id"]."' LIMIT 1");
					$drMap5 = $db->RowSelectorQuery("SELECT * FROM maptypes WHERE maptype_id='".$sensor5_maptype_id."' LIMIT 1");
					$map5=$drMap5['map_id'];
					$Collector["sensor5_map_id"] = $map5;
					$Collector["sensor5_map_id"] = $_POST["sensor5_map_id"];
					$QuotFields["sensor5_map_id"] = true;	
				}
				
				$Collector["sensor_description"] = $_POST["sensor_description"];
				$QuotFields["sensor_description"] = true;	
				
				$db->ExecuteUpdater("sensors",$PrimaryKeys,$Collector,$QuotFields);
				
				if($auth->UserType == "Administrator" ){
					$nextID = $db->sql_nextid();			
					if(intval($_GET["item"])==0){
						//str_pad(string,length,pad_string,pad_type)
						$PrimaryKeys["sensor_id"] = $nextID;
						$QuotFields["sensor_id"] = true; 

						$Collector["sensor1_name"] = str_pad(strval(dechex($nextID)),7,'0',STR_PAD_LEFT).'1';
						$QuotFields["sensor1_name"] = true;			

						$Collector["sensor2_name"] = str_pad(strval(dechex($nextID)),7,'0',STR_PAD_LEFT).'2';
						$QuotFields["sensor2_name"] = true;			

						$Collector["sensor3_name"] = str_pad(strval(dechex($nextID)),7,'0',STR_PAD_LEFT).'3';
						$QuotFields["sensor3_name"] = true;			

						$Collector["sensor4_name"] = str_pad(strval(dechex($nextID)),7,'0',STR_PAD_LEFT).'4';
						$QuotFields["sensor4_name"] = true;			

						$Collector["sensor5_name"] = str_pad(strval(dechex($nextID)),7,'0',STR_PAD_LEFT).'5';
						$QuotFields["sensor5_name"] = true;	
						
						$db->ExecuteUpdater("sensors",$PrimaryKeys,$Collector,$QuotFields);
					}
				}

				$messages->addMessage("SAVED!!!");
				Redirect($BaseUrl);
				
			}
			

		} else if($_REQUEST["Command"] ==  "DELETE") { //$command[0] ==
			if($item != "")
			{
				$error=0;
				$result = $db->sql_query('SELECT * FROM data WHERE sensor_id='.$item);
				
				if($db->sql_numrows($result) > 0) $error++;
				$result = $db->sql_query('SELECT * FROM alerts WHERE sensor_id='.$item);
				if($db->sql_numrows($result) > 0) $error++;				
				//$result = $db->sql_query('SELECT * FROM messages WHERE user_id='.$item);
				//if($db->sql_numrows($result) > 0) $error++;
				if($error==0) {	
					//$filter=($auth->UserType != "Administrator"?' AND user_id IN (SELECT user_id FROM users WHERE parent='.$auth->UserId.')':'');
					$filter="";
					$db->sql_query("DELETE FROM sensors WHERE sensor_id=" . $item.$filter);
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
		$query="SELECT * FROM sensors WHERE sensor_id=".$_GET['item'].$filter;
		$dr_e = $db->RowSelectorQuery($query);
		if (!isset($dr_e["sensor_id"]) && intval($_GET["item"])> 0) {
			$messages->addMessage("NOT FOUND!!!");
			Redirect("index.php?com=sensors");
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
						
						<!-- 
						<div class="form-group row">
							<label class="col-lg-3 control-label text-lg-right pt-2">Τύπος αισθητήρα</label>
							<div class="col-lg-6">
								<select name="sensortype_id" id="sensortype_id" class="form-control mb-3">
									<option value="">Επιλογή τύπου</option>
									<?
										//$filter=" AND is_valid='True'";
										//$resultSensortypes = $db->sql_query("SELECT * FROM sensortypes WHERE 1=1 ".$filter." ORDER BY sensortype_name ");
										//while ($drSensortypes = $db->sql_fetchrow($resultSensortypes)){
										//	echo '<option value="'.$drSensortypes['sensortype_id'].'" '.($drSensortypes['sensortype_id']==$dr_e['sensortype_id']?' selected':'').'>'.$drSensortypes['sensortype_name'].'</option>';
										//}
									?>
								</select>
							</div>
						</div>
						-->
						
						
						<div class="form-group row">
							<label class="col-lg-3 control-label text-lg-right pt-2">Επάγγελμα</label>
							<div class="col-lg-6">
								<select name="profession_id" id="profession_id" class="form-control mb-3">
									<option value="">Επιλογή μέσου</option>
									<? 
										
										//$resultProfessions = $db->sql_query("SELECT * FROM professions WHERE 1=1 AND is_valid='True'  ORDER BY profession_name");
										$filter.=($auth->UserType != "Administrator"?' AND t1.organization_id='.$auth->UserRow['organization_id']:'');
										$resultProfessions = $db->sql_query("SELECT t2.profession_id,t2.profession_name FROM prosettings t1 INNER JOIN professions t2 ON t1.profession_id = t2.profession_id WHERE 1=1 ORDER BY t2.profession_name");
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
							<label class="col-lg-3 control-label text-lg-right pt-2" for="imei">IMEI</label>
							<div class="col-lg-6">
								<input type="text" class="form-control" id="imei" name="imei" value="<?=(isset($dr_e["imei"]) ? $dr_e["imei"]:'')?>">
							</div>
						</div>
						
						<div class="form-group row">
							<label class="col-lg-3 control-label text-lg-right pt-2" for="sensor_name">Ονομασία</label>
							<div class="col-lg-6">
								<input type="text" class="form-control" id="sensor_name" name="sensor_name" value="<?=(isset($dr_e["sensor_name"]) ? $dr_e["sensor_name"]:'')?>">
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
						<a href="index.php?com=sensors"><button type="button" class="btn btn-primary">Επιστροφή</button></a>
					</div>
				</div>
			</section>
		</div>
	</div>
	
	

	<script>
		//document.getElementById("submitBtn").disabled = true;
		function checkFields(){
		var imei = $('#imei').val();
		//var user_name = $('#user_name').val();

			if ( imei.length >= 2 ){ //&& user_name.length >= 5 && user_password.length >= 5
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
						<!-- <table class="table table-bordered table-striped mb-0" id="datatable-default">-->
						<table class="table table-responsive-lg  table-bordered table-striped mb-0" id="datatable-filters">
							<thead>
								<tr>
									<!-- <th>#</th>-->
									<th><?=active?></th>
									<th>Οργανισμός</th>
									<th>IMEI</th>
									<th>Ονομασία</th>
									<th>Επάγγελμα</th>
									<th>Node 1</th>
									<th>Node 2</th>
									<th>Node 3</th>
									<th>Node 4</th>
									<th>Node 5</th>
									<th>Ημ/νία εισαγωγής</th>
									<th>Ενέργεια</th>
								</tr>
							</thead>
							<tbody>
								<?	
									//$filter.=($auth->UserType != "Administrator"?' AND t1.region_id='.$auth->UserRow['region_id']:'');
									$query = "SELECT * FROM sensors WHERE 1=1 ".$filter." ORDER BY sensor_name ";
									$result = $db->sql_query($query);
									$counter = 0;
									while ($dr = $db->sql_fetchrow($result))
									{
										?>
											<tr>
												<!-- <td><?//=$dr["sensor_id"]?></td>-->
												<td><?=($dr["is_valid"]=='True'?'Ναι':'Οχι')?></td>
												<td>
												<?
												$drOrg=$db->RowSelectorQuery("SELECT * FROM organizations WHERE organization_id='".$dr["organization_id"]."'");
												echo $drOrg['organization_name'];
												?>
												</td>
												<td><?=$dr["imei"]?></td>
												<td><?=$dr["sensor_name"]?></td>
												<td><?
												
													if(intval($dr['profession_id'])>0) {
														$drProfession = $db->RowSelectorQuery("SELECT * FROM professions WHERE profession_id='".$dr['profession_id']."'");
														echo $drProfession['profession_name'];
													}
													
												?></td>
												<td><?=$dr["sensor1_name"]?></td>
												<td><?=$dr["sensor2_name"]?></td>
												<td><?=$dr["sensor3_name"]?></td>
												<td><?=$dr["sensor4_name"]?></td>
												<td><?=$dr["sensor5_name"]?></td>
												<td><?=$dr["date_insert"]?></td>
												<td>
													<a style="padding:4px"  href="index.php?com=sensors&Command=edit&item=<?=$dr["sensor_id"]?>"><i style="font-size:24px;" class="fas fa-edit"></i></a>
													<a href="#" onclick="ConfirmDelete('Επιβεβαίωση διαγραφής','index.php?com=sensors&Command=DELETE&item=<?=$dr["sensor_id"]?>');"><span><i style="font-size:24px;" class="fas fa-trash"></i> </a></span></a>
												</td>
											</tr>
										<?
									}
									$db->sql_freeresult($result);
								?>
							</tbody>
						</table>
						<div class="row-fluid" style="margin-top:20px;">
							<a href="index.php?com=sensors&item="><button type="button" class="btn btn-primary">Νέα εγγραφή</button></a>
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