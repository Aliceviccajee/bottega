<?php
/*
Plugin Name:  Rest Client
Plugin URI:
Description:  Optional Rest Client - to be used when building AJAX functionality into the site.
Version:      1.0.0
Author:       Lunar Web Development
Author URI:   lunar.build
License:      Restricted
Text Domain:  lunar-rest
Domain Path: /lang
*/

function globRecursive($pattern, $flags = 0) {
	$files = glob($pattern, $flags);

	foreach (glob(dirname($pattern).'/*', GLOB_ONLYDIR|GLOB_NOSORT) as $dir) {
		$files = array_merge($files, globRecursive($dir.'/'.basename($pattern), $flags));
	}

	return $files;
}

foreach (globRecursive(dirname(__DIR__ . '/app/**/*')) as $filename) {
	if (is_file($filename)) {
		require_once $filename;
	}
}

// Init the endpoints
add_action( 'rest_api_init', function () {
	$Booking = new \RestClient\App\Controllers\BookingController('booking', [
		[
			'route' => 'times',
			'method' => 'GET',
			'callback' => 'get',
		],
		[
			'route' => 'distance-check',
			'method' => 'GET',
			'callback' => 'distanceCheck',
		],
	]);
});