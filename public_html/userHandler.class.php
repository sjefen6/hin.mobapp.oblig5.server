<?php
class userHandler {
	private static $userArray = array();

	function __construct() {
		$sql = "SELECT * FROM " . settings::getDbPrefix() . "users";

		$stmt = settings::getDatabase() -> prepare($sql);
		$stmt->execute();

		self::$userArray = $stmt -> fetchALL(PDO::FETCH_CLASS, 'user');
	}
	
	public function validate($username, $validationkey){
		if (!isset($username) && !isset($validationkey)){
			$user = $this -> getUser($username);
			if ($user != null && $user -> getUsermode() >= -1 && $user -> verifyValidationkey($validationkey)){
				return true;
			}
		}
		return false;
	}
	
	public function login($username, $password, $sessionkey){
		$user = $this -> getUser($username);
		if ($user != null && $user -> getUsermode() <= 1){
			if ($user -> verifyPasword($password) || $user -> verifySessionCookie($sessionkey)){
				return $user;
			}
		}
		return null;
	}
	
	public function lostpw($username, $email){
		$user = $this ->getUser($username);
		if ($user != null){
			return $user -> sendNewPassword($email);
		}
		return false;
	}
	
	private function getUser($username){
		foreach (self::$userArray as $user) {
			if ($user -> getUsername() == $username) {
				return $user;
			}
		}
		return null;
	}
	
	public static function getUserById($id){
		foreach (self::$userArray as $user) {
			if ($user -> getId() == $id) {
				return $user;
			}
		}
		return null;
	}
	
	public function getArray(){
		return self::$userArray;
	}

	public function addUser($username, $email, $password, $usermode) {
		if ($this -> getUser($username) == NULL){
			self::$userArray[] = new user($username, $email, $password, $usermode);
			return true;
		}
		return false;
	}
}

class user {
	private $id;
	private $username;
	private $email;
	private $password;
	private $salt;
	private $validationkey;
	private $sessionkey;
	private $latitude;
	private $longitude;
	private $usermode;
	private $track_id;

	function __construct($username = null, $email = null, $password = null, $usermode = null) {
		if ($username != null || $email != null || $password != null || $usermode != null) {
			// Lets fill the fields that needs some random stuff
			$this -> sessionkey = $this -> random_gen(30);
			$this -> validationkey = $this -> random_gen(30);

			$this -> username = $username;
			$this -> email = $email;
			$this -> setPassword($password);
			$this -> usermode = $usermode;
			
			if ($this -> usermode <= -1){
				$this->sendRegisterValidation();
			}

			$this -> save(true);
		}
	}
	
	public function getId() {
		return $this -> id;
	}
	
	public function getUsername() {
		return $this -> username;
	}
	
	public function getEmail() {
		return $this -> email;
	}

	public function getTrack_ID() {
		return $this -> track_id;
	}
	
	public function getUsermode() {
		return $this -> usermode;
	}
	
	public function getLatitude(){
		return $this->latitude;
	}
	
	public function getLongitude(){
		return $this->longitude;
	}

	private function setPassword($password) {
		$this -> salt = $this -> random_gen(30);
		$this -> password = sha1($password . $this -> salt);
	}
	
	public function getSessionkey()
	{
		return $this->sessionkey;
	}
	
	private function resetSessionKey(){
		$this -> sessionkey = $this -> random_gen(30);
		$this -> save();
	}

	public function verifyPasword($password) {
		if ($this -> password === sha1($password . $this -> salt)) {
			$this -> resetSessionKey();
			setcookie("username", $this -> username);
			setcookie("sessionkey", $this -> sessionkey);
			return true;
		}
		return false;
	}

	public function verifyValidationkey($validationkey) {
		if ($this -> validationkey === $validationkey) {
			if ($this -> usermode < 0) {
				$this -> usermode = 1;
				$this -> save();
				return true;
			}
		}
		return false;
	}

	public function verifySessionCookie($sessionkey) {
		if ($this -> sessionkey === $sessionkey) {
			return true;
		}
		return false;
	}
	
