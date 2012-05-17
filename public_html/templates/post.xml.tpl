{extends file="index.xml.tpl"}
{block name="main"}
	<post>
		<available>{if isset($post)}true{else}false{/if}</available>
{if isset($post)}
		<id>{$post->getId()}</id>
		<track_id>{$post->getTrack_ID()}</track_id>
		<radius>{$post->getRadius()}</radius>
		<latitude>{$post->getLatitude()}</latitude>
		<longitude>{$post->getLongitude()}</longitude>
		<clue>{$post->getClue()}</clue>
{/if}
	</post>
{/block}