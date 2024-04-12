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
$nav = "Ρυθμίσεις ΜΑΠ";
$config["navigation"] = $nav;
$item = (intval($_GET["item"]) > 0 ? intval($_GET["item"]) : "");
$BaseUrl = "index.php?com=alerts";
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
								<? if($auth->UserType == "Administrator"){?> <th>Οργανισμός</th><? } ?>
								<th>Επάγγελμα</th>
								<th>ΜΑΠ 1</th>
								<th>ΜΑΠ 2</th>
								<th>ΜΑΠ 3</th>
								<th>ΜΑΠ 4</th>
								<th>ΜΑΠ 5</th>
								<th>Ημ/νία εισαγωγής</th>
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
									//$deviceRow = $db->RowSelectorQuery("SELECT * FROM sensors WHERE sensor_id = '".$dr['sensor_id']."'");
									//$s = intval(substr($dr["probe"], -1) );
									//if($s>0){
									//	$sField = $dr['sensor'.$s."_maptype_id"];
									//	$sName = $db->RowSelectorQuery("SELECT * FROM maptypes WHERE maptype_id='".$sField."'");
									//}
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

<p class="text-center text-muted mt-3 mb-3">Development by <a target="_blank" href="http://www.dotsoft.gr/">Dotsoft</a> &copy; Copyright 2023. All Rights Reserved.</p>
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
	$commandCode='Εξαγωγή Ρυθμίσεων ΜΑΠ';
	$commandShortName='Ρυθμίσεις ΜΑΠ';
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
	$lastcolumn = 'I';
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
		,'B'=>array('Οργανισμός', 15)
		,'C'=>array('Επάγγελμα', 30)
		,'D'=>array('ΜΑΠ 1', 30)
		,'E'=>array('ΜΑΠ 2', 30)
		,'F'=>array('ΜΑΠ 3', 25)
		,'G'=>array('ΜΑΠ 4', 20)
		,'H'=>array('ΜΑΠ 5', 20)
		,'I'=>array('Ημ/νία εισαγωγής', 20)
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
		$desc1 = ($dr["mandatory1"]=='True'?" (!)":($dr['condition1_name']!=""?" (".$dr['condition1_name'].")":""));
		$desc2 = ($dr["mandatory2"]=='True'?" (!)":($dr['condition2_name']!=""?" (".$dr['condition2_name'].")":""));
		$desc3 = ($dr["mandatory3"]=='True'?" (!)":($dr['condition3_name']!=""?" (".$dr['condition3_name'].")":""));
		$desc4 = ($dr["mandatory4"]=='True'?" (!)":($dr['condition4_name']!=""?" (".$dr['condition4_name'].")":""));
		$desc5 = ($dr["mandatory5"]=='True'?" (!)":($dr['condition5_name']!=""?" (".$dr['condition5_name'].")":""));
		//$objPHPExcel->getActiveSheet()->setCellValue('A'.$line, $rowID);
		$objPHPExcel->getActiveSheet()->setCellValue('A'.$line, $dr["prosetting_id"]);
		$objPHPExcel->getActiveSheet()->setCellValue('B'.$line, $dr["organization_name"]);
		$objPHPExcel->getActiveSheet()->setCellValue('C'.$line, $dr["profession_name"]);
		$objPHPExcel->getActiveSheet()->setCellValue('D'.$line, $dr["sensor1_name"].$desc1);
		$objPHPExcel->getActiveSheet()->setCellValue('E'.$line, $dr["sensor2_name"].$desc2);
		$objPHPExcel->getActiveSheet()->setCellValue('F'.$line, $dr["sensor3_name"].$desc3);
		$objPHPExcel->getActiveSheet()->setCellValue('G'.$line, $dr["sensor4_name"].$desc4);
		$objPHPExcel->getActiveSheet()->setCellValue('H'.$line, $dr["sensor5_name"].$desc5);
		$objPHPExcel->getActiveSheet()->setCellValue('I'.$line, $dr['date_insert']);
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