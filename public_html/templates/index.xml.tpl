<?xml version="1.0" encoding="UTF-8"?>

<user>
	{if $user != null}
	<signedin>true</signedin>
	<username>{$user->getUsername()}</username>
	{else}
	<signedin>false</signedin>
	{/if}
</user>
{block name="content"}{/block}