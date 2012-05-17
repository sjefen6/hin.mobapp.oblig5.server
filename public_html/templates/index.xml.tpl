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
{if !empty($errors)}
	<errors>
{foreach from=$errors item=error}
		<error>{$error}</error>
{/foreach}
	</errors>
{/if}
{block name="main"}{/block}
</body>