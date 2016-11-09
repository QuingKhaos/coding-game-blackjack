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

namespace CodingGame\BlackJack;

use React\Datagram\Socket;

class Game
{
    const PLAYER = 'Patrik';

    /**
     * @var int
     */
    private $chips;

    /**
     * @var Hand
     */
    private $hand;

    /**
     * @var Card
     */
    private $bank;

    /**
     * @var Socket
     */
    private $client;

    public function __construct(Socket $client)
    {
        $this->chips = 100;
        $this->client = $client;

        $client->on('message', [$this, 'receive']);
    }

    public function receive(string $message)
    {
        if ('ROUND STARTING' === $message) {
            echo 'A new game starts'.PHP_EOL;
            $this->hand = new Hand();

        } elseif ('SET' === $message) {
            $this->setChips();

        } elseif (0 === strpos($message, 'CARD')) {
            list($a, $card) = explode(';', $message);
            $this->addCard((int) $card);

        } elseif (0 === strpos($message, 'BANK')) {
            list($a, $card) = explode(';', $message);
            $this->bank = new Card((int) $card);

        } elseif ('STAY_OR_CARD' === $message) {
            $this->stayOrCard();

        } elseif (0 === strpos($message, 'MONEY')) {
            list($a, $money) = explode(';', $message);
            $this->endRound((int) $money);

        }
    }

    private function setChips()
    {
        if ($this->chips === 0) {
            return;
        }

        if ($this->chips <= 10) {
            $money = $this->chips;
        } else {
            $money = (int) ($this->chips / 10);
            $money = $money < 10 ? 10 : $money;
        }

        $this->chips -= $money;

        echo "Betting {$money} chips".PHP_EOL;
        $this->client->send("SET;{$money};".self::PLAYER);
    }

    private function addCard(int $value)
    {
        $this->hand->addCard(
            new Card($value)
        );
    }

    private function stayOrCard()
    {
        if ($this->hand->stayOrCard($this->bank)) {
            echo '; staying with it'.PHP_EOL;
            $this->client->send('STAY;'.self::PLAYER);
        } else {
            echo '; requesting a new card'.PHP_EOL;
            $this->client->send('CARD;'.self::PLAYER);
        }
    }

    private function endRound(int $money)
    {
        $win = $money - $this->chips;
        $this->chips = $money;

        echo "Round ended; won ${win}; money stack is now at {$this->chips}".PHP_EOL.PHP_EOL;
    }
}
