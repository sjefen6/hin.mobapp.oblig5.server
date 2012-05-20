{extends file="index.xml.tpl"}
{block name="main"}
	<results>
		<available>{count($results)}</available>
{foreach from=$results item=result}
		<result>
			<username>{$result.username}</username>
			<posts>{$result.posts}</posts>
			<lvpts>{$result.lvpts}</lvpts>
		</result>
{/foreach}
	</results>
{/block}