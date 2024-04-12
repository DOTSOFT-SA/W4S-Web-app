<?php
defined( '_VALID_PROCCESS' ) or die( 'Direct Access to this location is not allowed.' );
	include($config["physicalPath"]."/languages/".$auth->LanguageCode.".php");
	//require_once(dirname(__FILE__) . "/common.php");
	//if(($auth->UserRow['admin_type']=='LOCAL')) {
	//	Redirect("index.php");
	//}

	//echo date('Y-m-d H:i:s', strtotime("11/01/2022 9:30 AM"));
	//exit;
	//echo '<br><br>';
	//if($auth->UserType != "Administrator") Redirect("index.php");

global $nav;
$nav = "Συμβάντα";
$config["navigation"] = $nav;
$item = (intval($_GET["item"]) > 0 ? intval($_GET["item"]) : "");
$BaseUrl = "index.php?com=events";
$command=array();
$command=explode("&",$_POST["Command"]);

//if( $auth->UserType == "Administrator" )
//{
	if(isset($_REQUEST["Command"]))
	{	

	/*
	11/01/2022 9:30 AM
	echo date('Y-m-d H:i:s', strtotime("11/01/2022 9:30 AM"));
	*/
		if($_REQUEST["Command"] == "SAVE")
		{
	
			$PrimaryKeys = array();
			$Collector = array();
			$QuotFields = array();
			
			if(isset($_GET["item"]) && $_GET["item"] != "" && intval($_GET["item"])> 0)
			{
				$PrimaryKeys["event_id"] = intval($_GET["item"]);
				$QuotFields["event_id"] = true;
				
			} else {
				$Collector["date_insert"] = date('Y-m-d H:i:s');
				$QuotFields["date_insert"] = true;
			}
			
			$Collector["event_date"] = date('Y-m-d H:i:s', strtotime($_POST["actionDate1"]));
			$QuotFields["event_date"] = true;
			
			$Collector["employee_id"] = $_POST["employee_id"];
			$QuotFields["employee_id"] = true;
			
			$Collector["event_name"] = $_POST["event_name"];
			$QuotFields["event_name"] = true;

			$Collector["description"] = $_POST["description"];
			$QuotFields["description"] = true;
			
			$Collector["user_id"] = $auth->UserRow['user_id'];
			$QuotFields["user_id"] = true;
			
			$db->ExecuteUpdater("events",$PrimaryKeys,$Collector,$QuotFields);
			$messages->addMessage("SAVED!!!");
			Redirect($BaseUrl);
		} else if($_REQUEST["Command"] ==  "DELETE") { //$command[0] ==
			if($item != "")
			{
				$error=0;
				//sos να προστεθει έλεγχος διαγραφής
				
				$row = $db->RowSelectorQuery("SELECT * FROM events WHERE event_id='".$item."'");
				if(isset($row["event_id"]) && intval(item)> 0 && $row["user_id"]<>$auth->UserRow['user_id'])$error++;
				if($error==0) {	
					//$filter=($auth->UserType != "Administrator"?' AND user_id IN (SELECT user_id FROM users WHERE parent='.$auth->UserId.')':'');
					$filter="";
					$db->sql_query("DELETE FROM events WHERE event_id=" . $item.$filter);
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
	$query="SELECT * FROM events WHERE event_id=".$_GET['item'].$filter;
	$dr_e = $db->RowSelectorQuery($query);
	if (!isset($dr_e["event_id"]) && intval($_GET["item"])> 0) {
		$messages->addMessage("NOT FOUND!!!");
		Redirect("index.php?com=events");
	}
	
	if (isset($dr_e["event_id"]) && intval($_GET["item"])> 0 && $dr_e["user_id"]<>$auth->UserRow['user_id']) {
		$messages->addMessage("NOT FOUND!!!");
		Redirect("index.php?com=events");
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
							<label class="col-lg-3 control-label text-lg-right pt-2" for="datetimepicker1">Ημ/νια</label>
							<div class="col-lg-6">
								<div class="form-group">
								   <div class="input-group date" id="datetimepicker1" data-target-input="nearest">
										<input type="text" name="actionDate1" id="actionDate1" value="<?=(isset($dr_e['event_date'])?$dr_e['event_date']:'')?>" class="form-control datetimepicker-input" data-target="#datetimepicker1"/>
										<div class="input-group-append" data-target="#datetimepicker1" data-toggle="datetimepicker">
											<div class="input-group-text"><i class="fa fa-calendar"></i></div>
										</div>
									</div>
								</div>
							</div>
						</div>
						
						<div class="form-group row">
							<label class="col-lg-3 control-label text-lg-right pt-2" for="employee_id">Υπάλληλος</label>
							<div class="col-lg-6">
								<select name="employee_id" id="employee_id" class="form-control mb-3">
									<option value="0">Επιλογή</option>
									<?
										$filter=" AND is_valid='True'";
										$filter.=($auth->UserType != "Administrator"?' AND organization_id='.$auth->UserRow['organization_id']:'');
										$resultEmployees = $db->sql_query("SELECT * FROM employees WHERE 1=1 ".$filter." ORDER BY surname ");
										while ($drEmployee = $db->sql_fetchrow($resultEmployees)){
											echo '<option value="'.$drEmployee['employee_id'].'" '.($drEmployee['employee_id']==$dr_e['employee_id']?' selected':'').'>'.$drEmployee['surname'].' '.$drEmployee['firstname'].'</option>';
										}
									?>
								</select>
							</div>
						</div>	
						
						<div class="form-group row">
							<label class="col-lg-3 control-label text-lg-right pt-2" for="event_name">Τιτλος</label>
							<div class="col-lg-6">
								<input type="text" class="form-control" id="event_name" name="event_name" value="<?=(isset($dr_e["event_name"]) ? $dr_e["event_name"]:'')?>">
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
						<a href="index.php?com=dataentry"><button type="button" class="btn btn-primary">Επιστροφή</button></a>
					</div>
				</div>
			</section>
		</div>
	</div>
	
	

	<script>
		//document.getElementById("submitBtn").disabled = true;
		function checkFields(){
		//var firstname = $('#firstname').val();
		//var surname = $('#surname').val();
		//var tag_id = $('#tag_id').val();
			//if ( firstname.length >= 2 && surname.length >= 3 && tag_id.length >= 1 ){ //&& user_name.length >= 5 && user_password.length >= 5
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
		
						<h2 class="card-title"><?=$nav?></h2>
					</header>
					<div class="card-body">
						<table class="table table-responsive-lg  table-bordered table-striped mb-0" id="datatable-filters">
							<thead>
								<tr>
									<th>α/α</th>
									<th>Ημερομηνία</th>
									<th>Υπάλληλος</th>
									<th>Συμβάν</th>
									<th><?=action?></th>
								</tr>
							</thead>
							<tbody>
								<?	
									//$filter=" WHERE 1=1 AND user_auth!='Administrator '";
									//$filter.=($auth->UserType != "Administrator"?' AND parent='.$auth->UserId:' AND parent=0');
									
									
									//$filter=" AND t2.is_valid='True'";
									//$filter.=($actionDate1!=''?" AND event_date>='".$actionDate1."'":'');
									//$filter.=($actionDate2!=''?" AND event_date<='".$actionDate2."'":'');
									//$filter.= (intval($employee)>0?' AND t2.employee_id='.$employee:'');
									$filter="";
									$filter.=($auth->UserType != "Administrator"?' AND t2.organization_id='.$auth->UserRow['organization_id']:"");
									$query = "SELECT t1.*,t2.surname,t2.firstname FROM events t1 INNER JOIN employees t2 ON t1.employee_id=t2.employee_id WHERE 1=1 ".$filter." ORDER BY event_id DESC LIMIT 2000 ";
									
									$result = $db->sql_query($query);
									$counter = 0;
									while ($dr = $db->sql_fetchrow($result))
									{
										?>
											<tr>
												<td><?=$dr["event_id"]?></td>
												<td><?=$dr["event_date"]?></td>
												<td><?=$dr["surname"].' '.$dr["firstname"]?></td>
												<td><?=$dr["event_name"]?></td>
												<td>
													<a style="padding:4px"  href="index.php?com=events&Command=edit&item=<?=$dr["event_id"]?>"><i style="font-size:24px;" class="fas fa-edit"></i></a>
													<a href="#" onclick="ConfirmDelete('Επιβεβαίωση διαγραφής','index.php?com=events&Command=DELETE&item=<?=$dr["event_id"]?>');"><span><i style="font-size:24px;" class="fas fa-trash"></i> </a></span></a>
												</td>
											</tr>
										<?
									}
									$db->sql_freeresult($result);
								?>
							</tbody>
						</table>
						
						<div class="row-fluid" style="margin-top:20px;">
							<a href="index.php?com=events&item="><button type="button" class="btn btn-primary">Νέα εγγραφή</button></a>
						</div>
					</div>
				</section>
			</div>
		</div>

<p class="text-center text-muted mt-3 mb-3">Development by <a target="_blank" href="http://www.dotsoft.gr/">Dotsoft</a> &copy; Copyright 2023. All Rights Reserved.</p>
			<script type="text/javascript">
				$(function () {
					$("#datetimepicker1").datetimepicker({
					  minView: 2,
					  format: 'YYYY-MM-DD'
					});
					
					//$('#datetimepicker1').datetimepicker({
					//	icons: {
					//		time: "fa fa-clock-o",
					//		date: "fa fa-calendar",
					//		up: "fa fa-arrow-up",
					//		down: "fa fa-arrow-down"
					//	}
					//});
					
					$("#datetimepicker1").on("change.datetimepicker", function (e) {
						//$('#datetimepicker2').datetimepicker('minDate', e.date);
						$('#datetimepicker2').datetimepicker({minDate:e.date, minView: 2,format: 'YYYY-MM-DD'});
					});
				});
			</script>
			

			
<? } ?> 

<script>
	$(document).ready(function() {
		$('#employee_id').select2();
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