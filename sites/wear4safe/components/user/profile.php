<?php defined( '_VALID_PROCCESS' ) or die( 'Direct Access to this location is not allowed.' ); ?>
<?
include($config["physicalPath"]."/languages/".$auth->LanguageCode.".php");
$config["navigation"] = editProfile;
global $components;

//if(isset($_GET["logout"]) && $_GET["logout"] == "true")
//{
//	Logout();
//	Redirect(CreateUrl());
//}

if($auth->UserId == "") Redirect(CreateUrl(array("com"=>"login","redir"=> CreateUrl(array("com"=>"profile")))));

$BaseUrl = CreateUrl(array("com"=>"profile"));

$Message = "";
//if($toolBar->CurrentCommand() == "SAVEINFO" && $_POST["reg_email"] != ""  && $_POST["reg_fname"] != "")
if(isset($_POST["Command"]) )
{
	if($_POST["Command"] == "SAVE")
	{
		$PerformRegister = true;

		//file upload
		$myfile=basename($_FILES["fileToUpload"]["name"]);
		$targetFile="";
		if(isset($myfile) && $myfile!="") {
			$targetDir = $config["physicalPath"] . "gallery/customer_logo/".$auth->UserId."/"; 
			$uploadOk = 1;
			$imageFileType = pathinfo($myfile,PATHINFO_EXTENSION);
			$targetFile = $targetDir.$auth->UserId.".".$imageFileType;
			$targetFiletoSave = $auth->UserId.".".$imageFileType;
			// Check if image file is a actual image or fake image
			if(isset($_POST["Command"]) && $_POST["Command"] == "SAVE") {
				$check = getimagesize($_FILES["fileToUpload"]["tmp_name"]);
				if($check !== false) {
					echo "File is an image - " . $check["mime"] . ".";
					$uploadOk = 1;
				} else {
					echo "File is not an image.";
					$uploadOk = 0;
				}
			}

			// Check file size
			if ($_FILES["fileToUpload"]["size"] > 500000) {
				echo "Sorry, your file is too large. Our limit is 500kb";
				$uploadOk = 0;
			}

			// Create target dir
			if (!file_exists($targetDir)) {
				@mkdir($targetDir);
			}

			// Allow certain file formats
			if($imageFileType != "jpg" && $imageFileType != "png" && $imageFileType != "jpeg" && $imageFileType != "gif" ) {
				echo "Sorry, only JPG, JPEG, PNG & GIF files are allowed.";
				$uploadOk = 0;
			}
			// Check if file already exists
			//if (file_exists($target_file)) {
			//	echo "Sorry, file already exists.";
			//	$uploadOk = 0;
			//}
			
			// Check if $uploadOk is set to 0 by an error
			if ($uploadOk == 0) {
				echo "Sorry, your file was not uploaded.";
			// if everything is ok, try to upload file
			} else {
				if (move_uploaded_file($_FILES["fileToUpload"]["tmp_name"], $targetFile)) {
					echo "The file ". basename( $_FILES["fileToUpload"]["name"]). " has been uploaded.";
				} else {
					echo "Sorry, there was an error uploading your file.";
				}
			}
		}
		
		/*$dr = $db->RowSelectorQuery("select count(*) from users where email='" . $_POST["reg_email"] . "' AND user_id != " . $auth->UserId );
		if(isset($dr) && $dr[0] > 0)
		{
			$PerformRegister = false;
			$messages->addMessage("saved!!!");
		}*/
		
		if($PerformRegister)
		{
			$PrimaryKeys = array();
			$Collector = array();
			$QuotFields = array();
			
			$PrimaryKeys["user_id"] = $auth->UserId;
			$QuotFields["user_id"] = true;
			
			if(isset($_POST["pw"]) && $_POST["pw"] != "")
			{
				if ($_POST["pw"]!=$auth->UserRow["user_password"]) {
					$messages->addMessage("Old password incorrect!!!");
				} else {
					if ($_POST["pw_new"]==$_POST["pw_new2"]) {
						$Collector["user_password"] = $_POST["pw_new"];
						$QuotFields["user_password"] = true;
					} else {
						$messages->addMessage("New passwords not same!!!");
					}
				}
			}
			//$Collector["email"] = $_POST["email"];
			//$QuotFields["email"] = true;
			
			$Collector["phone"] = $_POST["phone"];
			$QuotFields["phone"] = true;
			
			$Collector["mobilephone"] = $_POST["mobilephone"];
			$QuotFields["mobilephone"] = true;
			
			$Collector["mobilephone"] = $_POST["mobilephone"];
			$QuotFields["mobilephone"] = true;
						
			$Collector["address1"] = $_POST["address1"];
			$QuotFields["address1"] = true;
			
			$Collector["address2"] = $_POST["address2"];
			$QuotFields["address2"] = true;
			
			$Collector["city"] = $_POST["city"];
			$QuotFields["city"] = true;
			
			$Collector["zip"] = $_POST["zip"];
			$QuotFields["zip"] = true;
			
			$Collector["contactmethod"] = $_POST["contactmethod"];
			$QuotFields["contactmethod"] = true;
			
		
			//if($auth->UserType == "Administrator") {
			//	$Collector["is_valid"] = ($_POST["is_valid"]=="on" ? "True" : "False");
			//	$QuotFields["is_valid"] = true;
			//}
			
			if(isset($myfile) && $targetFile!="") {		
				$Collector["user_photo"] = $targetFiletoSave;
				$QuotFields["user_photo"] = true;
			}
			
			$db->ExecuteUpdater("users",$PrimaryKeys,$Collector,$QuotFields);
			$dr = $db->RowSelectorQuery("SELECT * FROM users WHERE user_id=" . $auth->UserId);
			$auth->UserRow = $dr;
			$messages->addMessage("Saved!!!");
		}
	}
}
?>
					<div class="row">
						<div class="col-lg-4 col-xl-3 mb-4 mb-xl-0">

							<section class="card">
								<div class="card-body">
									<div class="thumb-info mb-3">
										<img src="img/logo-person.png" class="rounded img-fluid" alt="<?=$auth->UserRow['user_fullname']?>">
										<div class="thumb-info-title">
											<span class="thumb-info-inner"><?=$auth->UserRow['user_fullname']?></span>
											<!-- <span class="thumb-info-type">CEO</span> -->
										</div>
									</div>

									<div class="widget-toggle-expand mb-3">
										<!-- 
										<div class="widget-header">
											<h5 class="mb-2">Ολοκλήρωση στοιχείων</h5>
											<div class="widget-toggle">+</div>
										</div>										
										
										<div class="widget-content-collapsed">
											<div class="progress progress-xs light">
												<div class="progress-bar" role="progressbar" aria-valuenow="60" aria-valuemin="0" aria-valuemax="100" style="width: 60%;">
													60%
												</div>
											</div>
										</div>
										-->
										<!-- 
										<div class="widget-content-expanded">
											<ul class="simple-todo-list mt-3">
												<li class="completed">Update Profile Picture</li>
												<li class="completed">Change Personal Information</li>
											</ul>
										</div>
										-->

									</div>
									<!--
									<hr class="dotted short">
									<h5 class="mb-2 mt-3">About</h5>
									<p class="text-2">Lorem ipsum dolor sit amet, consectetur adipiscing elit. Etiam quis vulputate quam. Interdum et malesuada</p>
									<div class="clearfix">
										<a class="text-uppercase text-muted float-right" href="#">(View All)</a>
									</div>
									-->
									<hr class="dotted short">
									<!--
									<div class="social-icons-list">
										<a rel="tooltip" data-placement="bottom" target="_blank" href="http://www.facebook.com" data-original-title="Facebook"><i class="fab fa-facebook-f"></i><span>Facebook</span></a>
										<a rel="tooltip" data-placement="bottom" href="http://www.twitter.com" data-original-title="Twitter"><i class="fab fa-twitter"></i><span>Twitter</span></a>
										<a rel="tooltip" data-placement="bottom" href="http://www.linkedin.com" data-original-title="Linkedin"><i class="fab fa-linkedin-in"></i><span>Linkedin</span></a>
									</div>
									-->
								</div>
							</section>

							
							<!--
							<section class="card">
								<header class="card-header">
									<div class="card-actions">
										<a href="#" class="card-action card-action-toggle" data-card-toggle></a>
										<a href="#" class="card-action card-action-dismiss" data-card-dismiss></a>
									</div>

									<h2 class="card-title">Popular Posts</h2>
								</header>
								<div class="card-body">
									<ul class="simple-post-list">
										<li>
											<div class="post-image">
												<div class="img-thumbnail">
													<a href="#">
														<img src="img/post-thumb-1.jpg" alt="">
													</a>
												</div>
											</div>
											<div class="post-info">
												<a href="#">Nullam Vitae Nibh Un Odiosters</a>
												<div class="post-meta">
													 Jan 10, 2017
												</div>
											</div>
										</li>
										<li>
											<div class="post-image">
												<div class="img-thumbnail">
													<a href="#">
														<img src="img/post-thumb-2.jpg" alt="">
													</a>
												</div>
											</div>
											<div class="post-info">
												<a href="#">Vitae Nibh Un Odiosters</a>
												<div class="post-meta">
													 Jan 10, 2017
												</div>
											</div>
										</li>
										<li>
											<div class="post-image">
												<div class="img-thumbnail">
													<a href="#">
														<img src="img/post-thumb-3.jpg" alt="">
													</a>
												</div>
											</div>
											<div class="post-info">
												<a href="#">Odiosters Nullam Vitae</a>
												<div class="post-meta">
													 Jan 10, 2017
												</div>
											</div>
										</li>
									</ul>
								</div>
							</section>
							-->
						</div>
						<div class="col-lg-8 col-xl-9">
							<div class="tabs">
								<ul class="nav nav-tabs tabs-primary">
									
									<li class="nav-item active">
										<a class="nav-link" href="#edit" data-toggle="tab">Επεξεργασία</a>
									</li>
								</ul>
								<div class="tab-content">
									
									<div id="edit" class="tab-pane active">
										<form class="p-3">
											<h4 class="mb-3">Προσωπικές πληροφορίες</h4>
											<div class="form-group">
												<label for="address1">Διεύθυνση επικοινωνίας</label>
												<input type="text" class="form-control" id="address1" name="address1" value="<?=(isset($_POST["address1"]) ? $_POST["address1"] : $auth->UserRow["address1"])?>">
											</div>
											<div class="form-group">
												<label for="address2">Άλλη διεύθυνση επικοινωνίας</label>
												<input type="text" class="form-control" id="address2" name="address2" value="<?=(isset($_POST["address2"]) ? $_POST["address2"] : $auth->UserRow["address2"])?>">
											</div>
											<div class="form-row">
												<div class="form-group col-md-10">
													<label for="city">Πόλη</label>
													<input type="text" class="form-control" id="city" name="city" value="<?=(isset($_POST["city"]) ? $_POST["city"] : $auth->UserRow["city"])?>">
												</div>
												<div class="form-group col-md-2">
													<label for="zip">Τ.Κ.</label>
													<input type="text" class="form-control" id="zip" name="zip" value="<?=(isset($_POST["zip"]) ? $_POST["zip"] : $auth->UserRow["zip"])?>">
												</div>
											</div>
											<hr class="dotted">
											<h4 class="mb-3">Τηλέφωνο</h4>
											<div class="form-row">
												<div class="form-group col-md-4">
													<label for="mobilephone">Κινητό τηλέφωνο επικοινωνίας</label>
													<input type="text" class="form-control" id="mobilephone" name="mobilephone" value="<?=(isset($_POST["mobilephone"]) ? $_POST["mobilephone"] : $auth->UserRow["mobilephone"])?>">
												</div>
												<div class="form-group col-md-4">
													<label for="phone">Σταθερό τηλέφωνο επικοινωνίας</label>
													<input type="text" class="form-control" id="phone" name="phone" value="<?=(isset($_POST["phone"]) ? $_POST["phone"] : $auth->UserRow["phone"])?>">
												</div>
												<div class="form-group col-md-4">
													<label for="contactmethod">Επιθυμητός τρόπος επικοινωνίας</label>
													<select id="contactmethod" name="contactmethod" class="form-control">
														<option <?=(intval($_POST['contactmethod'])==0 && intval($auth->UserRow["contactmethod"])==0?' selected':'')?>>Επιλογή...</option>
														<option <?=(intval($_POST['contactmethod'])==0 && intval($auth->UserRow["contactmethod"])==1?' selected':'')?> value="1">Κινητό τηλέφωνο</option>
														<option <?=(intval($_POST['contactmethod'])==0 && intval($auth->UserRow["contactmethod"])==2?' selected':'')?> value="2">Σταθερό τηλέφωνο</option>
														<option <?=(intval($_POST['contactmethod'])==0 && intval($auth->UserRow["contactmethod"])==3?' selected':'')?> value="3">Email</option>
													</select>
												</div>
											</div>
											<!-- <hr class="dotted tall">-->
											<hr class="dotted">
											<h4 class="mb-3">Αλλαγή κωδικού</h4>
											<div class="form-row">
												<div class="form-group col-md-6">
													<label for="pw">Νέος κωδικός</label>
													<input type="password" class="form-control" id="pw" name="pw"  placeholder="Password">
												</div>
												<div class="form-group col-md-6">
													<label for="pw_new">Επανάληψη κωδικού</label>
													<input type="password" class="form-control" id="pw_new" name="pw_new" placeholder="Password">
												</div>
											</div>
											<div class="form-row">
												<div class="col-md-12 text-right mt-3">
													<a href="#" onClick="cm('SAVE',1,0,'');"><button type="button" class="btn btn-primary modal-confirm">Αποθήκευση</button></a>
												</div>
											</div>

										</form>

									</div>
								</div>
							</div>
						</div>
						

					</div>