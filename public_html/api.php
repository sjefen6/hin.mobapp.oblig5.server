<?php
/*
 * Oblig 1
* @package Example-application
*/
header('Content-Type: text/xml; charset=utf-8');
date_default_timezone_set("Europe/Berlin");

require 'libs/Smarty.class.php';
require 'settings.class.php';
require 'trackhandler.class.php';

$smarty = new Smarty;
$settings = new settings("../settings.xml");

// $smarty->force_compile = true;
// $smarty->debugging = true;
//$smarty->caching = false;
//$smarty->cache_lifetime = 120;
$smarty->assign("mode","default");

/*
 * Login subutine
*/
$admin = false;
$failed = false;
$users = new userHandler("../users.xml");

if ($users -> verifySession()){
	$admin = true;
}

if (isset($_GET["login"])){
	if ($_GET["login"] == "in"){
		$admin = $users->verifyLogin($_POST["userId"], $_POST["password"]);
		if (!$admin){
			$failed =  true;
		}
	} else {
		$users -> logout();
		$admin = false;
	}
}
$smarty->assign("failed", $failed);
$smarty->assign("signedIn", $admin);

/*
 * Send user data to smarty
 */
$smarty->assign("tracks", $user -> getCurrent());

/*
 * Main content switch
*/
if (isset($_GET["list"])) {
	
	if ($_GET["list"] == "tracks"){
		$smarty->assign("tracks", $tracks -> listAll());
		$smarty->display('tracks.tpl');
	}
	
	
	$temp = $pages->getPage($_GET["page"]);
	if ($temp != false){
		$smarty->assign("mode","page");
		$smarty->assign("page", $temp);
	} else {
		header("Status: 404 Not Found");
	}
} else if (isset($_GET["post"])) {
	$temp = $posts->getPost($_GET["post"]);
	if ($temp != false){
		$smarty->assign("mode","post");
		$smarty->assign("post", $temp);
	} else {
		header("Status: 404 Not Found");
	}
} else if (isset($_GET["admin"])) {
	if ($_GET["admin"] == "addPage" && $admin){
		if (isset($_POST["title"])){
			if ($pages->addPage($_POST["id"],$_POST["title"],$_POST["desc"])){
				$smarty->assign("mode","added");
			} else {
				$smarty->assign("mode","notAdded");
			}
		} else {
			$smarty->assign("mode","addPage");
		}
	} else if ($_GET["admin"] == "addPost" && $admin){
		if (isset($_POST["title"])){
			if ($posts->addPost($_POST["id"],$_POST["title"],$_POST["desc"])){
				$smarty->assign("mode","added");
			} else {
				$smarty->assign("mode","notAdded");
			}
		} else {
			$smarty->assign("mode","addPost");
		}
	} else if ($_GET["admin"] == "newUser"){
		if (isset($_POST["userName"])){
			if ($users->addUser($_POST["userName"],$_POST["password"],
					$_POST["confirmPassword"],$_POST["firstName"],
					$_POST["lastName"],$_POST["email"])){
				$smarty->assign("mode","userAdded");
			} else {
				$smarty->assign("mode","notAdded");
			}
		} else {
			$smarty->assign("mode","newUser");
		} 
	}else {
		header("Status: 404 Not Found");
	}
} else {
	$temp = $posts->getPosts(0, 10);
	if ($temp != false){
		$smarty->assign("mode","bloglist");
		$smarty->assign("articles", $temp);
	} else {
		header("Status: 404 Not Found");
	}
}

$smarty->display('index.tpl');
?>