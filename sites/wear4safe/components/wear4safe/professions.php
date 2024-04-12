<?php
defined( '_VALID_PROCCESS' ) or die( 'Direct Access to this location is not allowed.' );
	include($config["physicalPath"]."/languages/".$auth->LanguageCode.".php");
//require_once(dirname(__FILE__) . "/common.php");
//if(($auth->UserRow['admin_type']=='LOCAL')) {
//	Redirect("index.php");
//}
//if($auth->UserType != "Administrator") Redirect("index.php");

global $nav;
$nav = "Επαγγέλματα";
$config["navigation"] = $nav;
$item = (intval($_GET["item"]) > 0 ? intval($_GET["item"]) : "");
$BaseUrl = "index.php?com=professions";
$command=array();
$command=explode("&",$_POST["Command"]);

if( $auth->UserType == "Administrator" )
{
	if(isset($_REQUEST["Command"]))
	{	
		if($_REQUEST["Command"] == "SAVE")
		{
			$PrimaryKeys = array();
			$Collector = array();
			$QuotFields = array();
			
			if(isset($_GET["item"]) && $_GET["item"] != "" && intval($_GET["item"])> 0)
			{
				$PrimaryKeys["profession_id"] = intval($_GET["item"]);
				$QuotFields["profession_id"] = true;
				
			} else {
				$Collector["date_insert"] = date('Y-m-d H:i:s');
				$QuotFields["date_insert"] = true;
			}
			
			$Collector["is_valid"] = isset($_POST["is_valid"]) && $_POST["is_valid"] == "on" ? "True" : "False";
			$QuotFields["is_valid"] = true;
			
			$Collector["profession_name"] = $_POST["profession_name"];
			$QuotFields["profession_name"] = true;
			
			$Collector["law"] = $_POST["law"];
			$QuotFields["law"] = true;
			
			$Collector["description"] =  $_POST["description"]; 
			$QuotFields["description"] = true;
			
			$db->ExecuteUpdater("professions",$PrimaryKeys,$Collector,$QuotFields);
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
					$db->sql_query("DELETE FROM professions WHERE profession_id=" . $item.$filter);
					$messages->addMessage("DELETE!!!");
					Redirect($BaseUrl);
				} else {
					$messages->addMessage("Υπάρχουν συνδεδεμένες εγγραφές. Η διαγραφή δεν μπορεί να ολοκληρωθεί");
					Redirect($BaseUrl);
				}
			}
		}
	}
}

