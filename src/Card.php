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

class Card
{
    /**
     * @var int
     */
    private $value;

    public function __construct(int $value)
    {
        $this->value = $value;
    }

    /**
     * @return int
     */
    public function getValue(): int
    {
        return $this->value;
    }
}
