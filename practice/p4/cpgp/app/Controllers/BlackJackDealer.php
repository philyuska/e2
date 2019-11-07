<?php
namespace App\Controllers;

class BlackJackDealer extends BlackJackPlayer
{
    public $name;
    public $hole;

    public function __construct(string $name="Dealer")
    {
        $this->name = $name;
        $this->hole = array();
    }

    public function getName()
    {
        return $this->name;
    }
    
    public function newRound()
    {
        $this->hand = array();
        $this->hole = array();
        $this->handTotal = 0;
    }


    public function drawHoleCard(array $cards, int $key)
    {
        list($card, $hole) = $cards;
        $this->hand[$key] = $card;
        $this->hole[$key] = $hole;
    }

    public function showHand()
    {
        foreach (array_keys($this->hole) as $key) {
            $this->hand[$key] = $this->hole[$key];
        }

        $this->hole=array();
        $this->handTotal = $this->handTotal();
    }

    public function debug()
    {
        print "<pre>";
        print_r($this);
        print "</pre>";
    }
}
