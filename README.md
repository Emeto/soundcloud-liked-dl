# SoundCloud Liked Tracks Downloader
Use SoundCloud HTTP API to download streamables (128kb/s .mp3 file from any track) from SoundCloud CDN, tracks are gathered from your favorites list with a maximum limit of 144 tracks without timestamp offset (in other words, from the last track you liked on SC).

The script uses an Application's client ID registered on developers.soundcloud.com to access the files. Streamables are stored on AWS  CloudFront with required Policy/Signature/Key-Pair-Id parameters along the HTTP request. A signature generated using a registered application client ID will authorize the streamable to be downloaded safely through a standard HTTP request.

## Requirements
* A valid application Client ID from SoundCloud (obtainable from https://developers.soundcloud.com), a standard user client ID will cause the Signature delievered by CloudFront to make the request reaching the file rejected (403 Forbidden)
* A System Envrionment with PHP 5.5 or newer and wget installed
* Your SoundCloud user ID (obtainable by using the resolver.json API endpoint provided by SC API)

## Track Likes API endpoint accepted URI parameters ($resolver variable in script)
* **offset** - UNIX Timestamp. Tracks prior to this timestamp will only be gathered. Can be useful if you have more than 144 tracks on your favorite list.
* **limit** - Integer. Limit of tracks returned by the API.
* **client_id** - Required. Integer. Your application client ID.
* **app_version** - Integer. Most likely the API version. Default is already in the script

## Notes
FOR PERSONAL AND EDUCATIONAL USE ONLY. THIS DOES NOT DOWNLOAD FULL VERSIONS OF TRACKS BEHIND THE SOUNDCLOUD GO PAYWALL.
