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
            $this->handHistory = $playerProps['handHistory'];
            $this->hole = $playerProps['hole'];
            $this->handTotal = $playerProps['handTotal'];
            $this->blackJack = $playerProps['blackJack'];
            $this->handOutcome =  $playerProps['handOutcome'];
            $this->outcome = $playerProps['outcome'];
            $this->name = $playerProps['name'];
        } else {
            $this->name = $name;
            $this->hole = array();
            $this->handOutcome =  array('bonusWin' => false,'blackjack' => false);
        }
    }

    public function getName()
    {
        return $this->name;
    }
    
    public function newRound(string $gameId, string $handId)
    {
        $this->hand = array();
        $this->handHistory = array();
        $this->hole = array();
        $this->handTotal = 0;
        $this->details = "";

        foreach (array_keys($this->handOutcome) as $outcome) {
            $this->handOutcome[$outcome] = false;
        }

        $this->handBegin($gameId, $handId);
    }

    private function handBegin(string $gameId, string $handId)
    {
        $this->setHandDetail($key = 'gameId', $value = $gameId);
        $this->setHandDetail($key = 'handId', $value = $handId);
        $this->setHandDetail($key = 'startTime', $value = time());
    }

    public function endRound()
    {
        $this->setHandDetail($key = 'endTime', $value = time());
        $this->flushHandHistory();
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

        $this->setHandDetail($key = 'show', $value = $this->handSummary() . " Total " . $this->handTotal());
    }

    public function getBonusWin()
    {
        return ($this->handOutcome['bonusWin']);
    }

    public function setBonusWin()
    {
        $this->handOutcome['bonusWin'] = true;
    }

    public function getBlackJack()
    {
        return ($this->handOutcome['blackjack']);
    }

    public function setBlackJack()
    {
        $this->handOutcome['blackjack'] = true;
    }
}
