<?php
defined( '_VALID_PROCCESS' ) or die( 'Direct Access to this location is not allowed.' );
	include($config["physicalPath"]."/languages/".$auth->LanguageCode.".php");
	include($config["physicalPath"]."/perm.php");
//require_once(dirname(__FILE__) . "/common.php");
//if(($auth->UserRow['admin_type']=='LOCAL')) {
//	Redirect("index.php");
//}
//if($auth->UserType != "Administrator") Redirect("index.php");
//if($auth->UserType != "Administrator" && !($_SESSION["permissions"] & $FLAG_300)) Redirect("index.php");
global $nav;
$nav = "Αναθέσεις";
$config["navigation"] = $nav;
$item = (intval($_GET["item"]) > 0 ? intval($_GET["item"]) : "");
$BaseUrl = "index.php?com=assignments";
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
				$PrimaryKeys["assignment_id"] = intval($_GET["item"]);
				$QuotFields["assignment_id"] = true; 
				
			} else {
				$Collector["date_insert"] = date('Y-m-d H:i:s');
				$QuotFields["date_insert"] = true;
			}

			//$Collector["is_valid"] = isset($_POST["is_valid"]) && $_POST["is_valid"] == "on" ? "True" : "False";
			//$QuotFields["is_valid"] = true;
			
			$Collector["user_id"] = $_POST["user_id"];
			$QuotFields["user_id"] = true;		
			
			$Collector["employee_id"] = $_POST["employee_id"];
			$QuotFields["employee_id"] = true;	
			
			//$Collector["organization_id"] = ($auth->UserType == "Administrator"?$_POST["organization_id"]:$auth->UserRow['organization_id']);
			//$QuotFields["organization_id"] = true;		
			
			$Collector["sensor_id"] = $_POST["sensor_id"];
			$QuotFields["sensor_id"] = true;		
						
						
			$Collector["assignment_date"] = date('Y-m-d H:i:s', strtotime($_POST["actionDate1"]));
			$QuotFields["assignment_date"] = true;
			
						
			$Collector["return_date"] = date('Y-m-d H:i:s', strtotime($_POST["actionDate2"]));
			$QuotFields["return_date"] = true;
			
			$Collector["description"] = $_POST["description"];
			$QuotFields["description"] = true;	
			
			$db->ExecuteUpdater("assignments",$PrimaryKeys,$Collector,$QuotFields);
			
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
					$db->sql_query("DELETE FROM assignments WHERE assignment_id=" . $item.$filter);
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
		$filter.=($auth->UserType != "Administrator"?' AND user_id='.$auth->UserRow['user_id'] :'');
		$query="SELECT * FROM sensors WHERE sensor_id=".$_GET['item'].$filter;
		$dr_e = $db->RowSelectorQuery($query);
		if (!isset($dr_e["assignment_id"]) && intval($_GET["item"])> 0) {
			$messages->addMessage("NOT FOUND!!!");
			Redirect("index.php?com=assignments");
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

						<? if($auth->UserType == "Administrator"){?>
						<div class="form-group row">
							<label class="col-lg-3 control-label text-lg-right pt-2">Επόπτης</label>
							<div class="col-lg-6">
								<select name="user_id" id="user_id" class="form-control mb-3">
									<!-- <option value="">Επιλογή μέσου</option>-->
									<? 
										//$filter.=($auth->UserType != "Administrator"?' AND organization_id='.$auth->UserRow['organization_id'] :'');
										$resultUsers= $db->sql_query("SELECT * FROM users WHERE 1=1 AND is_valid='True' AND role_id='2'  ORDER BY user_fullname");
										while ($drUsers = $db->sql_fetchrow($resultUsers)){
											echo '<option value="'.$drUsers['user_id'].'" '.($drUsers['user_id']==$dr_e['user_id']?' selected':'').'>'.$drUsers['user_fullname'].'</option>';
										}
									?>									
								</select>
							</div>
						</div>
						<? } ?>
					
						<div class="form-group row">
							<label class="col-lg-3 control-label text-lg-right pt-2">Υπάλληλος</label>
							<div class="col-lg-6">
								<select name="employee_id" id="employee_id" class="form-control mb-3">
									<!-- <option value="">Επιλογή μέσου</option>-->
									<? 
										$filter=($auth->UserType != "Administrator"?' AND organization_id='.$auth->UserRow['organization_id'] :'');
										$resultEmployees= $db->sql_query("SELECT * FROM employees WHERE 1=1 AND is_valid='True' ".$filter."  ORDER BY surname,firstname");
										while ($drEmployees = $db->sql_fetchrow($resultEmployees)){
											echo '<option value="'.$drEmployees['employee_id'].'" '.($drEmployees['employee_id']==$dr_e['employee_id']?' selected':'').'>'.$drEmployees['surname'].' '.$drEmployees['surname'].'</option>';
										}
									?>
									
								</select>
							</div>
						</div>
						
						<div class="form-group row">
							<label class="col-lg-3 control-label text-lg-right pt-2">Αισθητήρας</label>
							<div class="col-lg-6">
								<select name="sensor_id" id="sensor_id" class="form-control mb-3">
									<!-- <option value="">Επιλογή</option>-->
									<? 
										$filterSensor = ($auth->UserType != "Administrator"?' AND organization_id='.$auth->UserRow['organization_id'] :'');
										$filterSensor .= " AND sensor_id NOT IN (SELECT sensor_id FROM assignments WHERE ISNULL(return_date))";
										
										$resultSensor = $db->sql_query("SELECT * FROM sensors WHERE 1=1 ".$filterSensor." AND is_valid='True'  ORDER BY sensor_name");
										while ($drSensor = $db->sql_fetchrow($resultSensor)){
											echo '<option value="'.$drSensor['sensor_id'].'" '.($drSensor['sensor_id']==$dr_e['sensor_id']?' selected':'').'>'.$drSensor['sensor_name'].'</option>';
										}
									?>
								</select>
							</div>
						</div>
						
						<div class="form-group row">
							<label class="col-lg-3 control-label text-lg-right pt-2" for="datetimepicker1">Ημ/νια ανάθεσης</label>
							<div class="col-lg-6">
								<div class="form-group">
								   <div class="input-group date" id="datetimepicker1" data-target-input="nearest">
										<input type="text" name="actionDate1" id="actionDate1" value="<?=(isset($_REQUEST['actionDate1'])?$_REQUEST['actionDate1']:'')?>" class="form-control datetimepicker-input" data-target="#datetimepicker1"/>
										<div class="input-group-append" data-target="#datetimepicker1" data-toggle="datetimepicker">
											<div class="input-group-text"><i class="fa fa-calendar"></i></div>
										</div>
									</div>
								</div>
							</div>
						</div>
						
						<div class="form-group row">
							<label class="col-lg-3 control-label text-lg-right pt-2" for="datetimepicker2">Ημ/νια επιστροφής</label>
							<div class="col-lg-6">
								<div class="form-group">
								   <div class="input-group date" id="datetimepicker2" data-target-input="nearest">
										<input type="text" name="actionDate2" id="actionDate2" value="<?=(isset($_REQUEST['actionDate2'])?$_REQUEST['actionDate2']:'')?>" class="form-control datetimepicker-input" data-target="#datetimepicker2"/>
										<div class="input-group-append" data-target="#datetimepicker2" data-toggle="datetimepicker">
											<div class="input-group-text"><i class="fa fa-calendar"></i></div>
										</div>
									</div>
								</div>
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
						<a href="#" onClick="checkFields();">   <button type="button" class="btn btn-primary">Αποθήκευση</button></a>
						<a href="index.php?com=assignments"><button type="button" class="btn btn-primary">Επιστροφή</button></a>
					</div>
				</div>
			</section>
		</div>
	</div>
	
	

	<script>
		//document.getElementById("submitBtn").disabled = true;
		function checkFields(){
		var user_id = $('#user_id').val();
		var sensor_id = $('#sensor_id').val();
		var employee_id = $('#employee_id').val();

			if ( user_id.length >= 0 && sensor_id.length >= 0 && employee_id.length >= 0 ){ //&& user_name.length >= 5 && user_password.length >= 5
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
									<th>#</th>
									<th>Επόπτης</th>
									<th>Υπάλληλος</th>
									<th>Αισθητήρας</th>
									<th>IMEI</th>
									<th>Ημ/νία ανάθεσης</th>
									<!-- <th>Ημ/νία επιστροφής</th>-->
									<!-- <th>Ενέργεια</th>-->
								</tr>
							</thead>
							<tbody>
								<?	
									$filter.=($auth->UserType != "Administrator"?' AND t1.user_id IN (SELECT user_id FROM users WHERE organization_id='.$auth->UserRow['organization_id'].')':'');
									$filter.=" AND t1.return_date IS NULL";
									$filter.=(($auth->UserType != "Administrator" && $auth->UserRow['parent']>0)?' AND t3.sector_id = '.$auth->UserRow['sector_id'].' ':'');
									$query = "SELECT t1.*,t2.user_fullname, t3.surname,t3.firstname,t4.sensor_name,t4.imei
									FROM assignments t1 
									INNER JOIN users t2 ON t1.user_id=t2.user_id 
									INNER JOIN employees t3 ON t1.employee_id=t3.employee_id 
									INNER JOIN sensors t4 ON t1.sensor_id=t4.sensor_id 
									WHERE 1=1 ".$filter." ORDER BY t1.date_insert ";
							
									$result = $db->sql_query($query);
									$counter = 0;
									while ($dr = $db->sql_fetchrow($result))
									{
										?>
											<tr>
												<td><?=$dr["assignment_id"]?></td>
												<td><?=$dr["user_fullname"]?></td>
												<td><?=$dr["surname"].' '.$dr["firstname"]?></td>
												<td><?=$dr["sensor_name"]?></td>
												<td><?=$dr["imei"]?></td>
												<td><?=$dr["assignment_date"]?></td>
												<!-- <td><? //=$dr["return_date"]?></td>-->
												<!-- 
												<td>
													<a style="padding:4px"  href="index.php?com=assignments&Command=edit&item=<?//=$dr["assignment_id"]?>"><i style="font-size:24px;" class="fas fa-edit"></i></a>
													<a href="#" onclick="ConfirmDelete('Επιβεβαίωση διαγραφής','index.php?com=assignments&Command=DELETE&item=<?//=$dr["assignment_id"]?>');"><span><i style="font-size:24px;" class="fas fa-trash"></i> </a></span></a>
												</td>												
												-->

											</tr>
										<?
									}
									$db->sql_freeresult($result);
								?>
							</tbody>
						</table>
						<div class="row-fluid" style="margin-top:20px;">
							<a href="index.php?com=assignments&item="><button type="button" class="btn btn-primary">Νέα εγγραφή</button></a>
						</div>
					</div>
				</section>
			</div>
		</div>

<? } ?> 

		<script>
		// In your Javascript (external .js resource or <script> tag)
		$(document).ready(function() {
			$('#user_id').select2();
			$('#sensor_id').select2();
			$('#employee_id').select2();
		});
		</script>
		
		<script type="text/javascript">
			$(function () {
				//$("#datetimepicker1").datetimepicker({
				//  minView: 2,
				//  format: 'YYYY-MM-DD'
				//});
				//$("#datetimepicker2").datetimepicker({
				//  minView: 2,
				//  format: 'YYYY-MM-DD'
				//});
				$('#datetimepicker1').datetimepicker({
					icons: {
						time: "fa fa-clock-o",
						date: "fa fa-calendar",
						up: "fa fa-arrow-up",
						down: "fa fa-arrow-down"
					}
				});
				$('#datetimepicker2').datetimepicker({
					icons: {
						time: "fa fa-clock-o",
						date: "fa fa-calendar",
						up: "fa fa-arrow-up",
						down: "fa fa-arrow-down"
					}
				});
				
				//$("#datetimepicker1").on("change.datetimepicker", function (e) {
				//	//$('#datetimepicker2').datetimepicker('minDate', e.date);
				//	$('#datetimepicker2').datetimepicker({minDate:e.date, minView: 2,format: 'YYYY-MM-DD'});
				//});
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