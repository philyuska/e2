<?php

class BlackJackDealer extends BlackJackPlayer
{
    public $name;
    public $hole;

    public function __construct(string $name="Dealer")
    {
        $this->name = $name;
        $this->hole = array();
    }

    public function drawHoleCard($cards)
    {
        list($card, $hole) = $cards;
        $this->hand[] = $card;
        $this->hole[] = $hole;
    }

    public function peekHand()
    {
        if ($this->hand[1]['value'] == 21) {
            $this->bonusWin = true;
            $this->roundOver = true;
        } elseif (
            (($this->hand[0]['rank'] == 1) && ($this->hole[0]['value'] == 10)) ||
            (($this->hand[0]['value'] == 10) && ($this->hole[0]['rank'] == 1))
        ) {
            $this->blackjack = true;
            $this->handTotal = 21;
            $this->roundOver = true;
        }
    }


    public function handTotal()
    {
        $this->handTotal = 0;
        foreach ($this->hand as $card) {
            if ($card['rank'] <> 1) {
                $this->handTotal = $this->handTotal + $card['value'];
            }
        }

        foreach ($this->hand as $card) {
            if ($card['rank'] == 1) {
                if ($this->handTotal == 10) {
                    $this->handTotal = $this->handTotal + 11;
                } else {
                    if (($this->handTotal + 11) > 21) {
                        $this->handTotal = $this->handTotal + 1;
                    } else {
                        $this->handTotal = $this->handTotal + 11;
                    }
                }
            }
        }
        return $this->handTotal;
    }
}
