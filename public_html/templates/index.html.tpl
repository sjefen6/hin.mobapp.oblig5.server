<!DOCTYPE html>
<html>
<head>
<title>Rebus DB</title>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
</head>
<body>
<section id="wrap">
{if !empty($errors)}
<h1>Errors:</h1>
{foreach from=$errors item=error}
<p>{$error}</p>
{/foreach}{/if}

{if !isset($user)}
	<h1>Login</h1>
	<form method="post">
		<label for="username">Brukernavn:</label>
		<input type="text" name="username" required="required" /><br>
		<label for="password">Passord:</label>
		<input type="password" name="password" required="required" /><br>
		<input type="submit" value="Sign in" />
	</form>
	<h1>Register</h1>
	<form method="post">
		<label for="username">Brukernavn:</label>
		<input type="text" name="username" required="required" /><br>
		<label for="mail">E-mail:</label>
		<input type="email" name="mail" required="required" /><br>
		<label for="password">Passord:</label>
		<input type="password" name="password" required="required" /><br>
		<input type="submit" value="Register" />
	</form>
{else}
		
	
	<header>
		<hgroup>
			<h1>Rebus DB</h1>
		</hgroup>
	</header>
	<nav>
		<a href="/">Tracks</a>
		<a href="/?action=logout">Logout</a>
	</nav>
	
	<div id="content">
	{block name="errors"}{if isset($error) and count($error) != 0}
		<div id="errors">
			<h1>{$error}</h1>
		</div>{/if}
	{/block}
{block name="content"}
{/block}
{/if}
<footer><p>Alle rettigheter &copy; Vegard Lang&aring;s og Daniel Razafimandimby.<br>Mer info om cms-et p&aring; <a href="https://github.com/sjefen6/webapp-blogg" target="_blank">github</a></p></footer>
</section>
</body>
</html>