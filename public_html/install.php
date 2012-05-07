<?php
$settingsFile = "../settings.xml";
if (!file_exists($settingsFile)) {
	header('Content-Type: text/html; charset=utf-8');
	require ('libs/Smarty.class.php');
	$smarty = new Smarty;
	
	$all = isset($_POST["user"]) && isset($_POST["pw"]) && isset($_POST["blogname"]) && isset($_POST["tagline"])
		&& isset($_POST["dbhost"]) && isset($_POST["dbname"])
		&& isset($_POST["dbuser"]) && isset($_POST["dbpw"])
		&& isset($_POST["dbprefix"]);
		
	$oneOrMore = isset($_POST["user"]) || isset($_POST["pw"]) || isset($_POST["blogname"]) || isset($_POST["tagline"])
		|| isset($_POST["dbhost"]) || isset($_POST["dbname"])
		|| isset($_POST["dbuser"]) || isset($_POST["dbpw"])
		|| isset($_POST["dbprefix"]);
	
	if ($all) {
			require 'settings.class.php';
			$settings = new settings($settingsFile, $_POST["blogname"], $_POST["tagline"], $_POST["dbhost"], $_POST["dbuser"], $_POST["dbpw"], $_POST["dbname"], $_POST["dbprefix"]);
			
			// I don't see the point in cleaning input at this stage.
			// If an attacker is able to use this script he can make hell without exploiting injections.
			
			$user = $_POST["user"];
			$pw = $_POST["pw"];
			$blogname = $_POST["blogname"];
			$tagline = $_POST["tagline"];
			$dbhost = $_POST["dbhost"];
			$dbname = $_POST["dbname"];
			$dbuser = $_POST["dbuser"];
			$dbpw = $_POST["dbpw"];
			$dbprefix = $_POST["dbprefix"];
			
			$create_users = 
			"CREATE TABLE " . $dbprefix . "users (" .
         		"id INT NOT NULL AUTO_INCREMENT PRIMARY KEY," .
         		"username VARCHAR(100) NOT NULL," .
         		"email VARCHAR(200)," .
         		"firstname VARCHAR(100)," .
         		"lastname VARCHAR(100)," .
         		"password VARCHAR(100) NOT NULL," . //this is supposed to be a hashed value
         		"salt VARCHAR(100) NOT NULL," . //this is supposed to be a hashed value
         		"validationkey VARCHAR(100) NOT NULL," .
         		"session_cookie VARCHAR(100) NOT NULL," .
         		"usermode TINYINT NOT NULL," . // -1 = not validated, 0 = disabeled, 1 = active
         		"userlevel TINYINT NOT NULL" .
       		");";
			
			$create_tracks = 
			"CREATE TABLE " . $dbprefix . "tracks (" .
         		"id INT NOT NULL AUTO_INCREMENT PRIMARY KEY," .
         		"name VARCHAR(100) NOT NULL," .
         		"creator_user_id INT NOT NULL COMMENT 'The creator of the track'," .
         		"winner_user_id INT NULL COMMENT 'The first to complete the track'," .
         		"start_ts BIGINT(12) NOT NULL," .
         		"stop_ts BIGINT(12) NOT NULL," .
         		// FOREIGN KEY for creator_user_id -> kc_users(id)
         		"INDEX cre_id (creator_user_id)," .
                "FOREIGN KEY (creator_user_id) REFERENCES " . $dbprefix . "users(id)" .
                "ON DELETE CASCADE," .
                // FOREIGN KEY for winner_user_id -> kc_users(id)
         		"INDEX win_id (winner_user_id)," .
                "FOREIGN KEY (winner_user_id) REFERENCES " . $dbprefix . "users(id)" .
                "ON DELETE CASCADE" .
       		");";
			
			$create_posts = 
			"CREATE TABLE " . $dbprefix . "posts (" .
				"id INT NOT NULL AUTO_INCREMENT PRIMARY KEY," .
         		"track_id INT NOT NULL," .
         		"post_numer VARCHAR(100) NOT NULL," .
         		"radius VARCHAR(100) NOT NULL," .
         		"longitude BIGINT(12) NOT NULL," .
         		"latitude INT NOT NULL," .
         		"clue TEXT," .
         		// FOREIGN KEY for track_id -> kc_track(id)
         		"INDEX trk_id (track_id)," .
                "FOREIGN KEY (track_id) REFERENCES " . $dbprefix . "track(id)" .
                "ON DELETE CASCADE" .
       		");";
			
			$create_visited_posts = 
			"CREATE TABLE " . $dbprefix . "visited_posts (" .
         		"user_id INT NOT NULL," .
         		"track_id INT NOT NULL," .
         		"post_id INT NOT NULL," .
         		"ts BIGINT(12) NOT NULL," .
         		// FOREIGN KEY for user_id -> kc_users(id)
         		"INDEX usr_id (user_id)," .
                "FOREIGN KEY (user_id) REFERENCES " . $dbprefix . "users(id)" .
                "ON DELETE CASCADE," .
                // FOREIGN KEY for track_id -> kc_track(id)
         		"INDEX trk_id (track_id)," .
                "FOREIGN KEY (track_id) REFERENCES " . $dbprefix . "track(id)" .
                "ON DELETE CASCADE," .
                // FOREIGN KEY for post_id -> kc_post(id)
         		"INDEX pst_id (post_id)," .
                "FOREIGN KEY (post_id) REFERENCES " . $dbprefix . "post(id)" .
                "ON DELETE CASCADE" .
       		");";
       		
			
       		$db = settings::getDatabase();
       		
			$db -> exec($createUsers);
			$db -> exec($createPosts);
			$db -> exec($createPages);
			$db -> exec($createComments);
			
			//TODO: Add the user to the database!
			require('userHandler.class.php');
			$users = new userHandler();
			$users -> addUser($user, "", "", "", $pw, 0, 1);
			
			$smarty->assign("message","<pre>$createUsers\n$createPosts\n$createPages\n$createComments</pre>");

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