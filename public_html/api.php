<?php
header('Content-Type: text/xml; charset=utf-8');
date_default_timezone_set("Europe/Berlin");

require 'libs/Smarty.class.php';
require 'settings.class.php';
require 'trackHandler.class.php';
require 'userHandler.class.php';

$smarty = new Smarty;
$settings = new settings("../settings.xml");

//$smarty->force_compile = true;
//$smarty->debugging = true;
//$smarty->caching = false;
//$smarty->cache_lifetime = 120;
$smarty->assign("mode","default");

/*
 * Inputs
 */
$user = $password = $sessionkey = $validationkey = $action = $target = null;
// Username
if (isset($_REQUEST["username"])){
	$user = $_REQUEST["username"];
}
// Password
if (isset($_REQUEST["password"])){
	$password = $_REQUEST["password"];
}
// Sessionkey
if (isset($_REQUEST["sessionkey"])){
	$sessionkey = $_REQUEST["sessionkey"];
}
// Validationkey
if (isset($_REQUEST["validationkey"])){
	$validationkey = $_REQUEST["validationkey"];
}
// Action
if (isset($_REQUEST["action"])){
	$action = $_REQUEST["action"];
}
// Target
if (isset($_REQUEST["target"])){
	$target = $_REQUEST["target"];
}

/*
 * Logout subrutine
 * This comes before the login subrutine so that the user is logged out when the credetials are validated
 */
$users = new userHandler();
if ($action = "logout"){
	$users -> logout($username, $password, $sessionkey);
}

/*
 * Login subutine
*/
$validation = $users -> validate($username, $validationkey); // E-mail validation
$user = $users -> login($username, $password, $sessionkey);
$smarty->assign("user", $user);

/*
 * Main content switch
*/

switch ($target) {
    case "tracks":
        $tracks = new trackhandler();
        $smarty->assign("tracks", $tracks -> getAllSmarty($users));
		$smarty->display('tracks.xml.tpl');
        break;
    case 1:
        echo "i equals 1";
        break;
    case 2:
        echo "i equals 2";
        break;
}
exit;

if (isset($_GET["list"])) {
	
	if ($_GET["list"] == "tracks"){
		$smarty->assign("tracks", $tracks -> getAllSmarty($users));
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