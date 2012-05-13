<!DOCTYPE html>
<html>
<head>
<title>{$title}</title>
{css_tag file="browser.css"}
<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
</head>
<body>
<section id="wrap">
	<header>
		<hgroup>
			<h1>{$title}</h1>
			<h2>{$subtitle}</h2>
		</hgroup>
	</header>
	<nav>
		<a href="/">Tracks</a>
		<a href="/?mode=users">Users</a>
	</nav>
	
	<div id="content">
	{block name="errors"}{if isset($error) and count($error) != 0}
		<div id="errors">
			<h1>{$error}</h1>
		</div>{/if}
	{/block}
	{block name="content"}{/block}
<footer><p>Alle rettigheter &copy; Vegard Lang&aring;s, Lena Silseth og Daniel Razafimandimby.<br>Mer info om cms-et p&aring; <a href="https://github.com/sjefen6/webapp-blogg" target="_blank">github</a></p></footer>
</section>
</body>
</html>