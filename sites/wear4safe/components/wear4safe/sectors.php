<?php
defined( '_VALID_PROCCESS' ) or die( 'Direct Access to this location is not allowed.' );
	include($config["physicalPath"]."/languages/".$auth->LanguageCode.".php");
	include($config["physicalPath"]."/perm.php");
//require_once(dirname(__FILE__) . "/common.php");
//if(($auth->UserRow['admin_type']=='LOCAL')) {
//	Redirect("index.php");
//}
//if($auth->UserType != "Administrator") Redirect("index.php");

//if($auth->UserType != "Administrator" && $auth->UserRow['parent']>0) Redirect("index.php");
global $nav;
$nav = "Τομείς";
$config["navigation"] = $nav;
$item = (intval($_GET["item"]) > 0 ? intval($_GET["item"]) : "");
$BaseUrl = "index.php?com=sectors";
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
				$PrimaryKeys["sector_id"] = intval($_GET["item"]);
				$QuotFields["sector_id"] = true;
				
			} else {
				$Collector["date_insert"] = date('Y-m-d H:i:s');
				$QuotFields["date_insert"] = true;
			}

			$Collector["is_valid"] = isset($_POST["is_valid"]) && $_POST["is_valid"] == "on" ? "True" : "False";
			$QuotFields["is_valid"] = true;

			$Collector["organization_id"] = ($auth->UserType=='Administrator'?$_POST["organization_id"]:$auth->UserRow['organization_id']);
			$QuotFields["organization_id"] = true;		
			
			$Collector["sector_name"] = $_POST["sector_name"];
			$QuotFields["sector_name"] = true;		
			
			$Collector["description"] = $_POST["description"];
			$QuotFields["description"] = true;
	
			$db->ExecuteUpdater("sectors",$PrimaryKeys,$Collector,$QuotFields);
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
					$filter=($auth->UserType != "Administrator"?' AND organization_id = '.$auth->UserRow['organization_id']:'');
					$filter="";
					$db->sql_query("DELETE FROM sectors WHERE sector_id=" . $item.$filter);
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
	//$filter.=($auth->UserType != "Administrator"?' AND user_id IN (SELECT user_id FROM users WHERE parent='.$auth->UserId.')':'');
	
	//Πρέπει να είναι διαχειριστής του οργανισμού!!!!!!!!!! και η εγγραφή να ανοίκει στον οργανισμό του
	$filter=($auth->UserType != "Administrator"?' AND sector_id = '.$auth->UserRow['sector_id']:'');
	if(intval($_GET['item'])>0){
		$filter="";
		$query="SELECT * FROM sectors WHERE sector_id=".$_GET['item'].$filter;
		$dr_e = $db->RowSelectorQuery($query);
		if (!isset($dr_e["sector_id"]) && intval($_GET["item"])> 0) {
			$messages->addMessage("NOT FOUND!!!");
			Redirect("index.php?com=sectors");
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
						<? if($auth->UserType=='Administrator'){?>
						<div class="form-group row">
							<label class="col-lg-3 control-label text-lg-right pt-2" for="organization_id">Οργανισμός</label>
							<div class="col-lg-6">
								<select name="organization_id" id="organization_id" class="form-control mb-3">
									<option value="0">Επιλογή</option>
									<?
										$filter=" AND is_valid='True'";
										//$filter=" AND user_auth!='Administrator'";
										//$filter=" AND user_id='".$_GET['item']."'";
										//$filter=' AND user_id='.$_GET['item']; //$auth->UserId;
										$resultOrganization = $db->sql_query("SELECT * FROM organizations WHERE 1=1 ".$filter." ORDER BY organization_name ");
										while ($drOrganization = $db->sql_fetchrow($resultOrganization)){
											echo '<option value="'.$drOrganization['organization_id'].'" '.($drOrganization['organization_id']==$dr_e['organization_id']?' selected':'').'>'.$drOrganization['organization_name'].'</option>';
										}
									?>
								</select>
							</div>
						</div>						
						<? } ?>
						<div class="form-group row">
							<label class="col-lg-3 control-label text-lg-right pt-2" for="sector_name">Ονομασία</label>
							<div class="col-lg-6">
								<input type="text" class="form-control" id="sector_name" name="sector_name" value="<?=(isset($dr_e["sector_name"]) ? $dr_e["sector_name"]:'')?>">
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
					<? if($auth->UserType == "Administrator" || $auth->UserRow['parent']==0) {?>
						<a href="#" onClick="checkFields();">   <button type="button" class="btn btn-primary">Αποθήκευση</button></a>
					<? } ?>
						<a href="index.php?com=sectors"><button type="button" class="btn btn-primary">Επιστροφή</button></a>
					</div>
				</div>
			</section>
		</div>
	</div>
	
	

	<script>
		//document.getElementById("submitBtn").disabled = true;
		function checkFields(){
		var sector_name = $('#sector_name').val();
		//var user_name = $('#user_name').val();

			if ( sector_name.length >= 2 ){ //&& user_name.length >= 5 && user_password.length >= 5
					cm('SAVE',1,0,'');//document.getElementById("submitBtn").disabled = false;
			} else {
				//document.getElementById("submitBtn").disabled = true;
				alert('2 chars');
			}
		}
	</script>    
	<?
} else 	{
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
									<th><?=active?></th>
									<?=($auth->UserType == "Administrator"?'<th>Οργανισμός</th>':'')?>
									<th>Ονομασία</th>
									<th>Ημ/νία εισαγωγής</th>
									<th>Ενέργεια</th>
								</tr>
							</thead>
							<tbody>
								<?	
									$filter="";
									$filter.=($auth->UserType != "Administrator"?' AND t1.organization_id='.$auth->UserRow['organization_id']:'');
									$query = "SELECT * FROM sectors t1 INNER JOIN organizations t2 ON t1.organization_id=t2.organization_id WHERE 1=1 ".$filter." ORDER BY sector_name ";
									$result = $db->sql_query($query);
									$counter = 0;
									while ($dr = $db->sql_fetchrow($result))
									{
										?>
											<tr>
												<td><?=($dr["is_valid"]=='True'?'Ναι':'Οχι')?></td>
												<?=($auth->UserType == "Administrator"?'<td>'.$dr["organization_name"].'</td>':'')?>
												<td><?=$dr["sector_name"]?></td>
												<td><?=$dr["date_insert"]?></td>
												<td>
													<a style="padding:4px"  href="index.php?com=sectors&Command=edit&item=<?=$dr["sector_id"]?>"><i style="font-size:24px;" class="fas fa-edit"></i></a>
													<? if($auth->UserType == "Administrator" || $auth->UserRow['parent']==0) {?>
													<a href="#" onclick="ConfirmDelete('Επιβεβαίωση διαγραφής','index.php?com=sectors&Command=DELETE&item=<?=$dr["sector_id"]?>');"><span><i style="font-size:24px;" class="fas fa-trash"></i> </a></span></a>
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
						<? if($auth->UserType == "Administrator" || $auth->UserRow['parent']==0) {?>
							<a href="index.php?com=sectors&item="><button type="button" class="btn btn-primary">Νέα εγγραφή</button></a>
						<? } ?>
						</div>
					</div>
				</section>
			</div>
		</div>
<? } ?> 

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