if(isset($_GET["item"])) {
	//$filter=" WHERE 1=1 AND user_auth!='Administrator '";
	//$filter.=($auth->UserType != "Administrator"?' AND organization_id = '. $auth->UserRow['organization_id']:''); //IN (SELECT contact_id FROM users WHERE parent='.$auth->UserId.')':'');
	$query="SELECT * FROM professions WHERE profession_id=".$_GET['item'].$filter;
	$dr_e = $db->RowSelectorQuery($query);
	if (!isset($dr_e["profession_id"]) && intval($_GET["item"])> 0) {
		$messages->addMessage("NOT FOUND!!!");
		Redirect("index.php?com=professions");
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
								</div>
							</div>
						</div>
						
						<div class="form-group row">
							<label class="col-lg-3 control-label text-lg-right pt-2" for="profession_name">Ονομασία</label>
							<div class="col-lg-6">
								<input type="text" class="form-control" id="profession_name" name="profession_name" value="<?=(isset($dr_e["profession_name"]) ? $dr_e["profession_name"]:'')?>">
							</div>
						</div>
						
						<div class="form-group row">
							<label class="col-lg-3 control-label text-lg-right pt-2" for="law">Νομοθέτημα</label>
							<div class="col-lg-6">
								<input type="text" class="form-control" id="law" name="law" value="<?=(isset($dr_e["law"]) ? $dr_e["law"]:'')?>">
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
						<? if($auth->UserType == "Administrator") { ?>
						<a href="#" onClick="checkFields();"><button type="button" class="btn btn-primary">Αποθήκευση</button></a>
						<? } ?>
						<a href="index.php?com=professions"><button type="button" class="btn btn-primary">Επιστροφή</button></a>
					</div>
				</div>
			</section>
		</div>
	</div>
	
	

	<script>
		//document.getElementById("submitBtn").disabled = true;
		function checkFields(){
		//var full_name = $('#full_name').val();
		//var email = $('#email').val();
		//var user_name = $('#user_name').val();
			//if ( full_name.length >= 2 && email.length >= 3 ){ //&& user_name.length >= 5 && user_password.length >= 5
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
	
	?>
	
		<div class="row">
			<div class="col">
				<section class="card">
					<header class="card-header">
						<div class="card-actions">
							<a href="#" class="card-action card-action-toggle" data-card-toggle></a>
							<a href="#" class="card-action card-action-dismiss" data-card-dismiss></a>
						</div>
		
						<h2 class="card-title">Επαγγέλματα</h2>
					</header>
					<div class="card-body">
						<!-- <table class="table table-bordered table-striped mb-0" id="datatable-default">-->
						<table class="table table-responsive-lg  table-bordered table-striped mb-0" id="datatable-filters">
							<thead>
								<tr>
									<th>#</th>
									<th>Ενεργό</th>
									<th>Ονομασία</th>
									<th>Νομοθέτημα</th>
									<th style="width:15%;"><?=action?></th>
								</tr>
							</thead>
							<tbody>
								<?	
									//$filter=" WHERE 1=1 AND user_auth!='Administrator '";
									//$filter.=($auth->UserType != "Administrator"?' AND t1.organization_id='.$auth->UserRow['organization_id']:'');
									$filter="";
									$query = "SELECT * FROM professions WHERE 1=1 ".$filter."  ";
				
									$result = $db->sql_query($query);
									$counter = 0;
									while ($dr = $db->sql_fetchrow($result))
									{
										?>
											<tr>
												
												
												<td><?=$dr["profession_id"]?></td>
												<td><?=($dr["is_valid"]=='True'?'Ναι':'Οχι')?></td>
												<td><?=$dr["profession_name"]?></td>
												<td><?=$dr["law"]?></td>
												<td>
													<a data-toggle="tooltip" data-placement="top" title="Επεξεργασία" style="padding:4px"  href="index.php?com=professions&Command=edit&item=<?=$dr["profession_id"]?>"><i style="font-size:24px;" class="fas fa-edit"></i></a>
													<a data-toggle="tooltip" data-placement="top" title="Λεπτομέρειες" style="padding:4px"  href="index.php?com=professionsmap&Command=edit&profession_id=<?=$dr["profession_id"]?>"><i style="font-size:24px;" class="fas fa-plus"></i></a>
													<? if($auth->UserType == "Administrator") { ?>
														<a  data-toggle="tooltip" data-placement="top" title="Διαγραφή" href="#" onclick="ConfirmDelete('Επιβεβαίωση διαγραφής','index.php?com=professions&Command=DELETE&item=<?=$dr["profession_id"]?>');"><span><i style="font-size:24px;" class="fas fa-trash"></i> </a></span></a>
													<? } ?>
												</td>
											</tr>
										<?
									}
									$db->sql_freeresult($result);
								?>
							</tbody>
						</table>
						<? if($auth->UserType == "Administrator") { ?>
						<div class="row-fluid" style="margin-top:20px;">
							<a href="index.php?com=professions&item="><button type="button" class="btn btn-primary">Νέα εγγραφή</button></a>
						</div>
						<? } ?>
					</div>
				</section>
			</div>
		</div>

<p class="text-center text-muted mt-3 mb-3">Development by <a target="_blank" href="http://www.dotsoft.gr/">Dotsoft</a> &copy; Copyright <?=date("Y");?>. All Rights Reserved.</p>

			
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