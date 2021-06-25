# Example of Event-driven, non-blocking I/O with PHP

## Setup
```shell
composer install
```



## Startup your http server
```shell
php server.php 0.0.0.0:8000
```


### Client Example

```php
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
```

## HTTP Client Execution
```shell
php index.php
``

#### output
```text:
Hello world sleep: 2, act:1
Hello world sleep: 1, act:2
Hello world sleep: 2, act:3
Hello world sleep: 1, act:4
Hello world sleep: 2, act:5
Hello world sleep: 2, act:6
Hello world sleep: 1, act:7
2.0106890201569
```