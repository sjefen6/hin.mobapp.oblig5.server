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
		<label for="username">Username:</label>
		<input type="text" name="username" required="required" /><br>
		<label for="password">Passord:</label>
		<input type="password" name="password" required="required" /><br>
		<input type="submit" value="Sign in" />
	</form>
	<h1>Register</h1>
	<form method="post">
		<label for="username">Username:</label>
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
	<p>Hi {$user->getUsername()}!</p>
	
	<h1>Add Track</h1>
	<form method="post">
		<label for="name">Name:</label>
		<input type="text" name="name" required="required" /><br>
		<label for="start">Start (Unix timestamp):</label>
		<input type="datetime" name="start" required="required" /><br>
		<label for="stop">Stop (Unix timestamp):</label>
		<input type="datetime" name="stop" required="required" /><br>
		<input type="submit" value="Add" />
	</form>
{if !empty($tracks)}
	<h1>Add Post</h1>
	<form method="post">
		<label for="track">Name:</label>
		<select name="track" required="required">
{foreach from=$tracks item=track}
			<option value="{$track->getId()}">{$track->getName()}</option>
{/foreach}
		</select><br>
		<label for="radius">Radius:</label>
		<input type="text" name="radius" required="required" /><br>
		<label for="latitude">Latitude:</label>
		<input type="text" name="latitude" required="required" /><br>
		<label for="longitude">Longitude:</label>
		<input type="text" name="longitude" required="required" /><br>
		<label for="clue">Clue:</label>
		<textarea name="clue" placeholder="Under the cat!"></textarea>
		<input type="submit" value="Add" />
	</form>
{/if}
{/if}
<footer><p>Alle rettigheter &copy; Vegard Lang&aring;s og Daniel Razafimandimby.<br>Mer info om cms-et p&aring; <a href="https://github.com/sjefen6/rebus-db" target="_blank">github</a></p></footer>
</section>
</body>
</html>