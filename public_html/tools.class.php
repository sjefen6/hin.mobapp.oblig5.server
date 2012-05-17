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

		$users_size = count($userArray);
		for($i = 0; $i < $users_size; $i++)
		{
			$vpArray = $vps->getArray($userArray[i]);
			$resultArray[] = array('username' => $userArray[i]->getUsername(), 'posts' => count($vpArray), 'lvpts' => end($vpArray)->getTS()); 
		}
		return $resultArray;
	}
}
?>