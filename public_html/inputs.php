<?php

/*
 * Inputs
 */
$username = $password = $sessionkey = $validationkey = $action = $target = $track = $latitude = $longitude = $post = $name = $start = $stop = $clue = null;
$_POSTGET = array_merge($_GET, $_POST);
$_GETCOOKIE = array_merge($_GET, $_COOKIE);
$_GETPOSTCOOKIE = array_merge($_COOKIE,$_POST,$_GET);

// Format
if (isset($_POSTGET["format"])){
	$format = htmlspecialchars($_POSTGET["format"]);
}
// Username
if (isset($_GETPOSTCOOKIE["username"])){
	$username = htmlspecialchars($_GETPOSTCOOKIE["username"]);
}
// Password
if (isset($_POSTGET["password"])){
	$password = $_POSTGET["password"];
}
// Sessionkey
if (isset($_GETCOOKIE["sessionkey"])){
	$sessionkey = $_GETCOOKIE["sessionkey"];
}
// E-mail
if (isset($_POST["mail"])){
	$mail = htmlspecialchars($_POSTGET["mail"]);
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

// This is used only by the web interface
// Name (track name)
if (isset($_POST["name"])){
	$name = htmlspecialchars($_POSTGET["name"]);
}
// Start (track start_ts)
if (isset($_POST["start"])){
	$start = htmlspecialchars($_POSTGET["start"]);
}
// Start (track start_ts)
if (isset($_POST["stop"])){
	$stop = htmlspecialchars($_POSTGET["stop"]);
}
// Clue (post clue)
if (isset($_POST["radius"])){
	$radius = htmlspecialchars($_POSTGET["radius"]);
}
// Clue (post clue)
if (isset($_POST["clue"])){
	$clue = htmlspecialchars($_POSTGET["clue"]);
}


?>