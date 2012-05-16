<?php
/*
 * Vel, dette kunne sikkert ha v�rt l�st p� databasesiden p� en penere m�te...
 * 
 * SELECT rdb_posts.*,  rdb_visited_posts.ts
 * FROM rdb_posts
 * FULL JOIN rdb_visited_posts
 * ON rdb_posts.id = rdb_visited_posts.post_id
 * WHERE rdb_visited_posts.user_id = 1
 */

class vpHandler{
	private $vpArray;

	function __construct($track_id) {
		$sql = "SELECT * FROM " . settings::getDbPrefix(). "visited_posts WHERE track_id = $track_id AND user_id = " . $user->getID();
		
		$stmt = settings::getDatabase() -> prepare($sql);
		$stmt->execute();
		
		$this -> vpArray = $stmt -> fetchALL(PDO::FETCH_CLASS, 'vp');
	}
	
	public function getVp($user_id, $post_id){
		foreach ($this->vpArray as $vp) {
			if ($post_id == $track->getPost_ID() && $user_id == $track->getUser_ID() ) {
				return $vp;
			}
		}
		return null;
	}
	
	public function getArray($user=null){
		if(!isset($user)){
			return $this->trackArray;
		} else {
			$returnArray = array();
			foreach ($vpArray as $vp) {
				if($vp->getUser_ID() == $user->getId())
				{
					$returnArray[] = $vp;
				}
			}
			return $returnArray;
		}
	}
	
	public function addVp($user_id, $track_id, $post_id, $ts){
		$this->postArray[] = new Vp($user_id, $track_id, $post_id, $ts);
	}
}

class Vp{
	private $user_id;
	private $track_id;
	private $post_id;
	private $ts;

	function __construct($user_id = null, $track_id = null, $post_id = null, $ts = null) {
		if ($user_id != null || $track_id != null || $post_id != null || $ts != null) {
			$this -> user_id = $user_id;
			$this -> track_id = $track_id;
			$this -> post_id = $post_id;
			$this -> ts = $ts;

			$this -> save(true);
		}
	}

	public function getUser_ID(){
		return $this->user_id;
	}
	
	public function getTrack_ID(){
		return $this -> track_id;
	}
	
	public function getPost_ID(){
		return $this -> post_id;
	}
	
	public function getTS(){
		return $this -> ts;
	}
	
	private function save($new = false){
		/*** The SQL SELECT statement ***/
		if($new) {
			$sql = "INSERT INTO " . settings::getDbPrefix() . "visited_posts " . 
			"(user_id, track_id, post_id, ts) " . 
			"VALUES (:user_id, :track_id, :post_id, :ts);";
		}
		/*
		 * Vi har ingen set funksjoner
		else {
			$sql = "UPDATE " . settings::getDbPrefix() . "visited_posts " .
			"SET track_id=:track_id, ts=:ts" . 
        	"WHERE post_id=:post_id";
		}*/
		
		$stmt = settings::getDatabase()->prepare($sql);

		if ($new){
			$stmt -> execute(array(':user_id'=>$this -> user_id,
								':track_id'=>$this -> track_id,
								':post_id'=>$this -> post_id,
								':ts'=>$this -> ts));
		}
		/*
		 * Vi har ingen set funksjoner
		else {
			$stmt -> execute(array(':track_id'=>$this -> track_id,
								':ts'=>$this -> ts,
								':post_id'=>$this -> post_id));
		}*/
	}
}
?>