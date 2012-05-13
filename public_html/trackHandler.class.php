<?php
class trackHandler{
	private $trackArray;

	function __construct() {
		$sql = "SELECT * FROM " . settings::getDbPrefix(). "tracks";
		
		$stmt = settings::getDatabase() -> prepare($sql);
		$stmt->execute();
		
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

	public function getAllSmarty($users){
		$returnArray = array();

		foreach ($this->trackArray as $track) {
			$returnArray[] = $track->getSmarty($users);
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
		return $this->id;
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
		return $this->stop_ts;
	}
	
	public function getSmarty($users){
		$creatorUserName = $users->getUserById($this->creator) -> getUsername();
		if (isset($this->winner)){
			$winnerUserName = $users->getUserById($this->winner) -> getUsername();
		} else {
			$winnerUserName = "";
		}
		return array('id' => $this -> id,
					'name' => $this -> name,
					'creator' => $creatorUserName,
					'winner' => $winnerUserName,
					'start_ts' => $this->start_ts,
					'stop_ts' => $this->stop_ts);//TODO: Add pŒmeldte
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
		
		$stmt = settings::getDatabase()->prepare($sql);

		if ($new){
			$stmt -> execute(array(':name'=>$this -> name,
								':creator'=>$this -> creator,
								':start_ts'=>$this -> start_ts,
								':stop_ts'=>$this -> stop_ts));
		} else {
			$stmt -> execute(array(':name'=>$this -> name,
								':creator'=>$this -> creator,
								':winner'=>$this -> winner,
								':start_ts'=>$this -> start_ts,
								':stop_ts'=>$this -> stop_ts,
								':id'=>$this -> id));
		}
	}
}
?>