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
            if ($card->getValue() < 11) {
                $value += $card->getValue();
                unset($cards[$value]);
            }
        }

        foreach ($cards as $card) {
            if ($value <= (21 - 11)) {
                $value += 1;
            } else {
                $value += 11;
            }
        }

        echo "Current hand value is at {$value}";

        return $value;
    }

    public function stayOrCard(Card $bank): bool
    {
        $wholeStay = [2 => true, 3 => true, 4 => true, 5 => true, 6 => true, 7 => true, 8 => true, 9 => true, 10 => true, 11 => true];
        $wholeHit = [2 => false, 3 => false, 4 => false, 5 => false, 6 => false, 7 => false, 8 => false, 9 => false, 10 => false, 11 => false];

        $hardTotals = [
            20 => $wholeStay,
            19 => $wholeStay,
            18 => $wholeStay,
            17 => $wholeStay,
            16 => [2 => true, 3 => true, 4 => true, 5 => true, 6 => true, 7 => false, 8 => false, 9 => false, 10 => false, 11 => false],
            15 => [2 => true, 3 => true, 4 => true, 5 => true, 6 => true, 7 => false, 8 => false, 9 => false, 10 => false, 11 => false],
            14 => [2 => true, 3 => true, 4 => true, 5 => true, 6 => true, 7 => false, 8 => false, 9 => false, 10 => false, 11 => false],
            13 => [2 => true, 3 => true, 4 => true, 5 => true, 6 => true, 7 => false, 8 => false, 9 => false, 10 => false, 11 => false],
            12 => [2 => false, 3 => false, 4 => true, 5 => true, 6 => true, 7 => false, 8 => false, 9 => false, 10 => false, 11 => false],
            11 => $wholeHit,
            10 => $wholeHit,
            9 => $wholeHit,
            8 => $wholeHit,
            7 => $wholeHit,
            6 => $wholeHit,
            5 => $wholeHit,
        ];

        return $hardTotals[$this->getValue()][$bank->getValue()];
    }
}
