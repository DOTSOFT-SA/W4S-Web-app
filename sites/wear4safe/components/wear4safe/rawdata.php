<?php
defined( '_VALID_PROCCESS' ) or die( 'Direct Access to this location is not allowed.' );
//print_r($_POST);
//exit;
//Array ( [Command] => -1 [actionDate1] => 2022-12-14 [actionDate2] => 2022-12-15 [employee] => [datatable-filters_length] => 50 )
//Array ( [Command] => -1 [actionDate1] => [actionDate2] => [employee] => 96 [datatable-filters_length] => 50 )
	include($config["physicalPath"]."/languages/".$auth->LanguageCode.".php");
//require_once(dirname(__FILE__) . "/common.php");
//if(($auth->UserRow['admin_type']=='LOCAL')) {
//	Redirect("index.php");
//}
//if($auth->UserType != "Administrator") Redirect("index.php");
$actionDate1 = $_REQUEST['actionDate1'];
$actionDate2 = $_REQUEST['actionDate2'];
if(isset($_REQUEST['map']) && intval($_REQUEST['map'])>0) $map=intval($_REQUEST['map']);

//echo $actionDate1.' - '.$actionDate2.'<br>';
global $nav;
$nav = "Λίστα εγγραφών";
$config["navigation"] = $nav;
$item = (intval($_GET["item"]) > 0 ? intval($_GET["item"]) : "");
$BaseUrl = "index.php?com=rawdata";
if(!isset($_POST["xlsSubmit"])){
	?>
	
	<div class="row">
		<div class="col">
			<section class="card">
				<header class="card-header">
					<div class="card-actions">
						<a href="#" class="card-action card-action-toggle" data-card-toggle></a>
						<a href="#" class="card-action card-action-dismiss" data-card-dismiss></a>
					</div>
					<h2 class="card-title">Λίστα εγγραφών</h2>
				</header>
				<div class="card-body">
					<div class="row" style="margin-bottom:20px;">
						
						<div class='col-md-4'></div>
						<div class='col-md-4'></div>
						<div class='col-md-2' style="text-align:right;">
							<div style="display:block;margin-bottom: 1.8rem;"></div>
							<div class="form-group">
								<button type="submit" name="xlsSubmit" id="xlsSubmit" class="btn btn-primary">Εξαγωγή σε XLS</button>
							</div>
						</div>
						<div class='col-md-2'>
							<div style="display:block;margin-bottom: 1.8rem;"></div>
							<div class="form-group">
								<button style="float:right; width:100px;" id="go" class="btn btn-primary">Go</button>
							</div>
						</div>
					</div>
					
					<!-- <table class="table table-bordered table-striped mb-0" id="datatable-default"> -->
					<table class="table table-responsive-lg  table-bordered table-striped mb-0" id="datatable-filters">
						<thead>
							<tr>
								<th>#</th>
								<th>Εξοπλισμός</th>
								<th>Εργαζόμενος</th>
								<th>Ημνία/Ωρα</th>
								<th>Θέση</th>
								<th>Υψόμετρο</th>
								<th>Γωνία</th>
								<th>Ταχύτητα</th>
								<th>Αισθητήρας</th>
								<th>Τιμή</th>
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
								$filter.=(isset($_REQUEST['map']) && intval($_REQUEST['map'])>0?" AND t3.map_id=".intval($map):"");
								$filter.=($auth->UserType != "Administrator"?' AND t4.organization_id='.$auth->UserRow['organization_id']:"");
								//$auth->UserType != "Administrator"?' AND t2.region_id='.$auth->UserRow['region_id']:"");
								//$filter.=(intval($_POST['employee'])>0?' AND t1.employee_id='.$_POST['employee']:'');
								//$filter.=($_POST['actionDate1']!=''?'':'');
								//$filter.=($actionDate1!=''?" AND date(event_date)>='".$actionDate1."'":'');
								//$filter.=($actionDate2!=''?" AND date(event_date)<='".$actionDate2."'":'');
								//actionDate1] => 2022-12-14
								//$query = "SELECT * FROM alerts ORDER BY dt_server DESC LIMIT 1000";
								$query = "SELECT t1.*,t2.imei,t4.surname,t4.firstname,t2.sensor1_maptype_id,t2.sensor2_maptype_id,t2.sensor3_maptype_id,t2.sensor4_maptype_id,t2.sensor5_maptype_id 
									FROM data t1 
									INNER JOIN sensors t2 ON t1.sensor_id=t2.sensor_id 
									INNER JOIN (SELECT * FROM assignments WHERE return_date IS NULL) t3 ON t2.sensor_id=t3.sensor_id 
									INNER JOIN employees t4 ON t3.employee_id=t4.employee_id ORDER BY t1.alert_id DESC LIMIT 2000";
								//WHERE 1=1 ".$filter."  ";
								//echo $query;
								$result = $db->sql_query($query);
								$counter = 0;
								while ($dr = $db->sql_fetchrow($result))
								{
									//$s = intval(substr($dr["probe"], -1) );
									//if($s>0){
									//	$sField = $dr['sensor'.$s."_maptype_id"];
									//	$sName = $db->RowSelectorQuery("SELECT * FROM maptypes WHERE maptype_id='".$sField."'");
									//}
									?>
										<tr>
											<td><?=$dr["alert_id"]?></td>
											<td><?=$dr["imei"]?></td>
											<td><?=$dr["surname"].' '.$dr["firstname"]?></td>
											<td><?=$dr["dt_server"]?></td>
											<td><?=$dr["lat"].','.$dr["lng"]?></td>
											<td><?=$dr["altitude"]?></td>
											<td><?=$dr["angle"]?></td>
											<td><?=$dr["speed"]?></td>
											<td><?=$dr["probe"]?></td>
											<td><?=$dr["val"]?></td>
										</tr>
									<?
								}
								$db->sql_freeresult($result);
							?>
						</tbody>
					</table>

				</div>
			</section>
		</div>
	</div>

<p class="text-center text-muted mt-3 mb-3">Development by <a target="_blank" href="http://www.dotsoft.gr/">Dotsoft</a> &copy; Copyright <?=date("Y");?>. All Rights Reserved.</p>
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

<? } else {
	$commandCode='Εξαγωγή λίστας Εγγραφών';
	$commandShortName='Λίστα εγγραφών';

	//$filter=" AND t2.is_valid='True'";
	//$filter.=($actionDate1!=''?" AND event_date>='".$actionDate1."'":'');
	//$filter.=($actionDate2!=''?" AND event_date<='".$actionDate2."'":'');
	//$filter.= (intval($employee)>0?' AND t2.employee_id='.$employee:'');
	$filter="";
	$filter.=(isset($_REQUEST['map']) && intval($_REQUEST['map'])>0?" AND t3.map_id=".intval($map):"");
	$filter.=($auth->UserType != "Administrator"?' AND t4.organization_id='.$auth->UserRow['organization_id']:"");
	//$auth->UserType != "Administrator"?' AND t2.region_id='.$auth->UserRow['region_id']:"");
	//$filter.=(intval($_POST['employee'])>0?' AND t1.employee_id='.$_POST['employee']:'');
	//$filter.=($_POST['actionDate1']!=''?'':'');
	//$filter.=($actionDate1!=''?" AND date(event_date)>='".$actionDate1."'":'');
	//$filter.=($actionDate2!=''?" AND date(event_date)<='".$actionDate2."'":'');
	//actionDate1] => 2022-12-14
	//$query = "SELECT * FROM alerts ORDER BY dt_server DESC LIMIT 1000";
	$query = "SELECT t1.*,t2.imei,t4.surname,t4.firstname,t2.sensor1_maptype_id,t2.sensor2_maptype_id,t2.sensor3_maptype_id,t2.sensor4_maptype_id,t2.sensor5_maptype_id 
		FROM data t1 
		INNER JOIN sensors t2 ON t1.sensor_id=t2.sensor_id 
		INNER JOIN (SELECT * FROM assignments WHERE return_date IS NULL) t3 ON t2.sensor_id=t3.sensor_id 
		INNER JOIN employees t4 ON t3.employee_id=t4.employee_id ORDER BY t1.alert_id DESC LIMIT 2000";

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
	$lastcolumn = 'J';
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
	$objPHPExcel->getActiveSheet()->setAutoFilter('A4:I4');
	// Set column titles and columnn widths (0 : Auto)
	$columns = array(
		 'A'=>array('#', 8)
		,'B'=>array('Εξοπλισμός', 15)
		,'C'=>array('Εργαζόμενος', 30)
		,'D'=>array('Ημνία/Ωρα', 30)
		,'E'=>array('Θέση', 30)
		,'F'=>array('Υψόμετρο', 25)
		,'G'=>array('Γωνία', 20)
		,'H'=>array('Ταχύτητα', 20)
		,'I'=>array('Αισθητήρας', 20)
		,'J'=>array('Τιμή', 20)
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
		if($dr["exit_date"]!=''){
			$datetime1 = new DateTime($dr["event_date"]);
			$datetime2 = new DateTime($dr["exit_date"]);
			$interval = $datetime1->diff($datetime2);
			$elapsed = $interval->format('%h:%i:%s');										
		} else {
			$elapsed='';
		}
		$rowID++;
		//$objPHPExcel->getActiveSheet()->setCellValue('A'.$line, $rowID);
		$objPHPExcel->getActiveSheet()->setCellValue('A'.$line, $dr["alert_id"]);
		
		$objPHPExcel->getActiveSheet()->setCellValue('B'.$line, $dr["imei"]);
		$objPHPExcel->getActiveSheet()->setCellValue('C'.$line, $dr["surname"].' '.$dr["firstname"]);
		$objPHPExcel->getActiveSheet()->setCellValue('D'.$line, $dr['dt_server']);
		$objPHPExcel->getActiveSheet()->setCellValue('E'.$line, $dr["lat"].','.$dr["lng"]);
		$objPHPExcel->getActiveSheet()->setCellValue('F'.$line, $dr['altitude']);
		
		$objPHPExcel->getActiveSheet()->setCellValue('G'.$line, $dr['angle']);
		$objPHPExcel->getActiveSheet()->setCellValue('H'.$line, $dr['speed']);
		$objPHPExcel->getActiveSheet()->setCellValue('I'.$line, $dr['probe']);
		$objPHPExcel->getActiveSheet()->setCellValue('J'.$line, $dr['val']);
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
?>

<script>
	$(document).ready(function() {
		$('#map').select2();
	});
</script>