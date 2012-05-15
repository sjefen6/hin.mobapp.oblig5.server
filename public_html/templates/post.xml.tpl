{extends file="index.xml.tpl"}
{block name="main"}
	<post>
		<available>{isset($post)}</available>
{if isset($post)}
		<id>{$post->getId()}</id>
		<track_id>{$post->getTrack_ID()}</track_id>
		<radius>{$post->getRadius()}</radius>
		<latitude>{$track->getLatitude()}</latitude>
		<longitude>{$track->getLongitude()}</longitude>
		<clue>{$track->getClue()}</clue>
{/if}
	</post>
{/block}