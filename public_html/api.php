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
$username = $password = $sessionkey = $validationkey = $action = $target = null;
$_POSTGET = array_merge($_GET, $_POST);
$_GETCOOKIE = array_merge($_GET, $_COOKIE);
// Username
if (isset($_POSTGET["username"])){
	$username = $_POSTGET["username"];
}
// Password
if (isset($_POSTGET["password"])){
	$password = $_POSTGET["password"];
}
// Sessionkey
if (isset($_GETCOOKIE["sessionkey"])){
	$sessionkey = $_GETCOOKIE["sessionkey"];
}
// Validationkey
if (isset($_POSTGET["validationkey"])){
	$validationkey = $_POSTGET["validationkey"];
}
// Action
if (isset($_POSTGET["action"])){
	$action = $_POSTGET["action"];
}
// Target
if (isset($_POSTGET["target"])){
	$target = $_POSTGET["target"];
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
        $tracks = new trackHandler();
        $smarty->assign("tracks", $tracks->getArray());
		$smarty->display('tracks.xml.tpl');
        break;
    case "post":
    	// The users current post
        echo "i equals 1";
        break;
    case "posts":
		/*
		 * All posts for a given track.
		 * This should only be available after the time
		 * has run out or the user is the creator of the
		 * track.
		 */
        echo "i equals 2";
        break;
	case "result":
		// Scoreboard for a given track
		break;
	default:
		$smarty->display('index.xml.tpl');
		break;
}
exit;
?>