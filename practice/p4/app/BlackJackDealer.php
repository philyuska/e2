<?php

class BlackJackDealer extends BlackJackPlayer
{
    public $name;
    public $hole;
    protected $game;

    public function __construct(string $name="Dealer", BlackJack $game=null)
    {
        $this->name = $name;
        $this->hole = array();
        $this->game = $game;
    }

    public function getName()
    {
        return $this->name;
    }
    

    public function drawHoleCard(array $cards, int $key)
    {
        list($card, $hole) = $cards;
        $this->hand[$key] = $card;
        $this->hole[$key] = $hole;
    }

    public function peekHand()
    {
        if ($this->hand[2]['value'] == 21) {
            $this->game->setBonusWin();
            $this->game->setRoundOver();
        } elseif (
            (($this->hand[1]['rank'] == 1) && ($this->hole[2]['value'] == 10)) ||
            (($this->hand[1]['value'] == 10) && ($this->hole[2]['rank'] == 1))
        ) {
            $this->handTotal = 21;
            $this->game->setBlackJack();
            $this->game->setRoundOver();
        }
    }

    public function showHand()
    {
        foreach (array_keys($this->hole) as $key) {
            $this->hand[$key] = $this->hole[$key];
        }

        unset($this->hole);
        $this->handTotal = $this->handTotal();
    }

    public function debug()
    {
        print "<pre>";
        print_r($this->hand);
        print_r($this->hole);
        print "</pre>";
    }
}
