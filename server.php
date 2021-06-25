<?php

use Psr\Http\Message\ServerRequestInterface;
use React\EventLoop\Factory;
use React\Http\Message\Response;
use React\Http\Server;
use React\Promise\Promise;

require __DIR__ . '/vendor/autoload.php';

$loop = Factory::create();

$server = new Server($loop, function (ServerRequestInterface $request) use ($loop) {
    return new Promise(function ($resolve, $reject) use ($loop, $request) {
        $q = $request->getQueryParams();
        $a = $q['act'] ?? '';
        $time = $q['sleep'] ?? 1;
        $loop->addTimer($time, function() use ($resolve, $time, $a) {
            $response = new Response(
                200,
                array(
                    'Content-Type' => 'text/plain'
                ),
                "Hello world sleep: {$time}, act:{$a}"
            );
            $resolve($response);
        });
    });
});

$socket = new \React\Socket\Server(isset($argv[1]) ? $argv[1] : '0.0.0.0:0', $loop);
$server->listen($socket);

echo 'Listening on ' . str_replace('tcp:', 'http:', $socket->getAddress()) . PHP_EOL;

$loop->run();