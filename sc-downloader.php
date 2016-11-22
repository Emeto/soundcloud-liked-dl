<?php

/*
 * SoundCloud Liked tracks Downloader
 * Use SoundCloud HTTP API to download streamables (128kb/s .mp3 file from any track) from SC CDN
 * @author Jérémy Castellano <contact@emetophobic.com>
 */ 

/* Requires a SoundCloud App client ID, regular user client ID will result in curl requests being rejected (403 Forbidden)
 * This is verified by the server when accessing the streamable URL, which will impact the signature of the request
 */
$client_id = 'YOUR_CLIENT_ID';
$user_id = 1227255; // https://soundcloud.com/emetophobic

	// This get all liked tracks by a given user id, limit of tracks returned by the API can be configured in the URI
	$resolver = file_get_contents("https://api-v2.soundcloud.com/users/" . $user_id . "/track_likes?limit=300&client_id=" . $client_id . "&app_version=1479809541");

	// This will remove unwanted characters.
	// Check http://www.php.net/chr for details
	for ($i = 0; $i <= 31; ++$i) { 
	    $resolver = str_replace(chr($i), "", $resolver);
	}
	$resolver = str_replace(chr(127), "", $resolver);

	// This is the most common part
	// Some file begins with 'efbbbf' to mark the beginning of the file. (binary level)
	// here we detect it and we remove it, basically it's the first 3 characters 
	if (0 === strpos(bin2hex($resolver), 'efbbbf')) {
	   $resolver = substr($resolver, 3);
	}

	$resolver = json_decode($resolver, true);

	foreach ($resolver['collection'] as $track) {
		
		// API endpoint used by SC to get the streamables URLs
		$media_resolver = file_get_contents("https://api.soundcloud.com/i1/tracks/" . $track['track']['id'] . "/streams?client_id=" . $client_id . "&app_version=1479809541");

		for ($i = 0; $i <= 31; ++$i) { 
	    	$media_resolver = str_replace(chr($i), "", $media_resolver);
		}

		$media_resolver = str_replace(chr(127), "", $media_resolver);

		if (0 === strpos(bin2hex($media_resolver), 'efbbbf')) {
		   $media_resolver = substr($media_resolver, 3);
		}

		$media_resolver = json_decode($media_resolver, true);

		// Replace unicode characters encoding from Java into unicode encoding compatible with html_entity_decode().
		$replaced = preg_replace('/\\\\u([0-9A-F]+)/', '&#x$1;', $media_resolver['http_mp3_128_url']);
		$replaced = html_entity_decode($replaced, ENT_COMPAT, 'UTF-8');

		exec('wget -O "' . $track['track']['title'] . '.mp3" "' . $replaced . '"');
		flush();
	}

?>