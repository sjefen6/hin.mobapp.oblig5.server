<?php
class tools{
	public static function getCurrentPost($user, $posts, $vps){
		$postArray = $posts->getArray();
		foreach($postArray as $post)
		{
			if ($vps->getVp($user->getId(), $post->getId()) == null){
				return $post;
			}
		}
		return null;
	}
	
	public static function getResults($users,$vps,$track){
		$resultArray = array();
		$userArray = $users->getArray();
		foreach($userArray as $user)
		{
			$vpArray = $vps->getArrayForUserWhereTrack($user, $track);
			if(!empty($vpArray)){
				$resultArray[] = array('username' => $user->getUsername(), 'posts' => count($vpArray), 'lvpts' => end($vpArray)->getTS());
			} 
		}
		return $resultArray;
	}
}
?>