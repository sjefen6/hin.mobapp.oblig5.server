<?xml version="1.0" encoding="UTF-8"?>

<body>
	<user>
{if $user != null}
		<signedin>true</signedin>
		<username>{$user->getUsername()}</username>
		<track>{$user->getTrack_ID()}</track>
{if $action == "auth"}
		<sessionkey>{$user->getSessionkey()}</sessionkey>
{/if}
{else}
		<signedin>false</signedin>
{/if}
	</user>
	{block name="main"}{/block}
</body>