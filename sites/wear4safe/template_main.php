<?php defined( '_VALID_PROCCESS' ) or die( 'Direct Access to this location is not allowed.' ); ?>

<?
session_start();
	//include($config["physicalPath"]."/languages/".$auth->LanguageCode.".php");
	include($config["physicalPath"]."languages/gr.php");
	if(!isset($config["navigation"])) $config["navigation"] = appTitle;
?>
<? 
	LoadNoCacheHeader();
	LoadCharSetHeader();
?>
<!doctype html>
<html class="sidebar-light sidebar-left-big-icons">
	<head>
	  
		<? 
			$site_title = site_title;
			if(isset($config["title"])) {$site_title = strip_tags($config["title"]);}
			else if(isset($config["navigation"])) {$site_title = strip_tags($config["navigation"]);}
		?>
		<title><?=$site_title?></title>
		<meta http-equiv="Content-Type" content="text/html; charset=<?=$auth->LanguageCharset?>"/>
		<meta name="description" content="<?=(isset($config["metaDesciption"]) ? $config["metaDesciption"] : "")?>" />
		<meta name="keywords" content="<?=(isset($config["metaKeys"]) ? $config["metaKeys"] : "")?>" />
		<script language="javascript">
			var recordSelect="<?=core_recordSelect;?>"; 
			var CurrentLanguage = "<?=$auth->LanguageCode?>";
			var BaseUrl = "<?=$config["siteurl"]?>";
		</script>
		<script language="javascript" type="text/javascript" src="gms/client_scripts/core.js"></script>
		<script language="javascript" type="text/javascript" src="gms/client_scripts/public.js"></script>
		<link rel="stylesheet" type="text/css" href="<?=$config["siteurl"]?>sites/<?=$config["site"]?>/theme.css">
        <script type="text/javascript" src="/gms/client_scripts/ajax/ajaxAgent.js"></script>
        <script type="text/javascript">var ajaxUrl= "<?=$config["ajaxUrl"]?>"</script>
		<script type="text/javascript" src="/gms/client_scripts/swfobject/swfobject.js"></script>
		<!-- Basic -->
		<meta charset="UTF-8">
		<meta name="author" content="dotsoft">

		<!-- Mobile Metas -->
		<meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no" />

		<!-- Web Fonts  -->
		<link href="https://fonts.googleapis.com/css?family=Open+Sans:300,400,600,700,800|Shadows+Into+Light" rel="stylesheet" type="text/css">

		<!-- Vendor CSS -->
		<link rel="stylesheet" href="vendor/bootstrap/css/bootstrap.css" />
		<!-- <link rel="stylesheet" type="text/css" media="screen" href="//maxcdn.bootstrapcdn.com/bootstrap/3.3.1/css/bootstrap.min.css" />-->
		<link rel="stylesheet" href="vendor/animate/animate.css">

		<link rel="stylesheet" href="vendor/font-awesome/css/all.min.css" />
		<link rel="stylesheet" href="//maxcdn.bootstrapcdn.com/font-awesome/4.3.0/css/font-awesome.min.css"> <!--new-->
		
		
		<link rel="stylesheet" href="vendor/magnific-popup/magnific-popup.css" />
		<link rel="stylesheet" href="vendor/select2/css/select2.min.css" />
		<!-- 
		<link rel="stylesheet" href="vendor/bootstrap-datepicker/css/bootstrap-datepicker3.css" />
		-->

		<!-- Specific Page Vendor CSS -->
		<link rel="stylesheet" href="vendor/jquery-ui/jquery-ui.css" />
		<link rel="stylesheet" href="vendor/jquery-ui/jquery-ui.theme.css" />
		<link rel="stylesheet" href="vendor/bootstrap-multiselect/css/bootstrap-multiselect.css" />
		<link rel="stylesheet" href="vendor/datatables/media/css/dataTables.bootstrap4.css" />
		<link rel="stylesheet" href="vendor/morris/morris.css" />

		<!-- Theme CSS -->
		<link rel="stylesheet" href="css/theme.css" />

		<!-- Skin CSS -->
		<link rel="stylesheet" href="css/skins/default.css" />

		<!-- Theme Custom CSS -->
		<link rel="stylesheet" href="css/custom.css">

		<!-- Head Libs -->
		<script src="vendor/modernizr/modernizr.js"></script>
		<script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/2.9.3/Chart.min.js"></script>
		<!-- heatmap -->
		<!-- <link rel="stylesheet" href="css/heatmap.css"> -->
		<!-- <script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyAzYeS10uHq9OvcW4G3NKRoNT3AJmt1RI0"></script> -->
		<?if(isset($_GET['com']) && $_GET['com']=='heatmap'){?>
			<link rel="stylesheet" href="https://unpkg.com/leaflet@latest/dist/leaflet.css" />
			<script src="https://unpkg.com/leaflet@latest/dist/leaflet.js"></script>
			<script src='https://unpkg.com/leaflet.gridlayer.googlemutant@latest/Leaflet.GoogleMutant.js'></script>
			<script src="js/heatmap.min.js"></script>
			<script src="js/leaflet-heatmap.js"></script>
			<!-- 
			<style>
				.demo1-wrapper {position: absolute;top: 0;bottom: 0;width: 99%;height:99%;}
			</style>
			-->
		<? } ?>
		<style>
		.footer {
			position: sticky;
			left: 0;
			bottom: 0;
			width: 100%;
			/*background-color: #ddd;*/
			color: white;
			text-align: center;
		}
		</style>
		<?if(isset($_GET['com']) && $_GET['com']=='analogcluster'){?>
			<style>
				.cluster {
				  display: table;
				  box-shadow: 0px 0px 1px 1px rgba(0, 0, 0, 0.5);
				}
				.cluster img {
				  display: none
				}
				.cluster div {
				  color: inherit !important;
				  display: table-cell;
				  vertical-align: middle;
				  width: 100% !important;
				  height: 100% !important;
				  line-height: inherit !important;
				}
				/*custom cluster-styles*/
				.cluster_blue {
				  background: rgba(0,0,255,0.3);
				  /*
				  width:50px !important;
				  height:50px !important;				  
				  */

				}
			</style>
		
			<script src="js/markerclusterer.js"></script>
		<? } ?>

		<?if(isset($_GET['com']) && $_GET['com']=='cluster'){?>
			<script src="js/markerclusterer.js"></script>
			<style>
			  #map-container {
				width: 100%;
			  }
			  #map {
				width: 1200px;
				width: 100%;
				position:relative;
				height: 400px;
			  }
			</style>
		<? } ?>

		<style>
		#uploader {
			width: 100%; 
			height: 100%; 
			background: #ccc;
			/*background: #6c757d;*/
			/*padding: 10px;*/
		}
		#uploader.highlight {
			background:#dc3545;
		}
		#uploader.disabled {
			background:#aaa;
		}
		.blink_me {
		  animation: blinker 1s linear infinite;
		}

		@keyframes blinker {
			50% {
				opacity: 0;
			}
		}
		#holder.hover { border: 10px dashed #0c0 !important; }
		</style>
		<link rel="stylesheet" type="text/css" href="/css/upload/upload.css" /> 
		<link rel="stylesheet" type="text/css" href="/css/upload/uploadcomponent.css" />
		
		<!-- end heatmap
		<script src="vendor/jquery/jquery.js"></script>		
		<script src="vendor/bootstrap/js/bootstrap.js"></script>
		<link rel="stylesheet" type="text/css" media="screen" href="//maxcdn.bootstrapcdn.com/bootstrap/3.3.1/css/bootstrap.min.css" />-->
		<script src="vendor/jquery/jquery.js"></script>	
	
		<script src="/js/upload/jquery.custom-file-input.js"></script>
		<script src="/js/jquery.stickr.min.js"></script>
		
		<script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyC7wuT0z4vbl63W_o8JNsSk5D02vCQd05M"></script>
		<script src="js/jquery-gmaps-latlon-picker.js"></script>
		<link rel="stylesheet" href="css/jquery-gmaps-latlon-picker.css">
	<!--<script src="vendor/bootstrap/js/bootstrap.js"></script>-->
	
    <!--<script type="text/javascript" src="//code.jquery.com/jquery-2.1.1.min.js"></script>
     <script type="text/javascript" src="//maxcdn.bootstrapcdn.com/bootstrap/3.3.1/js/bootstrap.min.js"></script>
	<link href="//cdn.rawgit.com/Eonasdan/bootstrap-datetimepicker/e8bddc60e73c1ec2475f827be36e1957af72e2ea/build/css/bootstrap-datetimepicker.css" rel="stylesheet">
	<script src="//cdnjs.cloudflare.com/ajax/libs/moment.js/2.9.0/moment-with-locales.js"></script>
    <script src="//cdn.rawgit.com/Eonasdan/bootstrap-datetimepicker/e8bddc60e73c1ec2475f827be36e1957af72e2ea/src/js/bootstrap-datetimepicker.js"></script>
    -->
