<?xml version="1.0" encoding="UTF-8"?>

<tracks>
	<available>{$tracks.length}</available>
{foreach from=$tracks item=track}
	<track>
		<id>{$track.id}</id>
		<name>{$track.name}</name>
		<creator>{$track.creator}</creator>
		<winner>{$track.winner}</winner>
		<start_ts>{$track.start_ts}</start_ts>
		<stop_ts>{$track.stop_ts}</stop_ts>
	</track>
{/foreach}
</tracks>