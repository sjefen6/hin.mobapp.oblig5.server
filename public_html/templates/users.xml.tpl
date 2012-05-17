{extends file="index.xml.tpl"}
{block name="main"}
	<users>
		<available>{count($users)}</available>
{foreach from=$users item=user}
		<user>
			<id>{$user->getId()}</id>
			<username>{$user->getUsername()}</username>
			<latitude>{$user->getLatitude()}</latitude>
			<longitude>{$user->getLongitude()}</longitude>
		</user>
{/foreach}
	</users>
{/block}