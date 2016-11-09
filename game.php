<?php

/*
 * This file is part of the Black Jack coding game.
 *
 * (c) 2016 Patrik Karisch
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

declare(strict_types = 1);

use CodingGame\BlackJack\Game;

require_once __DIR__.'/vendor/autoload.php';

$loop = React\EventLoop\Factory::create();
$factory = new React\Datagram\Factory($loop);

$factory->createClient('192.168.1.14:22040')
    ->then(function (React\Datagram\Socket $client) {
        $client->send('JOIN;'.Game::PLAYER);

        $client->once('message', function ($message, $serverAddress, $client) {
            switch ($message) {
                case 'OK':
                    echo 'Registered successfully at the game'.PHP_EOL;
                    new Game($client);

                    break;

                case 'REJECTED':
                    echo 'Server rejected us'.PHP_EOL;
                    exit;

                default:
                    echo "Got ${message} from server".PHP_EOL;
            }
        });

        $client->on('error', function ($error, $client) {
            echo 'Error: '.$error->getMessage().PHP_EOL;
        });
    })
;

$loop->run();
