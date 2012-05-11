<?php

class postHandler{
	private $postArray;

	function __construct($filename) {
		$sql = "SELECT * FROM " . settings::getDbPrefix() . "posts";
		
		$stmt = settings::getDatabase() -> query($sql);
		
		$this -> postArray = $stmt -> fetchALL(PDO::FETCH_CLASS, 'posts');
	}
	
	public function getPosts($id, $users){
		foreach ($this->trackArray as $track) {
			if ($id == $track->getId()) {
				return $track->getSmarty($users);
			}
		}
		return false;
	}
	
	public function addPost($track_id, $post_number, $radius, $longitude, $latitude, $clue){
		$this->postArray[] = new Post($track_id, $post_number, $radius, $longitude, $latitude, $clue);
	}
}

class Post{
	private $id;
	private $track_id;
	private $post_number;
	private $radius;
	private $longitude;
	private $latitude;
	private $clue;
	private $ts;

	function __construct($track_id = null, $post_number = null, $radius = null, $longitude = null, $latitude = null, $clue = null) {
		$this->id = $post_id;
		$this->url_id = $url_id;
		$this->time = $time;
		$this->author_id = $author_id;
		$this->content = $desc;
		
		$this->save(true);
	}

	public function getId(){
		return $this->id;
	}
	
	private function save($new = false){
		/*** The SQL SELECT statement ***/
		if($new) {
			$sql = "INSERT INTO " . settings::getDbPrefix() . "posts " . 
			"(title, url_id, time, author_id, content) " . 
			"VALUES (:title, :url_id, :time, :author_id, :content);";
		} else {
			$sql = "UPDATE " . settings::getDbPrefix() . "users " .
			"SET title=:title, url_id=:url_id, time=:time, author_id=:author_id, content=:content " . 
        	"WHERE id=:id";
		}
		
		/*** fetch into an PDOStatement object ***/
		$stmt = settings::getDatabase()->prepare($sql);

		/*** fetch into the animals class ***/
		if ($new){
			$stmt -> execute(array(':title'=>$this -> title,
								':url_id'=>$this -> url_id,
								':time'=>$this -> time,
								':author_id'=>$this -> author_id,
								':content'=>$this -> content));
		} else {
			$stmt -> execute(array(':id'=>$this -> id,
								':title'=>$this -> title,
								':url_id'=>$this -> url_id,
								':time'=>$this -> time,
								':author_id'=>$this -> author_id,
								':content'=>$this -> content));
		}
	}
}
?>