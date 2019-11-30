<?php
namespace App\GameObjects;

class BlackJackDealer extends BlackJackPlayer
{
    public $name;
    public $hole;

    public function __construct(array $playerProps=null, string $name="Dealer")
    {
        if ($playerProps) {
            $this->seat = $playerProps['seat'];
            $this->button = $playerProps['button'];
            $this->hand = $playerProps['hand'];
            $this->hole = $playerProps['hole'];
            $this->handTotal = $playerProps['handTotal'];
            $this->blackJack = $playerProps['blackJack'];
            $this->handOutcome =  $playerProps['handOutcome'];
            $this->outcome = $playerProps['outcome'];
            $this->name = $playerProps['name'];
        } else {
            $this->name = $name;
            $this->hole = array();
        }
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
