{extends file="index.xml.tpl"}
{block name="main"}
	<tracks>
		<available>{count($tracks)}</available>
{foreach from=$tracks item=track}
		<track>
			<id>{$track->getId()}</id>
			<name>{$track->getName()}</name>
			<creator>{$track->getCreator()}</creator>
			<winner>{$track->getWinner()}</winner>
			<start_ts>{$track->getStart_TS()}</start_ts>
			<stop_ts>{$track->getStop_TS()}</stop_ts>
		</track>
{/foreach}
	</tracks>
{/block}