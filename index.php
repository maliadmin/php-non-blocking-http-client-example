<?php

require __DIR__ . '/vendor/autoload.php';
use Clue\React\Block;

$loop = React\EventLoop\Factory::create();
$browser = new React\Http\Browser($loop);


$promises = array(
    $browser->get('http://localhost:8000/?sleep=2&act=1'),
    $browser->get('http://localhost:8000/?sleep=1&act=2'),
    $browser->get('http://localhost:8000/?sleep=2&act=3'),
    $browser->get('http://localhost:8000/?sleep=1&act=4'),
    $browser->get('http://localhost:8000/?sleep=2&act=5'),
    $browser->get('http://localhost:8000/?sleep=2&act=6'),
    $browser->get('http://localhost:8000/?sleep=1&act=7'),
);


$start = microtime(true);
$responses = [];

try {
    $responses = Block\awaitAll($promises, $loop);
} catch (Exception $e) {
    echo $e;
}

$end = microtime(true);

$use = $end - $start;

foreach ($responses as $response) {
    $res = $response->getBody();
    echo $res;
    echo PHP_EOL;
}
echo $use;
echo PHP_EOL;



