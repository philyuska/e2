<?php

class BlackJackPlayer
{
    public $hand;
    public $hole;
    public $handTotal;
    public $blackjack;
    public $button;
    public $seat;

    public function __construct(Patron $patron=null)
    {
        $this->hand = array();
        $this->hole = array();
        $this->handTotal;
        $this->seat;
        $this->button;
        $this->blackjack = false;
        $this->patron = $patron;
    }

    public function getName()
    {
        return $this->patron->getName();
    }

    public function drawCard($card)
    {
        $this->hand[] = $card;
        $this->handTotal();
        return $card['name'];
    }

    public function drawHoleCard($cards)
    {
        list($card, $hole) = $cards;
        $this->hand[] = $card;
        $this->hole[] = $hole;
        $this->handTotal();
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


    public function debug()
    {
        print "<pre>";
        print_r($this);
        print "</pre>";
    }
}
