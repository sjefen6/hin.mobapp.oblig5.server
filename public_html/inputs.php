<?php

/*
 * Inputs
 */
$username = $password = $sessionkey = $validationkey = $action = $target = $track = $latitude = $longitude = $post = null;
$_POSTGET = array_merge($_GET, $_POST);
$_GETCOOKIE = array_merge($_GET, $_COOKIE);

// Username
if (isset($_POSTGET["username"])){
	$username = htmlspecialchars($_POSTGET["username"]);
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
	$action = htmlspecialchars($_POSTGET["action"]);
}
// Target
if (isset($_POSTGET["target"])){
	$target = htmlspecialchars($_POSTGET["target"]);
}
// Track
if (isset($_POSTGET["track"])){
	$track = htmlspecialchars($_POSTGET["track"]);
}
// Latitude
if (isset($_POSTGET["latitude"])){
	$latitude = htmlspecialchars($_POSTGET["latitude"]);
}
// Longitude
if (isset($_POSTGET["longitude"])){
	$longitude = htmlspecialchars($_POSTGET["longitude"]);
}
// Post
if (isset($_POSTGET["post"])){
	$post = htmlspecialchars($_POSTGET["post"]);
}

?>