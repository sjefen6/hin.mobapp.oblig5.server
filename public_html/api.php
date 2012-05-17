<?php
header('Content-Type: text/xml; charset=utf-8');
date_default_timezone_set("Europe/Berlin");

require 'libs/Smarty.class.php';
require 'inputs.php';
require 'settings.class.php';
require 'tools.class.php';
require 'userHandler.class.php';
require 'trackHandler.class.php';
require 'postHandler.class.php';
require 'vpHandler.class.php';

$smarty = new Smarty;
$settings = new settings("../settings.xml");
$users = new userHandler();

//$smarty->force_compile = true;
//$smarty->debugging = true;
//$smarty->caching = false;
//$smarty->cache_lifetime = 120;

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
	case "reached":
		$posts = new postHandler();
		$vps = new vpHandler();
		if(isset($user,$post) && $posts->getPost($post) != null){
			var_dump($user->getId(), $posts->getPost($post)->getTrack_ID(), $post, time());
			$vps->addVp($user->getId(), $posts->getPost($post)->getTrack_ID(), $post, time());
		}
		break;
	default:
		break;
}

/*
 * If a logged in user sends lat & long
 */
if(isset($user,$latitude,$longitude)){
	$user->report($latitude,$longitude);
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
		// List all available tracks
        $tracks = new trackHandler();
        $smarty->assign("tracks", $tracks->getArray());
		$smarty->display('tracks.xml.tpl');
        break;
	case "users":
		// Scoreboard for a given track
		$smarty->assign("users", $users->getArray());
		$smarty->display('users.xml.tpl');
		break;
    case "post":
    	// The users current post
    	if(!isset($posts)){
    		$posts = new postHandler();
		}
		if(!isset($vps)){
			$vps = new vpHandler();
		}
    	if (isset($user)){
    		$smarty->assign("post",tools::getCurrentPost($user, $posts, $vps));
			$smarty->display('post.xml.tpl');
    	}
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