{extends file="index.html.tpl"}

{block name="content"}
<table>
	<tr><th>Track</th><th>Creator</th><th>Winner</th><th>Start</th><th>End</th></tr>
	{foreach from=$tracks item=track}
	<tr>
		<td><a href="?track={$track->getId()}">{$track->getName()}</a></tr>
		<td>{$track->getId()}</td>
		<td>{$track->getWinner()}</td>
		<td>{$track->getStart_TS()}</td>
		<td>{$track->getStop_TS()}</td>
	</tr>
{/foreach}
</table>
{/block}