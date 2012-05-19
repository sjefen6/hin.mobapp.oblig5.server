<?php
require 'libs/Smarty.class.php';
require 'inputs.php';
require 'settings.class.php';
require 'tools.class.php';
require 'userHandler.class.php';
require 'trackHandler.class.php';
require 'postHandler.class.php';
require 'vpHandler.class.php';

if ($format == "xml"){
	header('Content-Type: text/xml; charset=utf-8');
} else {
	header('Content-Type: text/html; charset=utf-8');
}
date_default_timezone_set("Europe/Berlin");

$smarty = new Smarty;
$settings = new settings("../settings.xml");
$errors = array();

//$smarty->force_compile = true;
//$smarty->debugging = true;
//$smarty->caching = false;
//$smarty->cache_lifetime = 120;

/*
 * Showing optimalisation the middle finger and just load the whole database
 */
$users = new userHandler();
$tracks = new trackHandler();
$posts = new postHandler();
$vps = new vpHandler();

/*
 * E-mail validation
 */
if (isset($username,$validationkey)){
	$validation = $users -> validate($username, $validationkey);
}

/*
 * User is regestering
 */
if (isset($username,$mail,$password)){
	$users -> addUser($username, $mail, $password, -1);
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
		$user -> logout();
		$user = null;
		break;
	case "auth":
		$smarty->assign("action", "auth");
		break;
	case "join":
		if(isset($user)){
			$user->join($track);
		} else {
			$errors[] = "Cannot join track $track. User is not signed in.";
		}
		break;
	case "reached":
		if(isset($user,$post) && $posts->getPost($post) != null){
			$track_id = $posts->getPost($post)->getTrack_ID();
			
			if(time() >= $tracks->getTrack($track_id)->getStart_TS() && time() <= $tracks->getTrack($track_id)->getStop_TS()){
				$vps->addVp($user->getId(), $track_id, $post, time());
			} else {
				$errors[] = "Unable to set post $post as visited. The track is finished or not yet started.";
			}
		} else {
			$errors[] = "Unable to set post $post as visited. User must be signed in, the post parameter must be set.";
		}
		break;
	default:
		break;
}

/*
 * If a logged in user adds a track
 */
if(isset($name,$start,$stop) && isset($user)){
	$tracks->addTrack($name, $user->getId(), $start, $stop);
} else {
	$errors[] = "Get a <a href\"http://google.com/chrome\">decent browser</a>, then fill inn the entire form.";
}

/*
 * If a logged in user adds a post
 */
if(isset($track,$radius,$latitude,$longitude) && isset($user)){
	if ($tracks->getTrack($track) != null){
		if($tracks->getTrack($track)->getCreatorId() == $user->getId()){
			$posts->addPost($track, $radius, $latitude, $longitude, $clue);
		} else {
			$errors[] = "You are not the creator of this track. PS: cheating is not nice.";
		}
	} else {
		$errors[] = "I don't think this is a track in our database. PS: cheating is not nice.";
	}
} else {
	$errors[] = "Get a <a href\"http://google.com/chrome\">decent browser</a>, then fill inn the entire form.";
}

/*
 * If a logged in user sends lat & long
 */
if(isset($user,$latitude,$longitude) && empty($clue)){
	$user->report($latitude,$longitude);
}

/*
 * Sending the user to smarty
 */
$smarty->assign("user", $user);
/*
 * Sending errors to smarty
 */
$smarty->assign("errors",$errors);

/*
 * Main target switch
*/
switch ($target) {
    case "tracks":
		// List all available tracks
        $smarty->assign("tracks", $tracks->getArray());
		if ($format == "xml"){
			$smarty->display('tracks.xml.tpl');
		}
		exit;
        break;
	case "users":
		// Location for all users
		$smarty->assign("users", $users->getArray());
		if ($format == "xml"){
			$smarty->display('users.xml.tpl');
		}
		exit;
		break;
    case "post":
    	// The users current post
    	$current_post = null;
    	if (!isset($user)){
    		$errors[] = "Unable to get the users current post. User must be signed in.";
		} else {
    		$current_post = tools::getCurrentPost($user, $posts, $vps);
		    $track_id = $user->getTrack_ID();
		    if (!isset($track_id)){
		    	$errors[] = "Unable to get the users current post. The user has not joined a track.";
			} else {
				if(!(time() >= $tracks->getTrack($track_id)->getStart_TS() && time() <= $tracks->getTrack($track_id)->getStop_TS())){
					$errors[] = "Unable to get the users current post. The track is finished or not yet started.";
				} else {
		    		if (!isset($current_post)){
						$errors[] = "Unable to get the users current post. The track is out of posts.";
					}
				}
			}
		}
		$smarty->assign("post",$current_post);
		$smarty->assign("errors",$errors);
		if ($format == "xml"){
			$smarty->display('post.xml.tpl');
		}
		exit;
        break;
    case "posts":
		/*
		 * All posts for a given track.
		 * This should only be available after the time
		 * has run out or the user is the creator of the
		 * track.
		 */
		$postsArray = array();
		if (!isset($track)){
			$errors[] = "Unable to list posts. No track defined.";
		} else {
	        if ($user->getId() != $tracks->getTrack($track)->getCreatorId() || time() < $tracks->getTrack($track)->getStop_TS()){
	        	$errors[] = "Unable to list posts. Track is still active.";
	        } else {
	        	$postsArray = $posts->getArray($track);
	        }	
		}
	    $smarty->assign("posts", $postsArray);
    	$smarty->assign("errors",$errors);
    	if ($format == "xml"){
			$smarty->display('posts.xml.tpl');
		}
		exit;
        break;
	case "result":
		// Scoreboard for a given track
		//TODO: Make sure to ignore that this is not implemented!!!!
		$smarty->assign("errors",$errors);
		exit;
		break;
}

$smarty->assign("errors",$errors);
if ($format == "xml"){
	$smarty->display('index.xml.tpl');
} else {
	if(isset($user)){
		$smarty->assign("tracks", $tracks->getArrayForUser($user));
	}
	$smarty->display('index.html.tpl');
}

?>