<?php
class tools{
	public static function getCurrentPost($user, $posts, $vps){
		$postArray = $posts->getArray();
		$posts_size = count($postsArray);
		for($i = 0; $i < $posts_size; $i++)
		{
			if (!isset($vps->getVp($user->getId(), $postArray[i]->getId()))){
				return $postArray[i];
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