<!-- <script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>- ->
<script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.22.2/moment.min.js"></script>
<script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/tempusdominus-bootstrap-4/5.0.1/js/tempusdominus-bootstrap-4.min.js"></script>
<! --<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/tempusdominus-bootstrap-4/5.0.1/css/tempusdominus-bootstrap-4.min.css" />- ->
	<link href="//cdn.rawgit.com/Eonasdan/bootstrap-datetimepicker/e8bddc60e73c1ec2475f827be36e1957af72e2ea/build/css/bootstrap-datetimepicker.css" rel="stylesheet">-->

<!-- <script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>-->
<script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.22.2/moment.min.js"></script>
<script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/tempusdominus-bootstrap-4/5.0.1/js/tempusdominus-bootstrap-4.min.js"></script>
<!----><link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/tempusdominus-bootstrap-4/5.0.1/css/tempusdominus-bootstrap-4.min.css" />
	<link href="//cdn.rawgit.com/Eonasdan/bootstrap-datetimepicker/e8bddc60e73c1ec2475f827be36e1957af72e2ea/build/css/bootstrap-datetimepicker.css" rel="stylesheet">	
	<style>
		canvas {
			-moz-user-select: none;
			-webkit-user-select: none;
			-ms-user-select: none;
		}
	</style>
	</head>
	<body>
        <form id="__PageForm" name="__PageForm" method="post" enctype="multipart/form-data" onSubmit="return PageIsValid();" novalidate="novalidate">
		<!-- <form autocomplete="off" id="__PageForm" name="__PageForm" method="post" enctype="multipart/form-data" onSubmit="return PageIsValid();"  class='form-validate'><input type="hidden" name="Command" id="Command" value="-1"> -->
		<input type="hidden" name="Command" id="Command" value="-1">
		
		<section class="body">

			<!-- start: header -->
			<header class="header">
				<div class="logo-container">
					<a href="index.php" class="logo" style="margin:2px 0 0 10px;">
						<img src="/gallery/site/wear4safe.png" style="width:80px;" alt="wear4safe logo" />
					</a>
					<div class="d-md-none toggle-sidebar-left" data-toggle-class="sidebar-left-opened" data-target="html" data-fire-event="sidebar-left-opened">
						<i class="fas fa-bars" aria-label="Toggle sidebar"></i>
					</div>
				</div>
			
				<!-- start: search & user box -->
				<div class="header-right">
					<!-- 
					<div action="pages-search-results.html" class="search nav-form">
						<div class="input-group">
							<input type="text" class="form-control" name="q" id="q" placeholder="Search...">
							<span class="input-group-append">
								<button class="btn btn-default" type="submit"><i class="fas fa-search"></i></button>
							</span>
						</div>
					</div>
					-->

			
					<span class="separator"></span>
			
					<ul class="notifications">
						<li>
							<a href="index.php?com=messageslist" class="dropdown-toggle notification-icon">
								<i class="fas fa-envelope"></i>
								<span class="badge">
								<?
								$totalUnread=$db->RowSelectorQuery("SELECT count(*) AS totalUnread FROM messages WHERE user_id=".$auth->UserId." AND readed='False'");
								echo intval($totalUnread['totalUnread']);
								?>
								</span>
							</a>
						</li>
						<li>
							<a href="index.php?com=notificationslist" class="dropdown-toggle notification-icon"><!-- data-toggle="dropdown"-->
								<i class="fas fa-bell"></i>
									<?
										$totalNotifications=$db->RowSelectorQuery("SELECT count(*) AS totalAll FROM notifications WHERE is_valid='True'");
										$totalRead=$db->RowSelectorQuery("SELECT count(*) AS totalRead FROM notifications t1 LEFT JOIN notificationread t2 ON t1.notification_id=t2.notification_id WHERE t1.is_valid='True' AND t2.user_id=".$auth->UserRow['user_id']);
										//echo "SELECT count(*) AS totalRead FROM notifications t1 LEFT JOIN notificationread t2 ON t1.notification_id=t2.notification_id WHERE t1.is_valid='True' AND t2.user_id=".$auth->UserRow['user_id'];
									?>
								<span class="badge"><?=($totalNotifications['totalAll']-$totalRead['totalRead'])?></span>
							</a>
						</li>
					</ul>
			
					<span class="separator"></span>
			
					<div id="userbox" class="userbox">
						<a href="#" data-toggle="dropdown">
							<figure class="profile-picture">
								<img src="img/logo-person.png" alt="Joseph Doe" class="rounded-circle" data-lock-picture="img/!logged-user.jpg" />
							</figure>
							<div class="profile-info" data-lock-name="John Doe" data-lock-email="johndoe@okler.com">
								<span class="name"><?=$auth->UserRow['user_fullname']?></span>
								<span class="role"><?=$auth->UserRow['user_auth']?></span>
							</div>
			
							<i class="fa custom-caret"></i>
						</a>
			
						<div class="dropdown-menu">
							<ul class="list-unstyled mb-2">
								<li class="divider"></li>
								<li>
									<a role="menuitem" tabindex="-1" href="index.php?com=profile"><i class="fas fa-user"></i> Προφιλ</a>
								</li>
								<!--<li><a role="menuitem" tabindex="-1" href="#" data-lock-screen="true"><i class="fas fa-lock"></i> Lock Screen</a></li>-->
								<li><a role="menuitem" tabindex="-1" href="index.php?com=login&logout=true"><i class="fas fa-power-off"></i> Αποσύνδεση</a></li>
							</ul>
						</div>
					</div>
				</div>
				<!-- end: search & user box -->
			</header>
			<!-- end: header -->

			<div class="inner-wrapper">
				<!-- start: sidebar -->
				<aside id="sidebar-left" class="sidebar-left">
				
				    <div class="sidebar-header">
				        <div class="sidebar-title">
				            Επιλογές
				        </div>
						
				        <div class="sidebar-toggle d-none d-md-block" data-toggle-class="sidebar-left-collapsed" data-target="html" data-fire-event="sidebar-left-toggle">
				            <i class="fas fa-bars" aria-label="Toggle sidebar"></i>
				        </div>
				    </div>
				
				    <div class="nano">
				        <div class="nano-content">
				            <nav id="menu" class="nav-main" role="navigation">
				            
				                <ul class="nav nav-main">
				                    <li>
				                        <a class="nav-link" href="index.php">
				                            <i class="fas fa-home" aria-hidden="true"></i><span>Αρχική</span>
				                        </a>                        
				                    </li>
									
									<li class="nav-parent <?=(isset($_GET['com']) && ($_GET['com']=='professions' || $_GET['com']=='map' || $_GET['com']=='maptypes' || $_GET['com']=='legislations')?'nav-active':'')?>">
										<a class="nav-link" href="#">
											<i class="fas fa-globe-europe" aria-hidden="true"></i>
											<span>Οργάνωση</span>
										</a>
										<ul class="nav nav-children">
											<li><a class="nav-link" href="index.php?com=professions">Επαγγέλματα</a></li>
											<li><a class="nav-link" href="index.php?com=map">Κατηγορίες ΜΑΠ</a></li>
											<li><a class="nav-link" href="index.php?com=maptypes">Τύποι ΜΑΠ</a></li>
											<li><a class="nav-link" href="index.php?com=legislations">Νομοθετήματα</a></li>
											<? if($auth->UserType == "Administrator"){?>
											<li><a class="nav-link" href="index.php?com=roles">Ρόλοι</a></li>
											<? } ?>
											<li><a class="nav-link" href="index.php?com=conditions">Συνθήκες</a></li>
											<li><a class="nav-link" href="index.php?com=sectors">Τομείς</a></li>
											<? if($auth->UserType == "Administrator" || $auth->UserRow['parent']==0) {?>
											<li><a class="nav-link" href="index.php?com=prosettings">Ρυθμίσεις ΜΑΠ</a></li>
											<? } ?>
										</ul>
									</li>
									
									<? if($auth->UserType == "Administrator"){?>
				                    <li class="<?=($_GET['com']=='organizations'?'nav-parent nav-active':'')?>">
				                        <a class="nav-link" href="index.php?com=organizations">
				                             <i class="fas fa-map-marker-alt" aria-hidden="true"></i><span>Οργανισμοί</span>
										   <!--<i class="fas fa-globe-europe" aria-hidden="true"></i><span>Οργανισμοί</span>-->
				                        </a>
				                    </li>
									<? } ?>
									
									<!-- 
									<li class="<? //=($_GET['com']=='locations'?'nav-parent nav-active':'')?>">
										<a class="nav-link" href="index.php?com=locations">
											<i class="fas fa-globe-europe" aria-hidden="true"></i><span>Τοποθεσίες</span><!- - fas fa-file-alt- ->
										</a>                        
									</li>
									-->

									

									
									<? if($auth->UserType == "Administrator" || $auth->UserRow['parent']==0){?>
									<li class="<?=($_GET['com']=='users'?'nav-parent nav-active':'')?>">
				                        <a class="nav-link" href="index.php?com=users">
				                            <i class="fas fa-user" aria-hidden="true"></i><span>Χρήστες</span>
				                        </a>                        
				                    </li>
									<? } ?>
									
				                    <li class="<?=($_GET['com']=='employees'?'nav-parent nav-active':'')?>">
				                        <a class="nav-link" href="index.php?com=employees">
				                            <i class="fas fa-users" aria-hidden="true"></i><span>Εργαζόμενοι</span>
				                        </a>                        
				                    </li>
									
									<? if($auth->UserType == "Administrator" || $auth->UserRow['parent']==0) {?>
				                    <li class="<?=($_GET['com']=='sensors'?'nav-parent nav-active':'')?>">
				                        <a class="nav-link" href="index.php?com=sensors">
				                            <i class="fas fa-keyboard fa-fw" aria-hidden="true"></i><span>Αισθητήρες</span>
				                        </a>                        
				                    </li>		
									<? } ?>
				                    <li class="<?=($_GET['com']=='assignments'?'nav-parent nav-active':'')?>">
				                        <a class="nav-link" href="index.php?com=assignments">
				                            <i class="fas fa-user-shield fa-fw" aria-hidden="true"></i><span>Ενεργές Αναθέσεις</span>
				                        </a>                        
				                    </li>	
									
				                    <li class="<?=($_GET['com']=='events'?'nav-parent nav-active':'')?>">
				                        <a class="nav-link" href="index.php?com=events">
				                            <i class="fas fa-exclamation fa-fw" aria-hidden="true"></i><span>Συμβάντα</span>
				                        </a>                        
				                    </li>	
									
									<!--
				                    <li>
				                        <a class="nav-link" href="index.php?com=roles">
				                            <i class="fas fa-tasks" aria-hidden="true"></i><span>Ρόλοι</span>
				                        </a>                        
				                    </li>
				                    <li>
				                        <a class="nav-link" href="index.php?com=passwords">
				                            <i class="fas fa-lock" aria-hidden="true"></i><span>Κωδικοί</span>
				                        </a>                        
				                    </li>
									-->
									<? if($auth->UserType == "Administrator"){?>
				                    <li class="<?=($_GET['com']=='messages'?'nav-parent nav-active':'')?>">
				                        <a class="nav-link" href="index.php?com=messages">
				                            <!-- <span class="float-right badge badge-primary">15</span> -->
				                            <i class="fas fa-envelope" aria-hidden="true"></i><span>Μηνύματα</span>
				                        </a>                        
				                    </li>
				                    <li  class="<?=($_GET['com']=='notifications'?'nav-parent nav-active':'')?>">
				                        <a class="nav-link" href="index.php?com=notifications">
				                            <!-- <span class="float-right badge badge-primary">2</span> -->
				                            <i class="fas fa-bell" aria-hidden="true"></i><span>Ειδοποιήσεις</span>
				                        </a>                        
				                    </li>
									<? } ?>
									<li class="nav-parent <?=(isset($_GET['com']) && ($_GET['com']=='report1' || $_GET['com']=='report2')?'nav-active':'')?>">
										<a class="nav-link" href="#">
											<i class="fas fa-poll" aria-hidden="true"></i>
											<span>Αναφορές</span>
										</a>
										<ul class="nav nav-children">
											<li><a class="nav-link" href="index.php?com=alerts">Alerts</a></li>
											<li><a class="nav-link" href="index.php?com=rawdata">Λίστα εγγραφών</a></li>
											<li><a class="nav-link" href="index.php?com=lawdata">Νομοθεσία</a></li>
											<li><a class="nav-link" href="index.php?com=assignmentslist">Ιστορικό αναθέσεων</a></li>
											<li><a class="nav-link" href="index.php?com=problems">Αναφορές προβλημάτων</a></li>
											
												<li><a class="nav-link" href="index.php?com=mapsettings">Ρυθμίσεις ΜΑΠ</a></li>
											
										</ul>
									</li>
				                </ul>
				            </nav>
				        </div>
				
				        <script>
				            // Maintain Scroll Position
				            if (typeof localStorage !== 'undefined') {
				                if (localStorage.getItem('sidebar-left-position') !== null) {
				                    var initialPosition = localStorage.getItem('sidebar-left-position'),
				                        sidebarLeft = document.querySelector('#sidebar-left .nano-content');
				                    
				                    sidebarLeft.scrollTop = initialPosition;
				                }
				            }
				        </script>
				    </div>
				</aside>

				<section role="main" class="content-body" style="min-height: calc(100vh - 70px);">
					<header class="page-header">
						<h2>Πληροφοριακό σύστημα διαχείρισης μέσων ατομικής προστασίας</h2>
						<div class="right-wrapper text-right">
							<ol class="breadcrumbs" style="margin-right:50px;">
								<li>
									<a href="index.php">
										<i class="fas fa-home"></i>
									</a>
								</li>
								<li><span><?=$config["navigation"]?></span></li>
							</ol>
							<!-- <a class="sidebar-right-toggle" data-open="sidebar-right"><i class="fas fa-chevron-left"></i></a> -->
						</div>
					</header>

					<? if(!isset($_GET['com'])){?>
					<div class="row">
						<div class="col-lg-12 mb-12">
							<section class="card">
								<div class="card-body">
									Εφαρμογή Wear4safe

									<div class="row">
										<div class="col-lg-12 col-xl-12">
											<h5 class="font-weight-semibold text-dark text-uppercase mb-3 mt-3">Δεδομενα <span id="txt" style="float:right; margin-right:30px;"></span></h5>
										</div>
									</div>
									<div class="row">
										<div class="col-lg-6 col-xl-4">
											<section class="card card-featured-left card-featured-primary mb-4">
												<div class="card-body">
													<div class="widget-summary">
														<div class="widget-summary-col widget-summary-col-icon">
															<div class="summary-icon bg-primary">
																<i class="fas fa-life-ring"></i>
															</div>
														</div>
														<div class="widget-summary-col">
															<div class="summary">
																<?
																$employeesRow = $db->RowSelectorQuery("SELECT count(*) AS totalEmployees FROM employees WHERE 1=1".($auth->UserType!='Administrator'?" AND organization_id=".$auth->UserRow['organization_id']:""));
																?>
																<h4 class="title">Εργαζόμενοι</h4>
																<div class="info">
																	<strong class="amount"><?=$employeesRow['totalEmployees']?></strong>
																	<span class="text-primary"> </span>
																</div>
															</div>
															<div class="summary-footer">
																<a class="text-muted text-uppercase"> </a>
															</div>
														</div>
													</div>
												</div>
											</section>
										</div>
										<div class="col-lg-6 col-xl-4">
											<section class="card card-featured-left card-featured-primary mb-4">
												<div class="card-body">
													<div class="widget-summary">
														<div class="widget-summary-col widget-summary-col-icon">
															<div class="summary-icon bg-primary">
																<i class="fas fa-life-ring"></i>
															</div>
														</div>
														<div class="widget-summary-col">
															<div class="summary">
																<?
																$assignmentsRow = $db->RowSelectorQuery(
																"SELECT count(*) AS totalAssignments FROM assignments WHERE 1=1 AND isnull(return_date)".
																($auth->UserType!='Administrator'?" AND user_id IN (SELECT user_id FROM users WHERE organization_id=".$auth->UserRow['organization_id'].")":"")
																);
																?>
																<h4 class="title">Αναθέσεις</h4>
																<div class="info">
																	<strong class="amount"><?=$assignmentsRow['totalAssignments']?> </strong>
																	<span class="text-primary"> </span>
																</div>
															</div>
															<div class="summary-footer">
																<a class="text-muted text-uppercase"> </a>
															</div>
														</div>
													</div>
												</div>
											</section>
										</div>
										<div class="col-lg-6 col-xl-4">
											<section class="card card-featured-left card-featured-primary mb-4">
												<div class="card-body">
													<div class="widget-summary">
														<div class="widget-summary-col widget-summary-col-icon">
															<div class="summary-icon bg-primary">
																<i class="fas fa-life-ring"></i>
															</div>
														</div>
														<div class="widget-summary-col">
															<div class="summary">
																<?
																$sensorsRow = $db->RowSelectorQuery(
																"SELECT count(*) AS totalSensors FROM sensors WHERE 1=1 ".
																($auth->UserType!='Administrator'?" AND organization_id=".$auth->UserRow['organization_id']:"")
																);
																?>
																<h4 class="title">Αισθητήρες</h4>
																<div class="info">
																	<strong class="amount"><?=$sensorsRow['totalSensors']?></strong>
																	<span class="text-primary"> </span>
																</div>
															</div>
															<div class="summary-footer">
																<a class="text-muted text-uppercase"> </a>
															</div>
														</div>
													</div>
												</div>
											</section>
										</div>
									</div>	
										
									<div class="row">
										<div class="col-lg-6 col-xl-4">
											<h5 class="font-weight-semibold text-dark text-uppercase mb-3 mt-3">Δεδομενα</h5>
										</div>
									</div>
										
									<div class="row">	
										<div class="col-lg-12 col-xl-4">
											<section class="card mb-4">
												<div class="card-body bg-primary">
													<div class="widget-summary">
														<div class="widget-summary-col widget-summary-col-icon">
															<div class="summary-icon">
																<i class="fas fa-book-open"></i>
															</div>
														</div>
														<div class="widget-summary-col">
															<div class="summary">
																<h4 class="title">Εγγραφές </h4>
																<div class="info">
																	<strong class="amount"><?
																	$totalRows=$db->RowSelectorQuery("SELECT count(*) AS total FROM data");
																	echo $totalRows['total'];
																	//=number_format($dataArr['R'],4)
																	
																	?></strong>
																</div>
															</div>
															<div class="summary-footer">
																<a class="text-uppercase"> </a>
															</div>
														</div>
													</div>
												</div>
											</section>
										</div>
										<div class="col-lg-12 col-xl-4">	
											<section class="card mb-4">
												<div class="card-body bg-secondary">
													<div class="widget-summary">
														<div class="widget-summary-col widget-summary-col-icon">
															<div class="summary-icon">
																<i class="fas fa-exclamation-triangle"></i>
															</div>
														</div>
														<div class="widget-summary-col">
															<div class="summary">
																<h4 class="title">Ειδοποιήσεις</h4>
																<div class="info">
																	<strong class="amount"><?
																	$totalAlerts=$db->RowSelectorQuery("SELECT count(*) AS total FROM alerts");
																	echo $totalAlerts['total'];
																	//=number_format($dataArr['R1'],4)?></strong>
																</div>
															</div>
															<div class="summary-footer">
																<a class="text-uppercase"> </a>
															</div>
														</div>
													</div>
												</div>
											</section>
										</div>
										<div class="col-lg-12 col-xl-4">		
											<section class="card mb-4">
												<div class="card-body bg-tertiary">
													<div class="widget-summary">
														<div class="widget-summary-col widget-summary-col-icon">
															<div class="summary-icon">
																<i class="fas fa-clock"></i>
															</div>
														</div>
														<div class="widget-summary-col">
															<div class="summary">
																<h4 class="title">Χρήση</h4>
																<div class="info">
																	<strong class="amount"><?=number_format(($totalRows['total']/60),2)?> ώρες</strong>
																</div>
															</div>
															<div class="summary-footer">
																<a class="text-uppercase"> </a>
															</div>
														</div>
													</div>
												</div>
											</section>
										</div>	
									</div>
								</div>
							</section>
						</div>
					</div>
					
					
					<div class="row">
						<div class="col-lg-12 mb-12">
						<!-- <div class="col-lg-6 col-xl-3">-->
							<section class="card card-transparent">
								<header class="card-header">
									<div class="card-actions">
										<a href="#" class="card-action card-action-toggle" data-card-toggle></a>
										<a href="#" class="card-action card-action-dismiss" data-card-dismiss></a>
									</div>
					
									<h2 class="card-title">KPIs</h2>
								</header>
								<div class="card-body">
									<div class="row">
										<?
										$dataRow = $db->RowSelectorQuery("SELECT count(*) AS totalData FROM data");
										$alertsRow = $db->RowSelectorQuery("SELECT count(*) AS totalAlerts FROM alerts");
										$KPI1 = (1-(intval($alertsRow['totalAlerts']) / intval($dataRow['totalData'])))*100;
										//SELECT COUNT(DISTINCT DATE(dt_server)) as days FROM data; //total days
										// WHERE sensor_id IN (SELECT * FROM sensors WHERE organization_id = $auth->UserRow['organization_id'])
										?>
										<div class="col-lg-4 mb-4">
											<section class="card">
												<div class="card-body">
													<div class="circular-bar circular-bar-xs m-0 mt-1 mr-4 mb-0 float-right">
														<div class="circular-bar-chart" data-percent="<?=$KPI1?>" data-plugin-options='{ "barColor": "#2baab1", "delay": 300, "size": 50, "lineWidth": 4 }'>
															<strong>Average</strong>
															<label><span class="percent"><?=($KPI1)?></span>%</label>
														</div>
													</div>
													<div class="h4 font-weight-bold mb-0"><?=number_format($KPI1,2)?>%</div>
													<p class="text-3 text-muted mb-0">Ποσοστό εργαζομένων που φορούν τα αναγκαία ΜΑΠ</p>

												</div>
											</section>
										</div>
										<div class="col-lg-4 mb-4">
											<section class="card">
												<div class="card-body">
													<div class="circular-bar circular-bar-xs m-0 mt-1 mr-4 mb-0 float-right">
														<div class="circular-bar-chart" data-percent="98" data-plugin-options='{ "barColor": "#2baab1", "delay": 300, "size": 50, "lineWidth": 4 }'>
															<strong>Average</strong>
															<label><span class="percent">98</span>%</label>
														</div>
													</div>
													<div class="h4 font-weight-bold mb-0">98%</div>
													<p class="text-3 text-muted mb-0">Ποσοστό εργαζομένων με χαμηλή επικινδυνότητα σε συναγερμούς</p>
												</div>
											</section>
										</div>
										<div class="col-lg-4 mb-4">
											<section class="card">
												<div class="card-body">
													<div class="circular-bar circular-bar-xs m-0 mt-1 mr-4 mb-0 float-right">
														<?
														$eventsRow = $db->RowSelectorQuery("SELECT count(*) AS totalEvents FROM events WHERE 1=1 ".($auth->UserType!='Administrator'?" AND  employee_id IN (SELECT employee_id FROM employees WHERE organization_id=".$auth->UserRow['organization_id'].")":""));
														?>
														<div class="circular-bar-chart" data-percent="<?=$eventsRow['totalEvents']?>" data-plugin-options='{ "barColor": "#2baab1", "delay": 300, "size": 50, "lineWidth": 4 }'>
															<strong>Average</strong>

															<label><span class="percent"><?=$eventsRow['totalEvents']?></span></label>
														</div>
													</div>
													<div class="h4 font-weight-bold mb-0"><?=$eventsRow['totalEvents']?></div>
													<p class="text-3 text-muted mb-0">Αριθμός ατυχημάτων / συμβάντων σε εργαζόμενους σε ετήσια βάση</p>
												</div>
											</section>
										</div>
									</div>
								</div>
							</section>
						</div>
					</div>
				
					<div class="row">
						<div class="col-lg-6">
							<section class="card">
								<header class="card-header">
									<div class="card-actions">
										<a href="#" class="card-action card-action-toggle" data-card-toggle></a>
										<a href="#" class="card-action card-action-dismiss" data-card-dismiss></a>
									</div>
					
									<h2 class="card-title">Συμβάντα SOS</h2>
									<p class="card-subtitle">Αριθμός συμβάντων SOS ανα ημέρα για τις τελευταίες 30 ημέρες.</p>
								</header>
								<div class="card-body">
									<div class="chart chart-md" id="flotBars"></div>
									<script type="text/javascript">
										<?
										$flotBarsData = "";
										$filter.=($auth->UserType != "Administrator"?' AND sensor_id IN (SELECT sensor_id FROM sensors WHERE organization_id='.$auth->UserRow['organization_id'].')' :'');
										$query = "SELECT count(*) AS sos, date(dt_server) AS dtServer FROM alerts WHERE sos=1 ".$filter." GROUP BY date(dt_server);";
										$result = $db->sql_query($query);
										while ($dr = $db->sql_fetchrow($result)) {
											$flotBarsData.='["'.$dr['dtServer'].'",'.$dr['sos'].'],';
										}
										$flotBarsData = substr($flotBarsData, 0, -1);
										?>	
										
										var flotBarsData = [
											<?=$flotBarsData?>
										];
					
										// See: js/examples/examples.charts.js for more settings.
									</script>
					
								</div>
							</section>
						</div>
						<div class="col-lg-6">
							<section class="card">
								<header class="card-header">
									<div class="card-actions">
										<a href="#" class="card-action card-action-toggle" data-card-toggle></a>
										<a href="#" class="card-action card-action-dismiss" data-card-dismiss></a>
									</div>
					
									<h2 class="card-title">Alerts</h2>
									<p class="card-subtitle">Ημερίσιος αριθμός alerts για τις τελευταίες 30 μέρες</p>
								</header>
								<div class="card-body">
					
									<!-- Flot: Bars -->
									<div class="chart chart-md" id="flotBars2"></div>
									<script type="text/javascript">
										<?
										$flotBarsData2 = "";
										$filter2.=($auth->UserType != "Administrator"?' AND sensor_id IN (SELECT sensor_id FROM sensors WHERE organization_id='.$auth->UserRow['organization_id'].')' :'');
										$query2 = "SELECT count(*) AS sos, date(dt_server) AS dtServer FROM alerts WHERE 1=1 ".$filter2." GROUP BY date(dt_server);";
										
										$result2 = $db->sql_query($query2);
										while ($dr2 = $db->sql_fetchrow($result2)) {
											$date=date_create($dr2['dtServer']);
											$flotBarsData2.='["'.date_format($date,"d/m").'",'.$dr2['sos'].'],';
										}
										$flotBarsData2 = substr($flotBarsData2, 0, -1);
										?>	
										var flotBarsData2 = [
											<?=$flotBarsData2?>
										];
									</script>
					
								</div>
							</section>
						</div>
					</div>
					
					<div class="row">
						<div class="col-lg-6">
							<section class="card">
								<header class="card-header">
									<div class="card-actions">
										<a href="#" class="card-action card-action-toggle" data-card-toggle></a>
										<a href="#" class="card-action card-action-dismiss" data-card-dismiss></a>
									</div>
					
									<h2 class="card-title">Συμβάντα / Τομέα</h2>
									<p class="card-subtitle">% ποσοστό συμβάντων ανα τομέα τις τελευταίες 30 μέρες</p>
								</header>
								<div class="card-body">
									<div class="chart chart-md" id="flotBars3"></div>
									<script type="text/javascript">
										<?
										$flotBarsData3 = "";
										$filter3=($auth->UserType != "Administrator"?' AND t1.sensor_id IN (SELECT sensor_id FROM sensors WHERE organization_id='.$auth->UserRow['organization_id'].')' :'');
										//$query = "SELECT count(*) AS sos, date(dt_server) AS dtServer FROM data WHERE sos=1 ".$filter." GROUP BY date(dt_server);";
										$query3="SELECT count(*) AS total, t4.sector_id,t4.sector_name FROM alerts t1 
										INNER JOIN assignments t2 ON t1.sensor_id=t2.sensor_id 
										INNER JOIN employees t3 ON t2.employee_id=t3.employee_id 
										INNER JOIN sectors t4 ON t3.sector_id=t4.sector_id
										WHERE 1=1 ".$filter3." AND t2.return_date IS NULL 
										GROUP BY t4.sector_id";
										
										$result3 = $db->sql_query($query3);
										while ($dr3 = $db->sql_fetchrow($result3)) {
											$flotBarsData3.='["'.$dr3['sector_name'].'",'.($dr3['total']/$totalAlerts['total']).'],';
										}
										//
										$flotBarsData3 = substr($flotBarsData3, 0, -1);
										?>	
										
										var flotBarsData3 = [
											<?=$flotBarsData3?>
										];
									</script>
								</div>
							</section>
						</div>
						<div class="col-lg-6">
							<section class="card">
								<header class="card-header">
									<div class="card-actions">
										<a href="#" class="card-action card-action-toggle" data-card-toggle></a>
										<a href="#" class="card-action card-action-dismiss" data-card-dismiss></a>
									</div>
									<h2 class="card-title">Συμβάντα / Άτομο</h2>
									<p class="card-subtitle">% ποσοστό συμβάντων ανα άτομο τελευταίες 30 μέρες</p>
								</header>
								<div class="card-body">
									<div class="chart chart-md" id="flotBars4"></div>
									<script type="text/javascript">
										<?
										$flotBarsData4 = "";
										$filter4=($auth->UserType != "Administrator"?' AND t1.sensor_id IN (SELECT sensor_id FROM sensors WHERE organization_id='.$auth->UserRow['organization_id'].')' :'');
										$query4="SELECT count(*) AS total, t3.employee_id, t3.surname FROM alerts t1 
										INNER JOIN assignments t2 ON t1.sensor_id=t2.sensor_id 
										INNER JOIN employees t3 ON t2.employee_id=t3.employee_id 
										INNER JOIN sectors t4 ON t3.sector_id=t4.sector_id
										WHERE 1=1 ".$filter4." AND t2.return_date IS NULL
										GROUP BY t3.employee_id";
										
										$result4 = $db->sql_query($query4);
										while ($dr4 = $db->sql_fetchrow($result4)) {
											$flotBarsData4.='["'.$dr4['employee_id'].'",'.($dr4['total']).'],';
											///$totalAlerts['total']
										}
										$flotBarsData4 = substr($flotBarsData4, 0, -1);
										?>	
										var flotBarsData4 = [
											<?=$flotBarsData4?>
										];
									</script>
					
								</div>
							</section>
						</div>
					</div>
				
					<? } else {?>
                    <div class="content" style="margin-top:10px;">
	                    <?=$components->RenderRequestComponent(); ?>
					</div>
					<? } ?>
					<div class="footer" style="margin-top:40px;">
						<div><img src="/gallery/site/Sticker-website_ETPA_GR.jpg" style="max-width:400px;margin-top:10px;margin-bottom:10px;"></div>
					</div>	
				</section>

			</div>

							
		</section>

		</form>
	
		<!-- Vendor -->
		<script language="javascript" type="text/javascript" src="<?=$config["siteurl"]?>sites/<?=$config["site"]?>/js/func.js"></script>
		<script src="vendor/jquery-browser-mobile/jquery.browser.mobile.js"></script>
		<script src="vendor/popper/umd/popper.min.js"></script>
		<script src="vendor/bootstrap/js/bootstrap.js"></script>
		<!-- 
		<script src="vendor/bootstrap-datepicker/js/bootstrap-datepicker.js"></script>
		-->
		
		<script src="vendor/common/common.js"></script>
		<script src="vendor/nanoscroller/nanoscroller.js"></script>
		<script src="vendor/magnific-popup/jquery.magnific-popup.js"></script>
		<script src="vendor/jquery-placeholder/jquery.placeholder.js"></script>
		
		<!-- Specific Page Vendor -->
		<script src="vendor/jquery-ui/jquery-ui.js"></script>
		<script src="vendor/jqueryui-touch-punch/jquery.ui.touch-punch.js"></script>
		<script src="vendor/jquery-appear/jquery.appear.js"></script>
		<script src="vendor/bootstrap-multiselect/js/bootstrap-multiselect.js"></script>
		<script src="vendor/jquery.easy-pie-chart/jquery.easypiechart.js"></script>
		<script src="vendor/flot/jquery.flot.js"></script>
		<script src="vendor/flot.tooltip/jquery.flot.tooltip.js"></script>
		<script src="vendor/flot/jquery.flot.pie.js"></script>
		<script src="vendor/flot/jquery.flot.categories.js"></script>
		<script src="vendor/flot/jquery.flot.resize.js"></script>
		<script src="vendor/jquery-sparkline/jquery.sparkline.js"></script>
		<script src="vendor/raphael/raphael.js"></script>
		<script src="vendor/morris/morris.js"></script>
		<script src="vendor/gauge/gauge.js"></script>
		<script src="vendor/snap.svg/snap.svg.js"></script>
		<script src="vendor/liquid-meter/liquid.meter.js"></script>
		<script src="vendor/chartist/chartist.js"></script>
		
		<script src="vendor/jqvmap/jquery.vmap.js"></script>
		<script src="vendor/jqvmap/data/jquery.vmap.sampledata.js"></script>
		<script src="vendor/jqvmap/maps/jquery.vmap.world.js"></script>
		<script src="vendor/jqvmap/maps/continents/jquery.vmap.africa.js"></script>
		<script src="vendor/jqvmap/maps/continents/jquery.vmap.asia.js"></script>
		<script src="vendor/jqvmap/maps/continents/jquery.vmap.australia.js"></script>
		<script src="vendor/jqvmap/maps/continents/jquery.vmap.europe.js"></script>
		<script src="vendor/jqvmap/maps/continents/jquery.vmap.north-america.js"></script>
		<script src="vendor/jqvmap/maps/continents/jquery.vmap.south-america.js"></script>

		<script src="vendor/jquery-validation/jquery.validate.js"></script>
		<script src="vendor/select2/js/select2.js"></script>
		<script src="vendor/datatables/media/js/jquery.dataTables.min.js"></script>
		<script src="vendor/datatables/media/js/dataTables.bootstrap4.min.js"></script>
		<!--
		<script src="vendor/bootstrap-wizard/jquery.bootstrap.wizard.js"></script>
		<script src="js/examples/examples.wizard.js"></script>
		 Theme Base, Components and Settings -->
		
		<script src="vendor/datatables/extras/TableTools/Buttons-1.4.2/js/dataTables.buttons.min.js"></script>
		<script src="vendor/datatables/extras/TableTools/Buttons-1.4.2/js/buttons.bootstrap4.min.js"></script>
		<script src="vendor/datatables/extras/TableTools/Buttons-1.4.2/js/buttons.html5.min.js"></script>
		<script src="vendor/datatables/extras/TableTools/Buttons-1.4.2/js/buttons.print.min.js"></script>
		<script src="vendor/datatables/extras/TableTools/JSZip-2.5.0/jszip.min.js"></script>
		<script src="vendor/datatables/extras/TableTools/pdfmake-0.1.32/pdfmake.min.js"></script>
		<script src="vendor/datatables/extras/TableTools/pdfmake-0.1.32/vfs_fonts.js"></script>
		
		<script src="js/theme.js"></script>
		
		<!-- Theme Custom -->
		<script src="js/custom.js"></script>
		
		<!-- Theme Initialization Files -->
		<script src="js/theme.init.js"></script>

		<!-- Examples -->
		<script src="js/examples/examples.datatables.default.js"></script>
		<script src="js/examples/examples.datatables.row.with.details.js"></script>
		<script src="js/examples/examples.datatables.tabletools.js"></script>
		<script src="vendor/dropzone/dropzone.js"></script>
		
		<!-- Examples validation-->
		<script src="js/examples/examples.validation.js"></script>
		
		
		<!-- Examples 
		<script src="js/examples/examples.dashboard.js"></script>-->
		<script src="js/examples/examples.charts.js?v=3"></script>
		
		
	</body>
	<? $validator->RenderValidators();?>
	<? $messages->RenderMessages();?>
</html>