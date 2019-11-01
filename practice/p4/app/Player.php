<?php

class Player
{
    public $name;
    public $seat;
    public $hand;
    public $hole;
    public $total;
    public $blackjack;
    public $bonusWin;
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
        $this->bonusWin = false;
        $this->button = ($seat == 1 ? true : false);

        $this->digest = array();
        $this->outcome = "";
    }

    public function getName()
    {
        return $this->name;
    }

    public function drawCard($card)
    {
        $this->hand[] = $card;
        return $card['name'];
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
}
