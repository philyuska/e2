<?php
namespace App\GameObjects;

class CasinoWarPlayer
{
    public $hand;
    public $warHand;
    public $handTotal;
    public $handOutcome;
    public $outcome;
    public $button;
    public $seat;
    private $patron;
    private $name;
    private $ante;
    private $payout;
    private $casinoWar;

    public function __construct(array $playerProps = null, Patron $patron=null, $playerName="Anonymous")
    {
        if ($playerProps) {
        } else {
            $this->seat = null;
            $this->button = false;
            $this->hand = array();
            $this->warHand = array();
            $this->ante = 0;
            $this->handTotal = 0;
            $this->handOutcome =  array('bonusWin' => false,'playerWin' => false, 'playerLoss' => false );
            $this->outcome = null;
            $this->payout = null;
            $this->casinoWar = false;
            $this->patron = $patron;
            $this->name = ($patron ? null : $playerName);
        }
    }

    public function newRound()
    {
        $this->hand = array();
        $this->warHand = array();
        $this->handTotal = 0;
        $this->casinoWar = false;
        $this->outcome = null;
        $this->ante = 0;
        $this->payout = null;
        foreach (array_keys($this->handOutcome) as $outcome) {
            $this->handOutcome[$outcome] = false;
        }
    }

    public function getName()
    {
        if ($this->name) {
            return $this->name;
        }
        return $this->patron->getName();
    }

    public function setHandHistory(string $entry)
    {
        if ($this->isPatron()) {
            $this->patron->setHistory($entry);
        }
    }

    public function isPatron()
    {
        return (is_object($this->patron) ? true : false);
    }

    public function hasButton()
    {
        return ($this->button ? true : false);
    }

    public function gotoWar()
    {
        return ($this->casinoWar);
    }

    public function goneToWar()
    {
        return((count($this->warHand) > 0) ? true : false);
    }

    public function setGotoWar(bool $flag=false)
    {
        $this->casinoWar = $flag;
    }

    public function drawCard(array $card, int $key=null)
    {
        if ($key) {
            $this->hand[$key] = $card;
        } else {
            $this->hand[] = $card;
        }
        $this->handTotal();
    }

    public function drawWarCard(array $card, int $key=null)
    {
        if ($key) {
            $this->warHand[$key] = $card;
        } else {
            $this->warHand[] = $card;
        }
    }

    public function handTotal()
    {
        $this->handTotal = 0;
        $lastCard = end($this->hand);

        if ($lastCard['rank'] == 1) {
            $this->handTotal = 14;
        } else {
            $this->handTotal = $lastCard['rank'];
        }

        return $this->handTotal;
    }

    public function warHandTotal()
    {
        $this->handTotal = 0;
        $lastCard = end($this->warHand);

        if ($lastCard['rank'] == 1) {
            $this->handTotal = 14;
        } else {
            $this->handTotal = $lastCard['rank'];
        }

        return $this->handTotal;
    }

    public function collectAnte(int $tokens = 1)
    {
        if ($this->isPatron()) {
            if ($tokens < $this->patron->getTokens()) {
                $this->patron->subTokens($tokens);
                $this->ante = $tokens;
            }
        }
    }

    public function getAnte()
    {
        if ($this->isPatron()) {
            return $this->ante;
        }
    }

    public function getTokens()
    {
        if ($this->isPatron()) {
            return $this->patron->getTokens();
        }
    }

    public function getPayout()
    {
        if ($this->isPatron()) {
            return $this->payout;
        }
    }

    public function payout(int $tokens=null)
    {
        if ($tokens) {
            $this->patron->addTokens($tokens);
            $this->payout = $tokens;
        }
    }

    public function getOutcome()
    {
        return $this->outcome;
    }


    public function getlastCard(string $key='emoji')
    {
        $lastCard = end($this->hand);
        return $lastCard[$key];
    }

    public function handSummary()
    {
        $handSummary = array();

        foreach ($this->hand as $card) {
            $handSummary[] = $card['emoji'];
        }

        return join(',', $handSummary);

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
