{extends file="index.xml.tpl"}
{block name="main"}
	<posts>
		<available>{count($posts)}</available>
{foreach from=$posts item=post}
		<post>
			<id>{$post->getId()}</id>
			<track_id>{$post->getTrack_ID()}</track_id>
			<radius>{$post->getRadius()}</radius>
			<latitude>{$post->getLatitude()}</latitude>
			<longitude>{$post->getLongitude()}</longitude>
			<clue>{$post->getClue()}</clue>
		</post>
{/foreach}
	</posts>
{/block}