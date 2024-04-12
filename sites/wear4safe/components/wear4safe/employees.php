<?php
defined( '_VALID_PROCCESS' ) or die( 'Direct Access to this location is not allowed.' );
	include($config["physicalPath"]."/languages/".$auth->LanguageCode.".php");
//require_once(dirname(__FILE__) . "/common.php");
//if(($auth->UserRow['admin_type']=='LOCAL')) {
//	Redirect("index.php");
//}
//if($auth->UserType != "Administrator") Redirect("index.php");

global $nav;
$nav = "Προσωπικό";
$config["navigation"] = $nav;
$item = (intval($_GET["item"]) > 0 ? intval($_GET["item"]) : "");
$BaseUrl = "index.php?com=employees";
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
				$PrimaryKeys["employee_id"] = intval($_GET["item"]);
				$QuotFields["employee_id"] = true;
				
			} else {
				$Collector["date_insert"] = date('Y-m-d H:i:s');
				$QuotFields["date_insert"] = true;
			}

			$Collector["is_valid"] = isset($_POST["is_valid"]) && $_POST["is_valid"] == "on" ? "True" : "False";
			$QuotFields["is_valid"] = true;
			
			$Collector["firstname"] = $_POST["firstname"];
			$QuotFields["firstname"] = true;
			
			$Collector["surname"] = $_POST["surname"];
			$QuotFields["surname"] = true;

			$Collector["email"] = $_POST["email"];
			$QuotFields["email"] = true;
			
			$Collector["profession_id"] = $_POST["profession_id"];
			$QuotFields["profession_id"] = true;
			
			$Collector["phone"] = $_POST["phone"];
			$QuotFields["phone"] = true;

			$Collector["organization_id"] = ($auth->UserType == "Administrator" ? $_POST["organization_id"]:$auth->UserRow['organization_id']);
			$QuotFields["organization_id"] = true;

			$Collector["sector_id"] = $_POST["sector_id"];
			$QuotFields["sector_id"] = true;
			
			$Collector["description"] =  $_POST["description"]; 
			$QuotFields["description"] = true;
			
			$db->ExecuteUpdater("employees",$PrimaryKeys,$Collector,$QuotFields);
			$messages->addMessage("SAVED!!!");
			Redirect($BaseUrl);
		} else if($_REQUEST["Command"] ==  "DELETE") { //$command[0] ==
			if($item != "")
			{
				$error=0;
				//sos να προστεθει έλεγχος διαγραφής
				if($auth->UserType != "Administrator") {
					//Δεν μπορώ να σβήσω υπαλλήλους που δεν βρίσκονται στον οργανισμό μου
					$result = $db->sql_query('SELECT * FROM employees WHERE employee_id='.$item.' AND organization_id='.$auth->UserRow['organization_id']);
					if($db->sql_numrows($result) == 0) $error++;
				}
				//$result = $db->sql_query('SELECT * FROM messages WHERE user_id='.$item);
				//if($db->sql_numrows($result) > 0) $error++;
				if($error==0) {	
					//$filter=($auth->UserType != "Administrator"?' AND user_id IN (SELECT user_id FROM users WHERE parent='.$auth->UserId.')':'');
					$filter="";
					$db->sql_query("DELETE FROM employees WHERE employee_id=" . $item.$filter);
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
	//$filter.=($auth->UserType != "Administrator"?' AND location_id IN (SELECT location_id FROM locations WHERE region_id='.$auth->UserRow['region_id'].')':'');
	$filter.=($auth->UserType != "Administrator"?' AND organization_id = '.$auth->UserRow['organization_id']:'');
	$query="SELECT * FROM employees WHERE employee_id=".$_GET['item'].$filter;
	$dr_e = $db->RowSelectorQuery($query);
	if (!isset($dr_e["employee_id"]) && intval($_GET["item"])> 0) {
		$messages->addMessage("NOT FOUND!!!");
		Redirect("index.php?com=employees");
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
							<label class="col-lg-3 control-label text-lg-right pt-2" for="surname">Επώνυμο</label>
							<div class="col-lg-6">
								<input type="text" class="form-control" id="surname" name="surname" value="<?=(isset($dr_e["surname"]) ? $dr_e["surname"]:'')?>">
							</div>
						</div>
						<div class="form-group row">
							<label class="col-lg-3 control-label text-lg-right pt-2" for="firstname">Ονομα</label>
							<div class="col-lg-6">
								<input type="text" class="form-control" id="firstname" name="firstname" value="<?=(isset($dr_e["firstname"]) ? $dr_e["firstname"]:'')?>">
							</div>
						</div>  

						
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
						
						
						<div class="form-group row">
							<label class="col-lg-3 control-label text-lg-right pt-2" for="profession_id">Ειδικότητα</label>
							<div class="col-lg-6">
								<select name="profession_id" id="profession_id" class="form-control mb-3">
									<option value="0">Επιλογή</option>
									<?
										$filter=" AND is_valid='True'";
										//$filter.=($auth->UserType != "Administrator"?' AND region_id='.$auth->UserRow['region_id']:'');
										$resultProf = $db->sql_query("SELECT * FROM professions WHERE 1=1 ".$filter." ORDER BY profession_name ");
										while ($drProf = $db->sql_fetchrow($resultProf)){
											echo '<option value="'.$drProf['profession_id'].'" '.($drProf['profession_id']==$dr_e['profession_id']?' selected':'').'>'.$drProf['profession_name'].'</option>';
										}
									?>
								</select>
							</div>
						</div>	
						
						
						
						<div class="form-group row">
							<label class="col-lg-3 control-label text-lg-right pt-2" for="email">email</label>
							<div class="col-lg-6">
								<input type="text" class="form-control" id="email" name="email" value="<?=(isset($dr_e["email"]) ? $dr_e["email"]:'')?>">
							</div>
						</div>
					
						<div class="form-group row">
							<label class="col-lg-3 control-label text-lg-right pt-2" for="user_name">Τηλέφωνο</label>
							<div class="col-lg-6">
								<input type="text" class="form-control" id="phone" name="phone" value="<?=(isset($dr_e["phone"]) ? $dr_e["phone"]:'')?>">
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
					<? if($auth->UserType == "Administrator" || $auth->UserRow['parent']==0) { ?>
						<a href="#" onClick="checkFields();"><button type="button" class="btn btn-primary">Αποθήκευση</button></a>
					<? } ?>
						<a href="index.php?com=employees"><button type="button" class="btn btn-primary">Επιστροφή</button></a>
					</div>
				</div>
			</section>
		</div>
	</div>
	
	<script>
		//document.getElementById("submitBtn").disabled = true;
		function checkFields(){
		var firstname = $('#firstname').val();
		var surname = $('#surname').val();
		
			if ( firstname.length >= 2 && surname.length >= 3){ //&& user_name.length >= 5 && user_password.length >= 5
					cm('SAVE',1,0,'');//document.getElementById("submitBtn").disabled = false;
			} //else {
				//document.getElementById("submitBtn").disabled = true;
				//alert('2 chars');
			//}
		}
	</script>    
	<?
} else if(isset($_GET['employee'])){	
	if(!isset($_POST["xlsSubmit"])){
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
						<h2 class="card-title">Προσωπικό</h2>
					</header>
					<div class="card-body">
					
						<div class="row" style="margin-bottom:20px;">
							<div class='col-md-4'>
							</div>
							<div class='col-md-4'></div>

							<div class='col-md-4' style="text-align:right;">
								<div class="form-group">
									<button type="submit" name="xlsSubmit" id="xlsSubmit" class="btn btn-primary">Εξαγωγή σε XLS</button>
								</div>
							</div>

							<script type="text/javascript">
								$(function () {
									$('#datetimepicker1').datetimepicker({
										icons: {
											time: "fa fa-clock-o",
											date: "fa fa-calendar",
											up: "fa fa-arrow-up",
											down: "fa fa-arrow-down"
										}
									});
									
									$("#datetimepicker1").on("change.datetimepicker", function (e) {
										$('#datetimepicker2').datetimepicker('minDate', e.date);
									});
								});
							</script>
						</div>

						<!-- <table class="table table-bordered table-striped mb-0" id="datatable-default">-->
						<table class="table table-responsive-lg  table-bordered table-striped mb-0" id="datatable-filters">
							<thead>
								<tr>
									
									<th>Ημερομηνία</th>
									<th>Δεδομένα</th>
									<th>Ειδοποιήσεις</th>
									
									<th>Δείκτης</th>
								</tr>
							</thead>
							<tbody>
								<?	
									//$filter=" WHERE 1=1 AND user_auth!='Administrator '";
									$sensorRow = $db->RowSelectorQuery("SELECT * FROM assignments WHERE employee_id='".$_GET['employee']."' ORDER BY date_insert DESC LIMIT 1");
									$query="SELECT sensor_id,concat(dtServer,' ',hDtServer,':00:00') AS hdtServer,dataID,alertID FROM (SELECT concat(sensor_id,'_',date(dt_server),'_',hour(dt_server)) AS index1,t1.sensor_id,date(t1.dt_server) AS dtServer,hour(t1.dt_server) AS hDtServer,count(t1.alert_id) AS dataID FROM data t1 GROUP BY sensor_id,date(t1.dt_server),hour(t1.dt_server)) tt1 INNER JOIN (SELECT concat(sensor_id,'_',date(dt_server),'_',hour(dt_server)) AS index2,count(t2.alert_id) AS alertID FROM alerts t2 GROUP BY sensor_id,date(t2.dt_server),hour(dt_server)) tt2 ON tt1.index1=tt2.index2 WHERE sensor_id='".$sensorRow['sensor_id']."'";
									
									//$query = "SELECT sensor_id,dtServer,dataID,alertID FROM (SELECT concat(sensor_id,'_',date(dt_server)) AS index1,t1.sensor_id,date(t1.dt_server) AS dtServer,count(t1.alert_id) AS dataID FROM data t1 GROUP BY sensor_id,date(t1.dt_server)) tt1 INNER JOIN (SELECT concat(sensor_id,'_',date(dt_server)) AS index2,count(t2.alert_id) AS alertID FROM alerts t2 GROUP BY sensor_id,date(t2.dt_server)) tt2 ON tt1.index1=tt2.index2 
									//WHERE sensor_id='".$sensorRow['sensor_id']."'";
									
									//$filter.=($auth->UserType != "Administrator"?' AND t1.organization_id='.$auth->UserRow['organization_id']:'');
									//$filter.=(($auth->UserType != "Administrator" && $auth->UserRow['parent']>0)?' AND t1.sector_id='.$auth->UserRow['sector_id']:'');
									//if($auth->UserType == "Administrator" || $auth->UserRow['parent']==0)
									//$query = "SELECT t1.*,t2.organization_name FROM employees t1 INNER JOIN organizations t2 ON t1.organization_id=t2.organization_id ".$filter." ORDER BY surname ";
									$result = $db->sql_query($query);
									$counter = 0;
									while ($dr = $db->sql_fetchrow($result))
									{
										?>
											<tr>
												<td><?=$dr["hdtServer"]?></td>
												<td><?=$dr["dataID"]?></td>
												<td><?=$dr["alertID"]?></td>
												 <!-- Πόσο δεν φορούσε ΜΑΠ μέσα στην ώρα -->
												<td><?
												if((100*(($dr["alertID"]/$dr["dataID"])))<40) echo 'Χαμηλή';
												if(100*(($dr["alertID"]/$dr["dataID"]))>40 && 100*(($dr["alertID"]/$dr["dataID"]))<70 ) echo 'Μεσαία';
												if((100*(($dr["alertID"]/$dr["dataID"])))>70) echo 'Υψηλή';
												?></td>
											</tr>
										<?
									}
									$db->sql_freeresult($result);
								?>
							</tbody>
						</table>
						<div class="row-fluid" style="margin-top:20px;">
							<a href="index.php?com=employees"><button type="button" class="btn btn-primary">Επιστροφή</button></a>
						</div>
					</div>
				</section>
			</div>
		</div>

		<p class="text-center text-muted mt-3 mb-3">Development by <a target="_blank" href="http://www.dotsoft.gr/">Dotsoft</a> &copy; Copyright <?=date("Y");?>. All Rights Reserved.</p>

			
<? 
	}  else {
	$commandCode='Εξαγωγή στατιστικών χαμηλής επικινδυνότητας';
	$commandShortName='Στατιστικά χαμηλής επικινδυνότητας';
	
	//$region=$_REQUEST["r"];
	//$filter = ($auth->UserType!='Administrator'?" AND region_id=".$auth->UserRow['region_id']:'');
	
	
	//$refid=$_REQUEST["categ3"];
	//$datefrom=$_REQUEST["datefrom"];
	//$dateto=$_REQUEST["dateto"];
	//$filter.=" AND t1.sensor_id=".$refid;
	//$filter.= (intval($_REQUEST["r"])>0?" AND t3.region_id=".intval($_REQUEST["r"]):'');	
	
	//$datefromSql =  substr($datefrom,6,4).'-'.substr($datefrom,0,2).'-'.substr($datefrom,3,2);
	//$datetoSql =  substr($dateto,6,4).'-'.substr($dateto,0,2).'-'.substr($dateto,3,2);
	
	//$filter.=" AND t1.date_insert>='".$datefromSql."'";
	//$filter.=" AND t1.date_insert<='".$datetoSql."'"; //2021-01-30 08:22:57
	
	//$query = "SELECT t1.measurement,t1.date_insert,t2.sensorvar_description,t2.sensorvar_unit,t2.sensorvar_dec FROM measurements t1 
	//INNER JOIN sensorvars t2 ON t1.parameter_id=t2.sensorvar_id 
	//INNER JOIN mysensors t3 ON t1.sensor_id= t3.ref_id
	//WHERE 1=1 " .$filter." ORDER BY t1.date_insert";
	
	$filter.=($auth->UserType != "Administrator"?' AND t1.region_id='.$auth->UserRow['region_id']:'');
	$query = "SELECT t1.*,t2.location_name FROM employees t1 LEFT JOIN locations t2 ON t1.location_id=t2.location_id ".$filter." ORDER BY surname ";
	
	
	/*
	SELECT t1.measurement,t1.date_insert,t2.sensorvar_description,t2.sensorvar_unit,t2.sensorvar_dec FROM measurements t1 INNER JOIN sensorvars t2 ON t1.parameter_id=t2.sensorvar_id 
	INNER JOIN mysensors t3 ON t1.sensor_id= t3.ref_id WHERE 1=1 AND t1.date_insert>=06/01/2021 
	AND t1.date_insert<=06/08/2021 ORDER BY t1.date_insert

	SELECT t1.measurement,t1.date_insert,t2.sensorvar_description,t2.sensorvar_unit,t2.sensorvar_dec 
	FROM measurements t1 INNER JOIN sensorvars t2 ON t1.parameter_id=t2.sensorvar_id 
	INNER JOIN mysensors t3 ON t1.sensor_id= t3.ref_id WHERE 1=1 ORDER BY t1.date_insert		
	*/


	$result = $db->sql_query($query);

	//error_reporting(E_ALL);
	//ini_set('display_errors', FALSE);
	//ini_set('display_startup_errors', FALSE);
	error_reporting(0);
	date_default_timezone_set('Europe/London');

	if (PHP_SAPI == 'cli')
		die('This example should only be run from a Web Browser');

	/** Include PHPExcel */
	require_once dirname(__FILE__) . '/Classes/PHPExcel.php';


	// Create new PHPExcel object
	$objPHPExcel = new PHPExcel();

	// Create new PHPExcel object
	$objPHPExcel = new PHPExcel();

	// Set properties
	$objPHPExcel->getProperties()->setCreator("YourPoint OOD") ->setLastModifiedBy("Dimitrios Iordanidis")
									 ->setTitle($commandCode." xls export")	 ->setSubject($commandCode." xls export")
									 ->setDescription($commandCode." xls export made by ViewPanel Suite (2018)") 
									 ->setKeywords($commandCode." XLS export")
									 ->setCategory("Reports");


	$firstLine = $line = 5; $sheet=0; $record=1;
	$lastcolumn = 'G';
	// Set active sheet
	$objPHPExcel->setActiveSheetIndex($sheet);
	
	// Rename sheet
	$sheetTitle = $commandCode;
	$objPHPExcel->getActiveSheet()->setTitle($sheetTitle);

	// Set header and footer. When no different headers for odd/even are used, odd header is assumed.
	$objPHPExcel->getActiveSheet()->getHeaderFooter()->setOddHeader(''); //Validation
	$objPHPExcel->getActiveSheet()->getHeaderFooter()->setOddFooter('&L&B' . $objPHPExcel->getProperties()->getTitle() . '&RPage &P of &N');

	// Set page orientation and size
	$objPHPExcel->getActiveSheet()->getPageSetup()->setOrientation(PHPExcel_Worksheet_PageSetup::ORIENTATION_PORTRAIT);
	$objPHPExcel->getActiveSheet()->getPageSetup()->setPaperSize(PHPExcel_Worksheet_PageSetup::PAPERSIZE_A4);

	 //Title
	$objPHPExcel->getActiveSheet()->mergeCells ('A1:'.$lastcolumn.'1');
	$objPHPExcel->getActiveSheet()->getStyle('A1:A1')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
	$objPHPExcel->getActiveSheet()->setCellValue('A1', $commandShortName);
	
	//Subtitle
	$objPHPExcel->getActiveSheet()->mergeCells ('A2:'.$lastcolumn.'2');
	$objPHPExcel->getActiveSheet()->setCellValue('A2', PHPExcel_Shared_Date::PHPToExcel( gmmktime(0,0,0,date('m'),date('d'),date('Y')) ));
	$objPHPExcel->getActiveSheet()->getStyle('A2')->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_DATE_XLSX15);
	//$objPHPExcel->getActiveSheet()->getStyle('E:E')->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_GENERAL);
	$objPHPExcel->getActiveSheet()->setAutoFilter('A4:J4');
	// Set column titles and columnn widths (0 : Auto)
	$columns = array(
		 'A'=>array('#', 8)
		,'B'=>array('Ενεργό', 15)
		,'C'=>array('Επώνυμο', 30)
		,'D'=>array('Ονομα', 30)
		,'E'=>array('Οργανισμός', 30)
		,'F'=>array('email', 25)
		,'G'=>array('Τηλέφωνο', 20)
	);

	// Write titles
	foreach($columns as $key=>$value) {
		$objPHPExcel->getActiveSheet()->setCellValue($key.'4', $value[0]);
		if ($value[1]>0)
			$objPHPExcel->getActiveSheet()->getColumnDimension($key)->setWidth($value[1]);
		else
			$objPHPExcel->getActiveSheet()->getColumnDimension($key)->setAutoSize(true);
	}
 
	// Set style for header row using alternative method
	$objPHPExcel->getActiveSheet()->getStyle('A4:'.$lastcolumn.'4')->applyFromArray(
		array(
			'font'    => array(	'bold'      => true	),
			'alignment' => array('horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_LEFT	),
			'borders' => array(	'top' => array('style' => PHPExcel_Style_Border::BORDER_THIN)),
			'fill' => array(
			'type' => PHPExcel_Style_Fill::FILL_GRADIENT_LINEAR,
			'rotation'   => 90,	
			'startcolor' => array('argb' => 'FFeeeeee'),
			'endcolor' => array('argb' => 'FFeeeeee')
			)
		)
	);
	   	  
		  
	$objPHPExcel->getActiveSheet()->getStyle('E4:E10000')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_LEFT);
	$objPHPExcel->getActiveSheet()->getStyle('F4:F10000')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_LEFT);
	$objPHPExcel->getActiveSheet()->getStyle('G4:G10000')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_LEFT);
	$objPHPExcel->getActiveSheet()->getStyle('J4:J10000')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);
		  
	$objPHPExcel->getActiveSheet()->getStyle('A1:A2')->applyFromArray(
		 array(
			'font' => array(
			'name' => 'Arial',
			'size' => 18,
			'bold' => true,
			'color' => array(
			'rgb' => '000000'
			),	
		 ),
			'fill'=>array(
			 'type'       => PHPExcel_Style_Fill::FILL_SOLID,
			 'rotation'   => 0,
			 'color' => array(
			  'rgb' => 'eeeeee'
			 ))
		 )
	);		


	$objPHPExcel->getActiveSheet()->getStyle('A2')->getFont()->setSize(10);
	$objPHPExcel->getActiveSheet()->getRowDimension(1)->setRowHeight(50);
  
	// Add a drawing to the worksheet
	//$objDrawing = new PHPExcel_Worksheet_Drawing();
	//$objDrawing->setName('Logo');
	//$objDrawing->setDescription('Logo');
	//$objDrawing->setPath('./img/dio_logo.png');
	//$objDrawing->setHeight(80);
	//$objDrawing->setWorksheet($objPHPExcel->getActiveSheet());
	
	$rowID = 0;
	$line=5;

	while ($dr = $db->sql_fetchrow($result))
	{	
		$rowID++;
		$objPHPExcel->getActiveSheet()->setCellValue('A'.$line, $rowID);
		$objPHPExcel->getActiveSheet()->setCellValue('B'.$line, ($dr["is_valid"]=='True'?'Ναι':'Οχι'));
		$objPHPExcel->getActiveSheet()->setCellValue('C'.$line, $dr["surname"]);
		$objPHPExcel->getActiveSheet()->setCellValue('D'.$line, $dr['firstname']);
		$objPHPExcel->getActiveSheet()->setCellValue('E'.$line, $dr['organization_name']);
		$objPHPExcel->getActiveSheet()->setCellValue('F'.$line, $dr['email']);
		$objPHPExcel->getActiveSheet()->setCellValue('G'.$line, $dr['phone']);
		$line++;
	}
	
	// Rename worksheet
	$objPHPExcel->getActiveSheet()->setTitle($commandCode);


	// Set active sheet index to the first sheet, so Excel opens this as the first sheet
	$objPHPExcel->setActiveSheetIndex(0);


	// Redirect output to a client’s web browser (Excel5)
	header('Content-Type: application/vnd.ms-excel');
	header('Content-Disposition: attachment;filename="'.$commandCode.'-'.date("d-m-Y").'.xls"');
	header('Cache-Control: max-age=0');
	// If you're serving to IE 9, then the following may be needed
	header('Cache-Control: max-age=1');

	// If you're serving to IE over SSL, then the following may be needed
	header ('Expires: Mon, 26 Jul 1997 05:00:00 GMT'); // Date in the past
	header ('Last-Modified: '.gmdate('D, d M Y H:i:s').' GMT'); // always modified
	header ('Cache-Control: cache, must-revalidate'); // HTTP/1.1
	header ('Pragma: public'); // HTTP/1.0

	$objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel5');
	$objWriter->save('php://output');
	exit;
}

	
} else 	{
	if(!isset($_POST["xlsSubmit"])){
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
						<h2 class="card-title">Προσωπικό</h2>
					</header>
					<div class="card-body">
					
						<div class="row" style="margin-bottom:20px;">
							<div class='col-md-4'>
							</div>
							<div class='col-md-4'></div>

							<div class='col-md-4' style="text-align:right;">
								<div class="form-group">
									<button type="submit" name="xlsSubmit" id="xlsSubmit" class="btn btn-primary">Εξαγωγή σε XLS</button>
								</div>
							</div>

							<script type="text/javascript">
								$(function () {
									$('#datetimepicker1').datetimepicker({
										icons: {
											time: "fa fa-clock-o",
											date: "fa fa-calendar",
											up: "fa fa-arrow-up",
											down: "fa fa-arrow-down"
										}
									});
									
									$("#datetimepicker1").on("change.datetimepicker", function (e) {
										$('#datetimepicker2').datetimepicker('minDate', e.date);
									});
								});
							</script>
						</div>

						<!-- <table class="table table-bordered table-striped mb-0" id="datatable-default">-->
						<table class="table table-responsive-lg  table-bordered table-striped mb-0" id="datatable-filters">
							<thead>
								<tr>
									<th><?=active?></th>
									<th>Επώνυμο</th>
									<th>Ονομα</th>
									<th>PIN</th>
									<th>Ειδικότητα</th>
									<th>Οργανισμός</th>
									<th>email</th>
									<th>Τηλέφωνο</th>
									<th><?=action?></th>
								</tr>
							</thead>
							<tbody>
								<?	
									//$filter=" WHERE 1=1 AND user_auth!='Administrator '";
									$filter.=($auth->UserType != "Administrator"?' AND t1.organization_id='.$auth->UserRow['organization_id']:'');
									$filter.=(($auth->UserType != "Administrator" && $auth->UserRow['parent']>0)?' AND t1.sector_id='.$auth->UserRow['sector_id']:'');
									//if($auth->UserType == "Administrator" || $auth->UserRow['parent']==0)
									$query = "SELECT t1.*,t2.organization_name FROM employees t1 INNER JOIN organizations t2 ON t1.organization_id=t2.organization_id ".$filter." ORDER BY surname ";
									$result = $db->sql_query($query);
									$counter = 0;
									while ($dr = $db->sql_fetchrow($result))
									{
										?>
											<tr>
												<td><?=($dr["is_valid"]=='True'?'Ναι':'Οχι')?></td>
												<td><?=$dr["surname"]?></td>
												<td><?=$dr["firstname"]?></td>
												<td><?=str_pad(strval($dr["employee_id"]),4,'0',STR_PAD_LEFT)?></td>
												<td><?
												$drProf=$db->RowSelectorQuery("SELECT * FROM professions WHERE profession_id='".$dr["profession_id"]."'");
												echo $drProf["profession_name"];
												?></td>
												<td><?=$dr["organization_name"]?></td>
												<td><?=$dr["email"]?></td>
												<td><?=$dr["phone"]?></td>
												<td>
													<a data-toggle="tooltip" data-placement="top" title="Επεξεργασία" style="padding:4px"  href="index.php?com=employees&Command=edit&item=<?=$dr["employee_id"]?>"><i style="font-size:24px;" class="fas fa-edit"></i></a>
													<a data-toggle="tooltip" data-placement="top" title="Χρήση" style="padding:4px"  href="index.php?com=employees&Command=stats&employee=<?=$dr["employee_id"]?>"><i style="font-size:24px;" class="fas fa-plus"></i></a>
													
													<? if($auth->UserType == "Administrator" || $auth->UserRow['parent']==0) { ?>
														<a data-toggle="tooltip" data-placement="top" href="#" onclick="ConfirmDelete('Επιβεβαίωση διαγραφής','index.php?com=employees&Command=DELETE&item=<?=$dr["employee_id"]?>');"><span><i style="font-size:24px;" class="fas fa-trash"></i> </a></span></a>
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
						<? if($auth->UserType == "Administrator" || $auth->UserRow['parent']==0) { ?>
							<a href="index.php?com=employees&item="><button type="button" class="btn btn-primary">Νέα εγγραφή</button></a>
						<? } ?>
						</div>
					</div>
				</section>
			</div>
		</div>

		<p class="text-center text-muted mt-3 mb-3">Development by <a target="_blank" href="http://www.dotsoft.gr/">Dotsoft</a> &copy; Copyright <?=date("Y");?>. All Rights Reserved.</p>

			
<? 
	}  else {
	$commandCode='Εξαγωγή λίστας Προσωπικου';
	$commandShortName='Προσωπικό';
	
	//$region=$_REQUEST["r"];
	//$filter = ($auth->UserType!='Administrator'?" AND region_id=".$auth->UserRow['region_id']:'');
	
	
	//$refid=$_REQUEST["categ3"];
	//$datefrom=$_REQUEST["datefrom"];
	//$dateto=$_REQUEST["dateto"];
	//$filter.=" AND t1.sensor_id=".$refid;
	//$filter.= (intval($_REQUEST["r"])>0?" AND t3.region_id=".intval($_REQUEST["r"]):'');	
	
	//$datefromSql =  substr($datefrom,6,4).'-'.substr($datefrom,0,2).'-'.substr($datefrom,3,2);
	//$datetoSql =  substr($dateto,6,4).'-'.substr($dateto,0,2).'-'.substr($dateto,3,2);
	
	//$filter.=" AND t1.date_insert>='".$datefromSql."'";
	//$filter.=" AND t1.date_insert<='".$datetoSql."'"; //2021-01-30 08:22:57
	
	//$query = "SELECT t1.measurement,t1.date_insert,t2.sensorvar_description,t2.sensorvar_unit,t2.sensorvar_dec FROM measurements t1 
	//INNER JOIN sensorvars t2 ON t1.parameter_id=t2.sensorvar_id 
	//INNER JOIN mysensors t3 ON t1.sensor_id= t3.ref_id
	//WHERE 1=1 " .$filter." ORDER BY t1.date_insert";
	
	$filter.=($auth->UserType != "Administrator"?' AND t1.region_id='.$auth->UserRow['region_id']:'');
	$query = "SELECT t1.*,t2.location_name FROM employees t1 LEFT JOIN locations t2 ON t1.location_id=t2.location_id ".$filter." ORDER BY surname ";
	
	
	/*
	SELECT t1.measurement,t1.date_insert,t2.sensorvar_description,t2.sensorvar_unit,t2.sensorvar_dec FROM measurements t1 INNER JOIN sensorvars t2 ON t1.parameter_id=t2.sensorvar_id 
	INNER JOIN mysensors t3 ON t1.sensor_id= t3.ref_id WHERE 1=1 AND t1.date_insert>=06/01/2021 
	AND t1.date_insert<=06/08/2021 ORDER BY t1.date_insert

	SELECT t1.measurement,t1.date_insert,t2.sensorvar_description,t2.sensorvar_unit,t2.sensorvar_dec 
	FROM measurements t1 INNER JOIN sensorvars t2 ON t1.parameter_id=t2.sensorvar_id 
	INNER JOIN mysensors t3 ON t1.sensor_id= t3.ref_id WHERE 1=1 ORDER BY t1.date_insert		
	*/


	$result = $db->sql_query($query);

	//error_reporting(E_ALL);
	//ini_set('display_errors', FALSE);
	//ini_set('display_startup_errors', FALSE);
	error_reporting(0);
	date_default_timezone_set('Europe/London');

	if (PHP_SAPI == 'cli')
		die('This example should only be run from a Web Browser');

	/** Include PHPExcel */
	require_once dirname(__FILE__) . '/Classes/PHPExcel.php';


	// Create new PHPExcel object
	$objPHPExcel = new PHPExcel();

	// Create new PHPExcel object
	$objPHPExcel = new PHPExcel();

	// Set properties
	$objPHPExcel->getProperties()->setCreator("YourPoint OOD") ->setLastModifiedBy("Dimitrios Iordanidis")
									 ->setTitle($commandCode." xls export")	 ->setSubject($commandCode." xls export")
									 ->setDescription($commandCode." xls export made by ViewPanel Suite (2018)") 
									 ->setKeywords($commandCode." XLS export")
									 ->setCategory("Reports");


	$firstLine = $line = 5; $sheet=0; $record=1;
	$lastcolumn = 'G';
	// Set active sheet
	$objPHPExcel->setActiveSheetIndex($sheet);
	
	// Rename sheet
	$sheetTitle = $commandCode;
	$objPHPExcel->getActiveSheet()->setTitle($sheetTitle);

	// Set header and footer. When no different headers for odd/even are used, odd header is assumed.
	$objPHPExcel->getActiveSheet()->getHeaderFooter()->setOddHeader(''); //Validation
	$objPHPExcel->getActiveSheet()->getHeaderFooter()->setOddFooter('&L&B' . $objPHPExcel->getProperties()->getTitle() . '&RPage &P of &N');

	// Set page orientation and size
	$objPHPExcel->getActiveSheet()->getPageSetup()->setOrientation(PHPExcel_Worksheet_PageSetup::ORIENTATION_PORTRAIT);
	$objPHPExcel->getActiveSheet()->getPageSetup()->setPaperSize(PHPExcel_Worksheet_PageSetup::PAPERSIZE_A4);

	 //Title
	$objPHPExcel->getActiveSheet()->mergeCells ('A1:'.$lastcolumn.'1');
	$objPHPExcel->getActiveSheet()->getStyle('A1:A1')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
	$objPHPExcel->getActiveSheet()->setCellValue('A1', $commandShortName);
	
	//Subtitle
	$objPHPExcel->getActiveSheet()->mergeCells ('A2:'.$lastcolumn.'2');
	$objPHPExcel->getActiveSheet()->setCellValue('A2', PHPExcel_Shared_Date::PHPToExcel( gmmktime(0,0,0,date('m'),date('d'),date('Y')) ));
	$objPHPExcel->getActiveSheet()->getStyle('A2')->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_DATE_XLSX15);
	//$objPHPExcel->getActiveSheet()->getStyle('E:E')->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_GENERAL);
	$objPHPExcel->getActiveSheet()->setAutoFilter('A4:J4');
	// Set column titles and columnn widths (0 : Auto)
	$columns = array(
		 'A'=>array('#', 8)
		,'B'=>array('Ενεργό', 15)
		,'C'=>array('Επώνυμο', 30)
		,'D'=>array('Ονομα', 30)
		,'E'=>array('Οργανισμός', 30)
		,'F'=>array('email', 25)
		,'G'=>array('Τηλέφωνο', 20)
	);

	// Write titles
	foreach($columns as $key=>$value) {
		$objPHPExcel->getActiveSheet()->setCellValue($key.'4', $value[0]);
		if ($value[1]>0)
			$objPHPExcel->getActiveSheet()->getColumnDimension($key)->setWidth($value[1]);
		else
			$objPHPExcel->getActiveSheet()->getColumnDimension($key)->setAutoSize(true);
	}
 
	// Set style for header row using alternative method
	$objPHPExcel->getActiveSheet()->getStyle('A4:'.$lastcolumn.'4')->applyFromArray(
		array(
			'font'    => array(	'bold'      => true	),
			'alignment' => array('horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_LEFT	),
			'borders' => array(	'top' => array('style' => PHPExcel_Style_Border::BORDER_THIN)),
			'fill' => array(
			'type' => PHPExcel_Style_Fill::FILL_GRADIENT_LINEAR,
			'rotation'   => 90,	
			'startcolor' => array('argb' => 'FFeeeeee'),
			'endcolor' => array('argb' => 'FFeeeeee')
			)
		)
	);
	   	  
		  
	$objPHPExcel->getActiveSheet()->getStyle('E4:E10000')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_LEFT);
	$objPHPExcel->getActiveSheet()->getStyle('F4:F10000')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_LEFT);
	$objPHPExcel->getActiveSheet()->getStyle('G4:G10000')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_LEFT);
	$objPHPExcel->getActiveSheet()->getStyle('J4:J10000')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);
		  
	$objPHPExcel->getActiveSheet()->getStyle('A1:A2')->applyFromArray(
		 array(
			'font' => array(
			'name' => 'Arial',
			'size' => 18,
			'bold' => true,
			'color' => array(
			'rgb' => '000000'
			),	
		 ),
			'fill'=>array(
			 'type'       => PHPExcel_Style_Fill::FILL_SOLID,
			 'rotation'   => 0,
			 'color' => array(
			  'rgb' => 'eeeeee'
			 ))
		 )
	);		


	$objPHPExcel->getActiveSheet()->getStyle('A2')->getFont()->setSize(10);
	$objPHPExcel->getActiveSheet()->getRowDimension(1)->setRowHeight(50);
  
	// Add a drawing to the worksheet
	//$objDrawing = new PHPExcel_Worksheet_Drawing();
	//$objDrawing->setName('Logo');
	//$objDrawing->setDescription('Logo');
	//$objDrawing->setPath('./img/dio_logo.png');
	//$objDrawing->setHeight(80);
	//$objDrawing->setWorksheet($objPHPExcel->getActiveSheet());
	
	$rowID = 0;
	$line=5;

	while ($dr = $db->sql_fetchrow($result))
	{	
		$rowID++;
		$objPHPExcel->getActiveSheet()->setCellValue('A'.$line, $rowID);
		$objPHPExcel->getActiveSheet()->setCellValue('B'.$line, ($dr["is_valid"]=='True'?'Ναι':'Οχι'));
		$objPHPExcel->getActiveSheet()->setCellValue('C'.$line, $dr["surname"]);
		$objPHPExcel->getActiveSheet()->setCellValue('D'.$line, $dr['firstname']);
		$objPHPExcel->getActiveSheet()->setCellValue('E'.$line, $dr['organization_name']);
		$objPHPExcel->getActiveSheet()->setCellValue('F'.$line, $dr['email']);
		$objPHPExcel->getActiveSheet()->setCellValue('G'.$line, $dr['phone']);
		$line++;
	}
	
	// Rename worksheet
	$objPHPExcel->getActiveSheet()->setTitle($commandCode);


	// Set active sheet index to the first sheet, so Excel opens this as the first sheet
	$objPHPExcel->setActiveSheetIndex(0);


	// Redirect output to a client’s web browser (Excel5)
	header('Content-Type: application/vnd.ms-excel');
	header('Content-Disposition: attachment;filename="'.$commandCode.'-'.date("d-m-Y").'.xls"');
	header('Cache-Control: max-age=0');
	// If you're serving to IE 9, then the following may be needed
	header('Cache-Control: max-age=1');

	// If you're serving to IE over SSL, then the following may be needed
	header ('Expires: Mon, 26 Jul 1997 05:00:00 GMT'); // Date in the past
	header ('Last-Modified: '.gmdate('D, d M Y H:i:s').' GMT'); // always modified
	header ('Cache-Control: cache, must-revalidate'); // HTTP/1.1
	header ('Pragma: public'); // HTTP/1.0

	$objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel5');
	$objWriter->save('php://output');
	exit;
}

}
?> 

<script>
	$(document).ready(function() {
		$('#organization_id').select2();
		$('#profession_id').select2();
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