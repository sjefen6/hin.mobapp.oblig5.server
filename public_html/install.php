<?php
$settingsFile = "../settings.xml";

if (!file_exists($settingsFile)) {
	header('Content-Type: text/html; charset=utf-8');
	require ('libs/Smarty.class.php');
	$smarty = new Smarty;
	
	$username = $_POST["user"];
	$password = $_POST["pw"];
	$email = $_POST["mail"];
	$dbhost = $_POST["dbhost"];
	$dbname = $_POST["dbname"];
	$dbuser = $_POST["dbuser"];
	$dbpw = $_POST["dbpw"];
	$dbprefix = $_POST["dbprefix"];

	
	$all = isset($username,$password,$email,$dbhost,$dbname,$dbuser,$dbpw,$dbprefix);
		
	$oneOrMore = isset($username) || isset($password) || isset($email) ||
		isset($dbhost) || isset($dbname) || isset($dbuser) || isset($dbpw) ||
		isset($dbprefix);
	
	if ($all) {
			require 'settings.class.php';
			$settings = new settings($settingsFile, $dbhost, $dbuser, $dbpw, $dbname, $dbprefix);
			
			// I don't see the point in cleaning input at this stage.
			// If an attacker is able to use this script he can make hell without exploiting injections.
			
			
			$create_users = 
			"CREATE TABLE " . $dbprefix . "users (" .
         		"id INT NOT NULL AUTO_INCREMENT PRIMARY KEY," .
         		"username VARCHAR(100) NOT NULL," .
         		"email VARCHAR(200) NOT NULL," .
         		"password VARCHAR(100) NOT NULL," . //this is supposed to be a hashed value
         		"salt VARCHAR(100) NOT NULL," . //this is supposed to be a hashed value
         		"validationkey VARCHAR(100) NOT NULL," .
         		"sessionkey VARCHAR(100) NOT NULL," .
         		"latitude DOUBLE PRECISION(9,6) NOT NULL," .
         		"lognitude DOUBLE PRECISION(9,6) NOT NULL," .
         		"usermode TINYINT NOT NULL," . // -1 = not validated, 0 = disabeled, 1 = active
         		"track_id INT NULL" .  // tracks(id)
       		");";
			
			$create_tracks = 
			"CREATE TABLE " . $dbprefix . "tracks (" .
         		"id INT NOT NULL AUTO_INCREMENT PRIMARY KEY," .
         		"name VARCHAR(100) NOT NULL," .
         		"creator INT NOT NULL COMMENT 'The user_id og the creator of the track'," . // users(id)
         		"winner INT NULL COMMENT 'The user_id og the first to complete the track'," . // users(id)
         		"start_ts BIGINT(12) NOT NULL," .
         		"stop_ts BIGINT(12) NOT NULL" .
       		");";
			
			$create_posts = 
			"CREATE TABLE " . $dbprefix . "posts (" .
				"id INT NOT NULL AUTO_INCREMENT PRIMARY KEY," .
         		"track_id INT NOT NULL," . // tracks(id)
         		"post_number INT NOT NULL," .
         		"radius INT NOT NULL," .
         		"latitude DOUBLE PRECISION(9,6) NOT NULL," .
         		"longitude DOUBLE PRECISION(9,6) NOT NULL," .
         		"clue TEXT" .
       		");";
			
			$create_visited_posts = 
			"CREATE TABLE " . $dbprefix . "visited_posts (" .
         		"user_id INT NOT NULL," . // kc_users(id)
         		"track_id INT NOT NULL," . // kc_track(id)
         		"post_id INT NOT NULL," . // kc_post(id)
         		"ts BIGINT(12) NOT NULL" .
       		");";
       		
			
       		$db = settings::getDatabase();
       		
			$db -> exec($create_users);
			$db -> exec($create_tracks);
			$db -> exec($create_posts);
			$db -> exec($create_visited_posts);
			
			require('userHandler.class.php');
			$users = new userHandler();
			$users -> addUser($username, $email, $password, 1);
			
			$smarty->assign("message","<pre>$create_users\n$create_tracks\n$create_posts\n$create_visited_posts</pre>");

	} else 	if ($oneOrMore) {
			$smarty->assign("message","Fill ALL fields (and get a html5 compadible browser)!");

	}else {
			$smarty->assign("message","Fill all fields!");
	}

	$smarty -> display('install.tpl');

} else {
	exit ;
}
?>