	public function logout(){
		$this -> resetSessionKey();
		setcookie("username");
		setcookie("sessionkey");
	}
	
	public function join($track_id){
		$this->track_id = $track_id;
		$this->save();
	}
	
	public function report($lat, $long){
		$this->latitude = $lat;
		$this->longitude = $long;
		$this->save();
	}
	
	private function sendRegisterValidation(){
		$to  = $this->email;
		$url = $_SERVER['HTTP_HOST']."/?username=" . $this -> username . "&validationkey=" . $this->validationkey;
		
		$subject = "E-post validering kc blogg";
		
		$message = "Open this url to validate your e-mail adress: " . $url;
		
		$headers = 'From: noreply@'. $_SERVER['SERVER_NAME'] . "\r\n" .
		'Reply-To: noreply@'. $_SERVER['SERVER_NAME'] . "\r\n" .
		'X-Mailer: PHP/' . phpversion();
		
		mail($to, $subject, $message, $headers);
	}
	
	public function sendNewPassword($email){
		// This is a source for exploitation if the admin has not edited his user and set his email
		if ($this->email == $email || $this->email == NULL){
			$password = $this->random_gen(8);
			$subject = "New password for kc blogg";
			
			$message = "Your new password is: " . $password;
			
			$headers = 'From: noreply@'. $_SERVER['SERVER_NAME'] . "\r\n" .
						'Reply-To: noreply@'. $_SERVER['SERVER_NAME'] . "\r\n" .
						'X-Mailer: PHP/' . phpversion();
						
			mail($email,$subject,$message,$headers);
			
			$this->setPassword($password);
			$this->email = $email;
			$this->save();
			return true;
		}
		return false;
	}

	private function random_gen($length) {
		// Source: http://deepakssn.blogspot.com/2006/06/php-random-string-generator-function.html

		$random = "";
		srand((double)microtime() * 1000000);
		$char_list = "ABCDEFGHIJKLMNOPQRSTUVWXYZ";
		$char_list .= "abcdefghijklmnopqrstuvwxyz";
		$char_list .= "1234567890";
		// Add the special characters to $char_list if needed
		for ($i = 0; $i < $length; $i++) {
			$random .= substr($char_list, (rand() % (strlen($char_list))), 1);
		}
		return $random;
	}

	private function save($new = false) {
		if ($new) {
			$sql = "INSERT INTO " . settings::getDbPrefix() . "users " .
			"(username, email, password, " .
			"salt, validationkey, sessionkey, " .
			"latitude, longitude, " .
			"usermode, track_id) " .
			"VALUES (:username, :email, :password, " .
			":salt, :validationkey, :sessionkey, " .
			":latitude, :longitude, " .
			":usermode, :track_id);";
		} else {
			$sql = "UPDATE " . settings::getDbPrefix() . "users " .
			"SET username = :username, " .
			"email = :email, " .
			"password = :password, " .
			"salt = :salt, " .
			"validationkey = :validationkey, " .
			"sessionkey = :sessionkey, " .
			"latitude = :latitude, " .
			"longitude = :longitude, " .
			"usermode = :usermode, " .
			"track_id = :track_id " .
			"WHERE id = :id";
		}

		$stmt = settings::getDatabase() -> prepare($sql);

		if ($new) {
			$stmt -> execute(array(':username' => $this -> username,
					':email' => $this -> email,
					':password' => $this -> password,
					':salt' => $this -> salt,
					':validationkey' => $this -> validationkey,
					':sessionkey' => $this -> sessionkey,
					':latitude' => $this -> latitude,
					':longitude' => $this -> longitude,
					':usermode' => $this -> usermode,
					':track_id' => $this -> track_id));
		} else {
			$stmt -> execute(array(':username' => $this -> username,
					':email' => $this -> email,
					':password' => $this -> password,
					':salt' => $this -> salt,
					':validationkey' => $this -> validationkey,
					':sessionkey' => $this -> sessionkey,
					':latitude' => $this -> latitude,
					':longitude' => $this -> longitude,
					':usermode' => $this -> usermode,
					':track_id' => $this -> track_id,
					':id' => $this -> id));
		}
	}
}
?>