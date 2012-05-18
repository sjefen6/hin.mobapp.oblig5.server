<?php

class postHandler{
	private $postArray;

	function __construct() {
		// $sql = "SELECT * FROM " . settings::getDbPrefix(). "posts WHERE track_id = " . $user->getTrack_ID();
		$sql = "SELECT * FROM " . settings::getDbPrefix(). "posts";
		
		// SELECT rdb_posts.*,  rdb_visited_posts.ts
		// FROM rdb_posts
		// FULL JOIN rdb_visited_posts 
		// ON rdb_posts.id = rdb_visited_posts.post_id
		// WHERE rdb_visited_posts.user_id = 1
		
		$stmt = settings::getDatabase() -> prepare($sql);
		$stmt->execute();
		
		$this -> postArray = $stmt -> fetchALL(PDO::FETCH_CLASS, 'post');
	}
	
	public function getPost($id){
		foreach ($this->postArray as $post) {
			if ($id == $post->getId()) {
				return $post;
			}
		}
		return null;
	}
	
	public function getArray($track_id = null){
		$returnArray = array();
		if (!isset($track_id)){
			$returnArray = $this->postArray;
		} else {
			foreach ($this->postArray as $post){
				if ($post->getTrack_ID() == $track_id){
					$returnArray[] = $post;
				}
			}
		}
		return $returnArray;
	}
	
	public function addPost($track_id, $radius, $latitude, $longitude, $clue){
		$this->postArray[] = new Post($track_id, $radius, $latitude, $longitude, $clue);
	}
}

class Post{
	private $id;
	private $track_id;
	private $radius;
	private $latitude;
	private $longitude;
	private $clue;

	function __construct($track_id = null, $radius = null, $latitude = null, $longitude = null, $clue = null) {
		if ($track_id != null || $radius != null || $latitude != null || $longitude != null || $clue != null) {
			$this->track_id = $track_id;
			$this->radius = $radius;
			$this->latitude = $latitude;
			$this->longitude = $longitude;
			$this->clue = $clue;
			
			$this->save(true);
		}
	}

	public function getId(){
		return $this->id;
	}
	
	public function getTrack_ID(){
		return $this -> track_id;
	}
	
	public function getRadius(){
		return $this -> radius;
	}
	
	public function getLatitude(){
		return $this -> latitude;
	}
	
	public function getLongitude(){
		return $this -> longitude;
	}
	
	public function getClue(){
		return $this -> clue;
	}
	
	private function save($new = false){
		if($new) {
			$sql = "INSERT INTO " . settings::getDbPrefix() . "posts " .
			"(track_id, radius, latitude, longitude, clue) " .
			"VALUES (:track_id, :radius, :latitude, :longitude, :clue);";
		} else {
			$sql = "UPDATE " . settings::getDbPrefix() . "users " .
			"SET track_id=:track_id, radius=:radius, latitude=:latitude, longitude=:longitude, clue=:clue " . 
        	"WHERE id=:id";
		}
		
		$stmt = settings::getDatabase()->prepare($sql);
		
		if ($new){
			$stmt -> execute(array(':track_id'=>$this -> track_id,
								':radius'=>$this -> radius,
								':latitude'=>$this -> latitude,
								':longitude'=>$this -> longitude,
								':clue'=>$this -> clue));
		} else {
			$stmt -> execute(array(':track_id'=>$this -> track_id,
								':radius'=>$this -> radius,
								':latitude'=>$this -> latitude,
								':longitude'=>$this -> longitude,
								':clue'=>$this -> clue,
								':id'=>$this -> id));
		}
	}
}
?>