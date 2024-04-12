<?php
defined( '_VALID_PROCCESS' ) or die( 'Direct Access to this location is not allowed.' );
	include($config["physicalPath"]."/languages/".$auth->LanguageCode.".php");
//require_once(dirname(__FILE__) . "/common.php");
//if(($auth->UserRow['admin_type']=='LOCAL')) {
//	Redirect("index.php");
//}
//if($auth->UserType != "Administrator") Redirect("index.php");

global $nav;
$nav = "Νομοθετήματα";
$config["navigation"] = $nav;
$item = (intval($_GET["item"]) > 0 ? intval($_GET["item"]) : "");
$BaseUrl = "index.php?com=legislations";
$command=array();
$command=explode("&",$_POST["Command"]);
function base64_to_jpeg($base64_string, $output_file) {
    // open the output file for writing
    $ifp = fopen( $output_file, 'wb' ); 

    // split the string on commas
    // $data[ 0 ] == "data:image/png;base64"
    // $data[ 1 ] == <actual base64 string>
    $data = explode( ',', $base64_string );

    // we could add validation here with ensuring count( $data ) > 1
    fwrite( $ifp, base64_decode( $data[ 1 ] ) );

    // clean up the file resource
    fclose( $ifp ); 

    return $output_file; 
}
if( $auth->UserType == "Administrator" )
{
	if(isset($_REQUEST["Command"]))
	{	
		if($_REQUEST["Command"] == "SAVE")
		{
			$newFileName="";
			$currentDirectory = getcwd();
			$uploadDirectory = "/uploads/";
			
			$errors = []; // Store errors here
			$fileExtensionsAllowed = ['jpeg','jpg','png','gif','pdf','doc','docx']; // These will be the only file extensions allowed 

			if((isset($_POST['fileDragData']) || isset($_FILES['file1']['name']))){
				$uploadPath = $currentDirectory . $uploadDirectory . basename($newFileName); 
				$fileName = $_FILES['file1']['name'];
				if(isset($_POST['fileDragData']) && $_POST['fileDragData']!=''){
					$fileExtension = strtolower(end(explode('.',$_POST['fileDragName'])));

					//check extension
					if (! in_array($fileExtension,$fileExtensionsAllowed)) {
						$errors[] = "This file extension is not allowed. Please upload a JPEG or PNG file";
					} 
					//check size
					if (strlen($_POST['fileDragData'])> 4000000) {
						$errors[] = "File exceeds maximum size (4MB)";
					}
					if (empty($errors)) {
						//base64_to_jpeg($_POST['fileDragData'],'uploads/'.$_POST['fileDragName']);
						$newFileName=randomCode(15).'.'.$fileExtension;
						
						base64_to_jpeg($_POST['fileDragData'],'uploads/'.$newFileName);
						//$didUpload = move_uploaded_file($fileName, $uploadPath);
						
						echo "The file " . basename($fileName) . " has been uploaded";
					} else {
						foreach ($errors as $error) {
							echo $error . "These are the errors" . "\n";
						}
					}
				} else {
					$fileName = $_FILES['file1']['name'];
					$fileSize = $_FILES['file1']['size'];
					$fileTmpName  = $_FILES['file1']['tmp_name'];
					$fileType = $_FILES['file1']['type'];
					$fileExtension = strtolower(end(explode('.',$fileName)));
					//$uploadPath = $currentDirectory . $uploadDirectory . basename($fileName); 
					$newFileName=randomCode(15).'.'.$fileExtension;
					$uploadPath = $currentDirectory . $uploadDirectory . basename($newFileName); 
					
					//check extension
					if (! in_array($fileExtension,$fileExtensionsAllowed)) {
						$errors[] = $fileExtension."This file extension is not allowed. Please upload a JPEG or PNG file";
					}
					//check size
					if ($fileSize > 4000000) {
						$errors[] = "File exceeds maximum size (4MB)";
					}
					if (empty($errors)) {
						$didUpload = move_uploaded_file($fileTmpName, $uploadPath);
						if ($didUpload) {
							echo "The file " . basename($fileName) . " has been uploaded";
						} else {
							echo "An error occurred. Please contact the administrator.";
						}
					} else {
						foreach ($errors as $error) {
							echo $error . "These are the errors" . "\n";
						}
					}
				}
			}
			
			
			$PrimaryKeys = array();
			$Collector = array();
			$QuotFields = array();

			
			if(isset($_GET["item"]) && $_GET["item"] != "" && intval($_GET["item"])> 0)
			{
				$PrimaryKeys["legislation_id"] = intval($_GET["item"]);
				$QuotFields["legislation_id"] = true;
				
			} else {
				$Collector["date_insert"] = date('Y-m-d H:i:s');
				$QuotFields["date_insert"] = true;
			}
			
			//$Collector["is_valid"] = ($_POST["is_valid"]=='on'?'True':'False');
			//$QuotFields["is_valid"] = true;
			
		
			$Collector["legislation_name"] = $_POST["legislation_name"];
			$QuotFields["legislation_name"] = true;
			
			$Collector["legislation_title"] = $_POST["legislation_title"];
			$QuotFields["legislation_title"] = true;
			
			$Collector["file"] = $newFileName;
			$QuotFields["file"] = true;
			
			$Collector["description"] = $_POST["description"];
			$QuotFields["description"] = true;
			

			$db->ExecuteUpdater("legislations",$PrimaryKeys,$Collector,$QuotFields);
			//$messages->addMessage("Αποθηκευτηκε!!!");
			Redirect($BaseUrl);
		} else if($_REQUEST["Command"] ==  "DELETE") { //$command[0] ==
			if($item != ""){
				$error=0;
				//$result = $db->sql_query('SELECT * FROM products WHERE product_id='.$item); Πρέπει να γίνει έλεγχος αν υπάρχουν κινήσεις
				if($db->sql_numrows($result) > 0) $error++;
				if($error==0) {	
					//$filter=" AND user_id=".$auth->UserId;
					//$filter=($auth->UserType != "Administrator"?' AND user_id = '.$auth->UserId:'');
					$db->sql_query("DELETE FROM legislations WHERE legislation_id=" . $item.$filter);
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
	//$filter=($auth->UserType != "Administrator"?' AND user_id='.$auth->UserId:'');
	$query="SELECT * FROM legislations WHERE legislation_id=".$_GET['item'].$filter." LIMIT 1";
	$dr_e = $db->RowSelectorQuery($query);
	if(intval($_GET["item"])> 0 && intval($dr_e['legislation_id'])==0){
		$messages->addMessage("NOT FOUND!!!");
		Redirect("index.php?com=legislations");		
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
					<h2 class="card-title"><?=edit?></h2>
				</header>
				<div class="card-body">
					<div class="form-horizontal form-bordered" method="get">
					
						<div class="form-group row">
							<div class="col-lg-12">
								<div class="form-group">
									<label class="col-form-label" for="legislation_name">Ονομασία</label>
									<input type="text" class="form-control" id="legislation_name" name="legislation_name" value="<?=(isset($dr_e["legislation_name"]) ? $dr_e["legislation_name"]:'')?>">
								</div>
							</div>
						</div>
						
						<div class="form-group row">
							<div class="col-lg-12">
								<div class="form-group">
									<label class="col-form-label" for="legislation_title">Τίτλος</label>
									<input type="text" class="form-control" id="legislation_title" name="legislation_title" value="<?=(isset($dr_e["legislation_title"]) ? $dr_e["legislation_title"]:'')?>">
								</div>
							</div>
						</div>
						
						
						
						<div class="form-group row">
							<div class="col-lg-12">
								<label class="col-form-label" for="description">Παρατηρήσεις</label>
								<textarea class="form-control" name="description" id="description" rows="3"  data-plugin-textarea-autosize><?=$dr_e["description"]?></textarea>
							</div>
						</div>
			
						<div class="form-group row">
							<div class="col-lg-12" style="text-align:center;">
									<?
									if($dr_e['file']!=''){
										echo '<a href="/uploads/'.$dr_e['file'].'" target="_blank" style="font-size:48px;"><i class="fas fa-file-pdf"></i></a>';
									}
									?>
							</div>
						</div>
						
						<div class="form-group row">
							<div class="col-lg-12" style="text-align:center;">
								<input type="file" name="file1" id="file1" class="inputfile inputfile-4"  data-multiple-caption="{count} files selected" multiple />
								<label for="file1">
								<!-- 
									<figure>
										<svg xmlns="http://www.w3.org/2000/svg" width="20" height="17" viewBox="0 0 20 17"><path d="M10 0l-5.2 4.9h3.3v5.1h3.8v-5.1h3.3l-5.2-4.9zm9.3 11.5l-3.2-2.1h-2l3.4 2.6h-3.5c-.1 0-.2.1-.2.1l-.8 2.3h-6l-.8-2.2c-.1-.1-.1-.2-.2-.2h-3.6l3.4-2.6h-2l-3.2 2.1c-.4.3-.7 1-.6 1.5l.6 3.1c.1.5.7.9 1.2.9h16.3c.6 0 1.1-.4 1.3-.9l.6-3.1c.1-.5-.2-1.2-.7-1.5z"/></svg>
									</figure>-->
									<span id="dragFile">Choose a file&hellip;</span>
								</label>
								<input id="fileDragName" name="fileDragName" type="hidden">
								<!-- 
								<input id="fileDragSize">
								<input id="fileDragType">
								-->
								
								<input id="fileDragData" name="fileDragData" type="hidden">
								<div id="holder" style="width:100%; height:200px; border: 5px dashed #888"></div>
							</div>
						</div>

				
						<div class="form-group row" style="margin-top:20px;">
							<? if($auth->UserType == "Administrator") { ?>
								<a href="#" onClick="checkFields();"><button type="button" class="mb-1 mt-1 mr-1 btn btn-primary">Αποθήκευση</button></a>
							<? } ?>
							<a href="index.php?com=legislations"><button type="button" class="mb-1 mt-1 mr-1 btn btn-primary">Επιστροφή</button></a>
						</div>
					</div>

				</div>
			</section>
		</div>
	</div>
	
	

	<script>
		//document.getElementById("submitBtn").disabled = true;
		function checkFields(){
			var legislation_name = $('#legislation_name').val();
			var legislation_title = $('#legislation_title').val();
				if ( legislation_name.length >= 2 && legislation_title.length>2){ //&& user_name.length >= 5 && user_password.length >= 5
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
					<!-- <table class="table table-responsive-lg  table-bordered table-striped mb-0" id="datatable-default">-->
						<thead>
							<tr>
								<th>Ονομασία</th>
								<th>Τίτλος</th>
								<th>Ημ/νία εισαγωγής</th>
								<th>Ενέργεια</th>
							</tr>
						</thead>
							<tbody>
							<?	
								//$filter=($auth->UserType != "Administrator"?' AND user_id='.$auth->UserId:'');
								$query = "SELECT * FROM legislations WHERE 1=1 ".$filter." ORDER BY legislation_name ASC ";
								$result = $db->sql_query($query);
								$counter = 0;
								while ($dr = $db->sql_fetchrow($result))
								{
									?>
										<tr>
											<td><?=$dr["legislation_name"]?></td>
											<td><?=$dr["legislation_title"]?></td>
											<td style="width:160px;"><?=$dr["date_insert"]?></td>
											<td style="width:120px;">
												<a data-toggle="tooltip" data-placement="top" title="Επεξεργασία" style="padding:4px"  href="index.php?com=legislations&Command=edit&item=<?=$dr["legislation_id"]?>"><i style="font-size:24px;" class="fas fa-edit"></i> </a>
												<? if($auth->UserType == "Administrator") { ?>
													<a data-toggle="tooltip" data-placement="top" title="Διαγραφή" href="#" onclick="ConfirmDelete('Επιβεβαίωση διαγραφής','index.php?com=legislations&Command=DELETE&item=<?=$dr["legislation_id"]?>');"><i style="font-size:24px;" class="fas fa-trash"></i> </a>
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
							<a href="index.php?com=legislations&item="><button type="button" class="mb-1 mt-1 mr-1 btn btn-primary">Νέα εγγραφή</button></a>
						</div>
					<? } ?>
				</div>
			</section>
		</div>
	</div>
			
<? } ?> 

<script type="text/javascript">
	function readfiles(files) {
			document.getElementById('fileDragName').value = files[0].name
			document.getElementById('dragFile').innerHTML = files[0].name
			//document.getElementById('fileDragSize').value = files[0].size
			//document.getElementById('fileDragType').value = files[0].type
			reader = new FileReader();
			reader.onload = function(event) {
				document.getElementById('fileDragData').value = event.target.result;}
				reader.readAsDataURL(files[0]);
	}
	var holder = document.getElementById('holder');
	holder.ondragover = function () { this.className = 'hover'; return false; };
	holder.ondragend = function () { this.className = ''; return false; };
	holder.ondrop = function (e) {
		this.className = '';
		e.preventDefault();
		readfiles(e.dataTransfer.files);
	}
</script>
<script>
$('#file1').on('change', function() { 
   // $(this).val() // get the current value of the input field.
	//console.log('input changed to: ', file1.value);
	document.getElementById('dragFile').innerHTML = file1.value
	
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