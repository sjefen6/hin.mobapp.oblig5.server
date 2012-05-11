<?php
class trackHandler{
	private $trackArray;

	function __construct($settings) {
		$sql = "SELECT * FROM " . settings::getDbPrefix(). "tracks";
		$stmt = settings::getDatabase() -> query($sql);
		$this -> trackArray = $stmt -> fetchALL(PDO::FETCH_CLASS, 'track');
	}

	public function getTrack($id, $users){
		foreach ($this->trackArray as $track) {
			if ($id == $track->getId()) {
				return $track->getSmarty($users);
			}
		}
		return false;
	}

	public function getPosts($from, $to, $users){
		$returnArray = array();

		if ($from > $to){
			return false;
		}
		$counter = 0;
		foreach ($this->trackArray as $track) {
			if ($counter < $from){
				// We are not yet at $from
				;
			} else if ($counter > $to) {
				// We have passed $to
				return $returnArray;
			} else {
				$returnArray[] = $track->getSmarty($users);
			}
			$counter++;
		}
		return $returnArray;
	}
	
	public function addTrack($name, $creator, $start_ts, $stop_ts){
		$this->trackArray[] = new track($name, $creator, $start_ts, $stop_ts);
	}
}

class track{
	private $id;
	private $name;
	private $creator;
	private $winner;
	private $start_ts;
	private $stop_ts;

	function __construct($name=null, $creator=null, $start_ts=null, $stop_ts=null) {
		if ($name != null || $creator != null || $start_ts != null || $stop_ts != null) { 
			$this->name = $name;
			$this->creator = $creator;
			$this->start_ts = $start_ts;
			$this->stop_ts = $stop_ts;

			$this->save(true);
		}
	}

	public function getId(){
		return $this->url_id;
	}

	public function getName(){
		return $this->name;
	}

	public function getCreator(){
		return $this->creator;
	}

	public function getStart_TS(){
		return $this->start_ts;
	}
	
	public function getStop_TS(){
		return $this->author_id;
	}
	
	public function getSmarty($users){
		$user = $users->getUserById($this->author_id);
		return array('id' => $this -> getId(),
					'url_id' => $this -> url_id,
					'title' => $this->title,
					'time' => date("r", $this->time),
					'content' => $this->content,
					'author' => $user->getFirstname() . " " . $user->getLastname(),
					'no_comments' => count($comments->getCommentsForPost($this -> id, $users)),
					'comments' => $comments->getCommentsForPost($this -> getId(), $users));
	}
	
	private function save($new = false){
		if($new) {
			$sql = "INSERT INTO " . settings::getDbPrefix() . "tracks " . 
			"(name, creator, start_ts, stop_ts) " . 
			"VALUES (:name, :creator, :start_ts, :stop_ts);";
		} else {
			$sql = "UPDATE " . settings::getDbPrefix() . "tracks " .
			"SET name=:name, creator=:creator, winner=:winner, start_ts=:start_ts, stop_ts=:stop_ts " . 
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