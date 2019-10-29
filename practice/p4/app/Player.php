<?php

class Player
{
    public $name;
    public $seat;
    public $hand;
    public $hole;
    public $total;
    public $blackjack;
    public $bonus;
    public $button;
    public $digest;
    public $outcome;

    public function __construct(string $playerName="1", int $seat=1)
    {
        $this->name = "Player" . $playerName;
        $this->seat = $seat;
        $this->hand = array();
        $this->hole = array();
        $this->total = 0;
        $this->blackjack = false;
        $this->bonus = false;
        $this->button = ($seat == 1 ? true : false);

        $this->digest = array('Deal');
        $this->outcome = "";
    }

    public function getName()
    {
    }

    public function drawCard($card)
    {
        $this->hand[] = $card;
    }

    public function drawHoleCard($cards)
    {
        list($card, $hole) = $cards;
        $this->hand[] = $card;
        $this->hole[] = $hole;
    }

    public function debug()
    {
        print "<pre>";
        print_r($this);
        print "</pre>";
    }

    /*     public function getHandTotal()
        {
            $this->total = 0;

            foreach ($this->hand as $card) {
                if ($card['rank'] <> 1) {
                    $this->total = $this->total + $card['value'];
                }
            }

            foreach ($this->hand as $card) {
                if ($card['rank'] == 1) {
                    if ($this->total == 10) {
                        $this->blackjack = true;
                        $this->total = $this->total + 11;
                    } else {
                        if (($this->total + 11) > 21) {
                            $this->total = $this->total + 1;
                        } else {
                            $this->total = $this->total + 11;
                        }
                    }
                }
            }
            return $this->total;
        } */
}
