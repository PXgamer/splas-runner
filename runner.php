<?php

include 'vendor/autoload.php';

use \pxgamer\splas;

// This enables the clearing of the backgrounds folder to reduce storage use
$imgs = scandir('backgrounds/');
foreach ($imgs as $img)
{
	unlink('backgrounds/' . $img);
}

$splas = new splas(''); // Add your Unsplash.com API key here

$rand = json_decode($splas->getRandom());

$file_img = 'backgrounds/'.uniqid('bg-') . '.jpg';

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