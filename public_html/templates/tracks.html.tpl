{extends file="index.html.tpl"}

{block name="content"}
<table>
	<tr><th>Track</th><th>Creator</th><th>Winner</th><th>Start</th><th>End</th></tr>
	{foreach from=$tracks item=track}
	<tr>
		<td><a href="?track={$track.id}">{$track.name}</a></tr>
		<td>{$track.creator}</td>
		<td>{$track.winner}</td>
		<td>{$track.start_ts}</td>
		<td>{$track.stop_ts}</td>
	</tr>
{/foreach}
</table>
{/block}