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
//if(isset($_REQUEST['map']) && intval($_REQUEST['map'])>0) $map=intval($_REQUEST['map']);

//echo $actionDate1.' - '.$actionDate2.'<br>';
global $nav;
$nav = "Λίστα αναθέσεων";
$config["navigation"] = $nav;
$item = (intval($_GET["item"]) > 0 ? intval($_GET["item"]) : "");
$BaseUrl = "index.php?com=assignmentslist";
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
					<h2 class="card-title"><?=$nav?></h2>
				</header>
				<div class="card-body">
					<div class="row" style="margin-bottom:20px;">
						
						<div class='col-md-4'>
							<!-- 
							<label for="map">Κατηγορία</label>
							<select id="map" name="map" class="form-control">
								<option value="" selected>Ολα</option>
								<?
									//$resultMap = $db->sql_query("SELECT * FROM map WHERE is_valid='True'");
									//$counter = 0;
									//while ($drMap = $db->sql_fetchrow($resultMap)){
									//	echo "<option value='".$drMap['map_id']."' ".($drMap['map_id']==$map?'selected':'').">".$drMap['map_name']."</option>";
									//}
								?>
							</select>
							-->

						</div>
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
								<th>Επόπτης</th>
								<th>Υπάλληλος</th>
								<th>Αισθητήρας</th>
								<th>IMEI</th>
								<th>Ημ/νία ανάθεσης</th>
								<th>Ημ/νία επιστροφής</th>
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
								//$filter="";
								//$filter.=(isset($_REQUEST['map']) && intval($_REQUEST['map'])>0?" AND t3.map_id=".intval($map):"");
								//$auth->UserType != "Administrator"?' AND t2.region_id='.$auth->UserRow['region_id']:"");
								//$filter.=(intval($_POST['employee'])>0?' AND t1.employee_id='.$_POST['employee']:'');
								//$filter.=($_POST['actionDate1']!=''?'':'');
								//$filter.=($actionDate1!=''?" AND date(event_date)>='".$actionDate1."'":'');
								//$filter.=($actionDate2!=''?" AND date(event_date)<='".$actionDate2."'":'');
								//actionDate1] => 2022-12-14
								$filter.=($auth->UserType != "Administrator"?' AND t1.user_id IN (SELECT user_id FROM users WHERE organization_id='.$auth->UserRow['organization_id'].')':'');
								$query = "SELECT t1.*,t2.user_fullname, t3.surname,t3.firstname,t4.sensor_name,t4.imei
								FROM assignments t1 
								INNER JOIN users t2 ON t1.user_id=t2.user_id 
								INNER JOIN employees t3 ON t1.employee_id=t3.employee_id 
								INNER JOIN sensors t4 ON t1.sensor_id=t4.sensor_id 
								WHERE 1=1 ".$filter." ORDER BY t1.date_insert ";
								//echo $query;
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
											<td><?=$dr["return_date"]?></td>
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
	$commandCode='Εξαγωγή λίστας Αναθέσεων';
	$commandShortName='Λίστα Αναθέσεων';

	
	$filter="";
	//$filter.=($auth->UserType != "Administrator"?' AND t2.region_id='.$auth->UserRow['region_id']:"");
	//$filter.=(intval($_POST['employee'])>0?' AND t1.employee_id='.$_POST['employee']:'');
	//$filter.=($_POST['actionDate1']!=''?'':'');
	//$filter.=($actionDate1!=''?" AND date(event_date)>='".$actionDate1."'":'');
	//$filter.=($actionDate2!=''?" AND date(event_date)<='".$actionDate2."'":'');
	//actionDate1] => 2022-12-14
		$filter.=($auth->UserType != "Administrator"?' AND t1.user_id IN (SELECT user_id FROM users WHERE organization_id='.$auth->UserRow['organization_id'].')':'');
		$query = "SELECT t1.*,t2.user_fullname, t3.surname,t3.firstname,t4.sensor_name,t4.imei
		FROM assignments t1 
		INNER JOIN users t2 ON t1.user_id=t2.user_id 
		INNER JOIN employees t3 ON t1.employee_id=t3.employee_id 
		INNER JOIN sensors t4 ON t1.sensor_id=t4.sensor_id 
		WHERE 1=1 ".$filter." ORDER BY t1.date_insert ";



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
	$objPHPExcel->getActiveSheet()->setAutoFilter('A4:I4');
	// Set column titles and columnn widths (0 : Auto)
	$columns = array(
		 'A'=>array('#', 8)
		,'B'=>array('Επόπτης', 15)
		,'C'=>array('Υπάλληλος', 30)
		,'D'=>array('Αισθητήρας', 30)
		,'E'=>array('IMEI', 30)
		,'F'=>array('Ημ/νία ανάθεσης', 25)
		,'G'=>array('Ημ/νία επιστροφής', 20)
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
		//$objPHPExcel->getActiveSheet()->setCellValue('A'.$line, $rowID);
		$objPHPExcel->getActiveSheet()->setCellValue('A'.$line, $dr["assignment_id"]);
		
		$objPHPExcel->getActiveSheet()->setCellValue('B'.$line, $dr["user_fullname"]);
		$objPHPExcel->getActiveSheet()->setCellValue('C'.$line, $dr["surname"].' '.$dr["firstname"]);
		$objPHPExcel->getActiveSheet()->setCellValue('D'.$line, $dr['sensor_name']);
		$objPHPExcel->getActiveSheet()->setCellValue('E'.$line, $dr['imei']);
		$objPHPExcel->getActiveSheet()->setCellValue('F'.$line, $dr['assignment_date']);
		
		$objPHPExcel->getActiveSheet()->setCellValue('G'.$line, $dr['return_date']);

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