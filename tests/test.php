<?php

require_once __DIR__ . '/../vendor/autoload.php';

use Fitri\GoogleImages;

$images = GoogleImages\images('test');

echo 'get images data from google images: '.count($images);
