<?php
defined( '_VALID_PROCCESS' ) or die( 'Direct Access to this location is not allowed.' );
	include($config["physicalPath"]."/languages/".$auth->LanguageCode.".php");
//require_once(dirname(__FILE__) . "/common.php");
//if(($auth->UserRow['admin_type']=='LOCAL')) {
//	Redirect("index.php");
//}
//if($auth->UserType != "Administrator") Redirect("index.php");

global $nav;
$nav = "Μέσα ανα επάγγελμα";
$config["navigation"] = $nav;
$item = (intval($_GET["item"]) > 0 ? intval($_GET["item"]) : "");
$BaseUrl = "index.php?com=professionsmap".(intval($_POST["profession_id"])>0?"&profession_id=".$_POST["profession_id"]:'');
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
				$PrimaryKeys["professionsmap_id"] = intval($_GET["item"]);
				$QuotFields["professionsmap_id"] = true;
				
			} else {
				$Collector["date_insert"] = date('Y-m-d H:i:s');
				$QuotFields["date_insert"] = true;
			}
			
			$Collector["is_valid"] = isset($_POST["is_valid"]) && $_POST["is_valid"] == "on" ? "True" : "False";
			$QuotFields["is_valid"] = true;
			
			$Collector["profession_id"] = $_POST["profession_id"];
			$QuotFields["profession_id"] = true;
			
			$Collector["maptype_id"] = $_POST["maptype_id"];
			$QuotFields["maptype_id"] = true;
			
			$Collector["description"] =  $_POST["description"]; 
			$QuotFields["description"] = true;
			
			$db->ExecuteUpdater("professionsmap",$PrimaryKeys,$Collector,$QuotFields);
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
					$db->sql_query("DELETE FROM professionsmap WHERE professionsmap_id=" . $item.$filter); 
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
	//$filter.=($auth->UserType != "Administrator"?' AND organization_id = '. $auth->UserRow['organization_id']:''); //IN (SELECT contact_id FROM users WHERE parent='.$auth->UserId.')':'');
	$query="SELECT * FROM professionsmap WHERE professionsmap_id=".$_GET['item'].$filter;
	$dr_e = $db->RowSelectorQuery($query);
	if (!isset($dr_e["professionsmap_id"]) && intval($_GET["item"])> 0) {
		$messages->addMessage("NOT FOUND!!!");
		Redirect("index.php?com=professionsmap_id");
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
									<input type="checkbox" name="is_valid" id="is_valid" <?=((isset($dr_e["is_valid"]) && $dr_e["is_valid"]=='True') ? 'checked':'')?>>
									<label for="is_valid"></label>
									<input type="hidden" name="profession_id" id="profession_id" value="<?=$_GET['profession_id']?>">
								</div>
							</div>
						</div>
						
						<div class="form-group row">
							<label class="col-lg-3 control-label text-lg-right pt-2">Μέσο</label>
							<div class="col-lg-6">
								<select name="maptype_id" id="maptype_id" class="form-control mb-3">
									<option value="">Επιλογή μέσου</option>
									<? 
										
										$resultMaps = $db->sql_query("SELECT t1.map_name,t2.maptype_name,t2.maptype_id FROM map t1 INNER JOIN maptypes t2 ON t1.map_id=t2.map_id WHERE 1=1 AND t1.is_valid='True' AND t2.is_valid='True' ORDER BY map_name,maptype_name");
										while ($drMaps = $db->sql_fetchrow($resultMaps)){
											echo '<option value="'.$drMaps['maptype_id'].'" '.($drMaps['maptype_id']==$dr_e['maptype_id']?' selected':'').'>'.$drMaps['map_name'].'-'.$drMaps['maptype_name'].'</option>';
										}
									?>
									
								</select>
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
						<? if( $auth->UserType == "Administrator" ){ ?>
						<a href="#" onClick="checkFields();"><button type="button" class="btn btn-primary">Αποθήκευση</button></a>
						<? } ?>
						<a href="index.php?com=professionsmap"><button type="button" class="btn btn-primary">Επιστροφή</button></a>
					</div>
				</div>
			</section>
		</div>
	</div>
	
	

	<script>
		//document.getElementById("submitBtn").disabled = true;
		function checkFields(){
		//var map_name = $('#map_name').val();
		//var email = $('#email').val();
		//var user_name = $('#user_name').val();
			//if ( map_name.length >= 2  ){ //&& user_name.length >= 5 && user_password.length >= 5
					cm('SAVE',1,0,'');//document.getElementById("submitBtn").disabled = false;
			//} //else {
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
		if(intval($_GET['profession_id'])==0) Redirect("index.php?com=professions");
		$drProfession=$db->RowSelectorQuery("SELECT * FROM professions WHERE is_valid='True' AND profession_id='".$_GET['profession_id']."'");
		if(intval($drProfession['profession_id'])==0) Redirect("index.php?com=professions");
	?>
	
		<div class="row">
			<div class="col">
				<section class="card">
					<header class="card-header">
						<div class="card-actions">
							<a href="#" class="card-action card-action-toggle" data-card-toggle></a>
							<a href="#" class="card-action card-action-dismiss" data-card-dismiss></a>
						</div>
		
						<h2 class="card-title">ΜΑΠ για το επάγγελμα : <?=$drProfession['profession_name']?></h2>
					</header>
					<div class="card-body">
						<table class="table table-bordered table-striped mb-0" id="datatable-default">
							<thead>
								<tr>
									<th>#</th>
									<th>Ενεργό</th>
									<th>Κατηγορία</th>
									<th>Τύπος</th>
									<th style="width:25%;"><?=action?></th>
								</tr>
							</thead>
							<tbody>
								<?	
									//$filter=" WHERE 1=1 AND user_auth!='Administrator '";
									//$filter.=($auth->UserType != "Administrator"?' AND t1.organization_id='.$auth->UserRow['organization_id']:'');
									$filter=" AND profession_id=".$_GET['profession_id'];
									$query = "SELECT t1.professionsmap_id,t1.is_valid,t3.map_name,t2.maptype_name FROM professionsmap t1 INNER JOIN maptypes t2 ON t1.maptype_id=t2.maptype_id 
									INNER JOIN map t3 ON t2.map_id=t3.map_id WHERE 1=1 ".$filter."  ";
									$result = $db->sql_query($query);
									$counter = 0;
									while ($dr = $db->sql_fetchrow($result))
									{
										?>
											<tr>
												
												
												<td><?=$dr["professionsmap_id"]?></td>
												<td><?=$dr["is_valid"]?></td>
												<td><?=$dr["map_name"]?></td>
												<td><?=$dr["maptype_name"]?></td>
												<td>
												<? if( $auth->UserType == "Administrator" ){ ?>
													<a style="padding:4px"  href="index.php?com=professionsmap&Command=edit&item=<?=$dr["professionsmap_id"]?>"><i style="font-size:24px;" class="fas fa-edit"></i></a>
													<a href="#" onclick="ConfirmDelete('Επιβεβαίωση διαγραφής','index.php?com=professionsmap&Command=DELETE&item=<?=$dr["professionsmap_id"]?>');"><span><i style="font-size:24px;" class="fas fa-trash"></i> </a></span></a>
												<? } ?>
												</td>
											</tr>
										<?
									}
									$db->sql_freeresult($result);
								?>
							</tbody>
						</table>
						
						<div class="row-fluid" style="margin-top:20px;">
						<? if( $auth->UserType == "Administrator" ){ ?>
							<a href="index.php?com=professionsmap&profession_id=<?=$_GET['profession_id']?>&item="><button type="button" class="btn btn-primary">Νέα εγγραφή</button></a>
						<? } ?>	
							<a href="index.php?com=professions"><button type="button" class="btn btn-primary">Επιστροφή</button></a>
						</div>
					</div>
				</section>
			</div>
		</div>

<p class="text-center text-muted mt-3 mb-3">Development by <a target="_blank" href="http://www.dotsoft.gr/">Dotsoft</a> &copy; Copyright <?=date("Y");?>. All Rights Reserved.</p>

<? } ?>	
		
		<script>
		// In your Javascript (external .js resource or <script> tag)
		$(document).ready(function() {
			$('#maptype_id').select2();
		});
		</script>