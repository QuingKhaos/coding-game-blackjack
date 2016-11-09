<?php

declare(strict_types = 1);

namespace CodingGame\BlackJack;

class Hand
{
    /**
     * @var Card[]
     */
    private $cards = [];

    public function addCard(Card $card)
    {
        echo "Adding card {$card->getValue()} to hand".PHP_EOL;

        $this->cards[] = $card;
    }

    public function getValue(): int
    {
        $cards = $this->cards;
        $value = 0;

        foreach ($cards as $key => $card) {
            $value += $card->getValue();
        }

        echo "Current hand value is at {$value}";

        return $value;
    }
}
