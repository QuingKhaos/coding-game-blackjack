<?php

declare(strict_types = 1);

require_once __DIR__.'/vendor/autoload.php';

$loop = React\EventLoop\Factory::create();
$factory = new React\Datagram\Factory($loop);

$factory->createClient('192.168.1.14:22040')
    ->then(function (React\Datagram\Socket $client) {
        $client->send('JOIN;Patrik');

        $client->on('message', function ($message, $serverAddress, $client) {
            switch ($message) {
                case 'OK':
                    echo 'Registered successfully at the game'.PHP_EOL;
                    break;

                default:
                    echo "Got ${message} from server";
                    exit;
            }
        });

        $client->on('error', function ($error, $client) {
            echo 'Error: '.$error->getMessage().PHP_EOL;
        });
    })
;

$loop->run();
