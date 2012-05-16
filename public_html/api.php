<?php
header('Content-Type: text/xml; charset=utf-8');
date_default_timezone_set("Europe/Berlin");

require 'libs/Smarty.class.php';
require 'settings.class.php';
require 'trackHandler.class.php';
require 'userHandler.class.php';
require 'vpHandler.class.php'

$smarty = new Smarty;
$settings = new settings("../settings.xml");
$users = new userHandler();

//$smarty->force_compile = true;
//$smarty->debugging = true;
//$smarty->caching = false;
//$smarty->cache_lifetime = 120;

/*
 * Inputs
 */
$username = $password = $sessionkey = $validationkey = $action = $target = $track = null;
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
// Track
if (isset($_POSTGET["track"])){
	$track = $_POSTGET["track"];
}
// Latitude
if (isset($_POSTGET["latitude"])){
	$latitude = $_POSTGET["latitude"];
}
// Longitude
if (isset($_POSTGET["longitude"])){
	$longitude = $_POSTGET["longitude"];
}

/*
 * E-mail validation
 */
if (isset($validationkey)){
	$validation = $users -> validate($username, $validationkey); // E-mail validation
}

/*
 * Login subutine
*/
$user = $users -> login($username, $password, $sessionkey);

/*
 * Action switch
 */
switch ($action) {
	case "logout":
		/*
		 * Logout subrutine
		 */
		$user -> logout();
		$user = null;
		break;
	case "auth":
		$smarty->assign("action", "auth");
		break;
	case "join":
		if(isset($user)){
			$user->join($track);
		}
		break;
	case "report":
		if(isset($user)){
			$user->report($latitude,$longitude);
		}
		break;
	default:
		break;
}

/*
 * Sending the user to smarty
 */
$smarty->assign("user", $user);

/*
 * Main target switch
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