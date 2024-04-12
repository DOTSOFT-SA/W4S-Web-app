<?php defined( '_VALID_PROCCESS' ) or die( 'Direct Access to this location is not allowed.' ); ?>
<?
	$FLAG_DEVICES = 1;
	$FLAG_CAMPAINS = 2;
	$FLAG_PASSWORDS = 4;
	$FLAG_PRODUCTS = 8;
	$FLAG_CATEGORIES = 16;
	$FLAG_ADS = 32;
	$FLAG_CREDITS = 64;
	$FLAG_RESELLERS = 128;
	$FLAG_SURVEYS = 256;
	$FLAG_FRIENDS = 512;
	$FLAG_RATING = 1024;
	$FLAG_STATS = 2048;
	$permissions = (intval($auth->UserRow['access'])>0?$auth->UserRow['access']:0);
	

//login, logout, lock checking here

//if ( isset($_SESSION["userprofile"]) ) {
//	LoginSocial($_SESSION["userprofile"]['id'],$_SESSION["userprofile"]['email'],$_SESSION["userprofile"]['name'], "Register");
//	//echo ($_SESSION["userprofile"]['id']."-".$_SESSION["userprofile"]['email']."-".$_SESSION["userprofile"]['name']."-". "Register");
//}

if(isset($_GET["logout"]) && $_GET["logout"] == "true")
{
	Logout();
	Redirect(CreateUrl());
}

if($auth->UserType != "" && isset($_GET["com"]) && $_GET["com"] == "locked")
{
	$_SESSION["locked_id"] = $auth->UserRow["user_id"];
	$_SESSION["user_photo"] = $auth->UserRow["user_photo"];
	$_SESSION["locked_user"] = $auth->UserRow["user_name"];
	$_SESSION["locked_user_fullname"] = $auth->UserRow["user_fullname"];
	Logout();
	Redirect("index.php?com=locked");
}

	if($auth->UserType != "")
	{

		if(isset($_GET["com"]) && ($_GET["com"] != "")){
			include("template_main.php");
		} else {
			include("template_main.php");
		}
		
	}
	else if(isset($_GET["com"]) && $_GET["com"] == "forgot")
	{
		include(dirname(__FILE__) . "/templates/public/forgot.php");
	}
	else if(isset($_GET["com"]) && $_GET["com"] == "contact")
	{
		include(dirname(__FILE__) . "/templates/public/contact.php");
	}
	else if(isset($_GET["com"]) && $_GET["com"] == "register")
	{
		include(dirname(__FILE__) . "/templates/public/register.php");
	}
	else if(isset($_GET["com"]) && $_GET["com"] == "locked")
	{
		include(dirname(__FILE__) . "/templates/public/locked.php");
	}
	else
	{
		include(dirname(__FILE__) . "/templates/public/login.php");
	}
?>