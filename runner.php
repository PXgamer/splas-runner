<?php

include 'vendor/autoload.php';

use \pxgamer\splas;

$interval = 0; // Set the time (minutes) [Default: 0]

set_time_limit(0);

// Do not edit below

while (true)
{
	$now = time();
	// This enables the clearing of the backgrounds folder to reduce storage use
	$iterator = new DirectoryIterator('backgrounds');
	foreach ($iterator as $file)
	{
		if ($file->isDot() || $file->isDot()) {
			continue;
		}
		unlink('backgrounds/' . $file->current());
	}

	$splas = new splas(''); // Add your Unsplash.com API key here

	$rand = json_decode($splas->getRandom());

	$file_img = "backgrounds/".uniqid('bg-') . ".jpg";
	
	echo "\nGrabbing image: $file_img";

	$ch = curl_init();
	$fp = fopen($file_img, 'wb');

	curl_setopt($ch, CURLOPT_URL, $rand->urls->raw);
	curl_setopt($ch, CURLOPT_FILE, $fp);
	curl_setopt($ch, CURLOPT_HEADER, 0);
	curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
	curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);

	$data = curl_exec($ch);

	curl_close($ch);
	fclose($fp);

	exec('wallpaper ' . $file_img);
	if (!$interval) exit;
	sleep($interval * 60);
}