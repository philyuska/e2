<?php

class BlackJackPlayer
{
    public $hand;
    public $handTotal;
    public $blackJack;
    public $handOutcome;
    public $outcome;
    public $button;
    public $seat;
    private $patron;
    private $name;
    private $ante;
    private $payout;

    public function __construct(Patron $patron=null, $playerName="Anonymous")
    {
        $this->seat = null;
        $this->button = false;
        $this->hand = array();
        $this->ante = 0;
        $this->handTotal = 0;
        $this->blackJack = false;
        $this->handOutcome =  array('bonusWin' => false,'playerWin' => false, 'playerLoss' => false, 'playerPush' => false);
        $this->outcome = null;
        $this->payout = null;
        $this->patron = $patron;
        $this->name = ($patron ? null : $playerName);
    }

    public function newRound()
    {
        $this->hand = array();
        $this->handTotal = 0;
        $this->blackJack = false;
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

    public function hasBlackJack()
    {
        return ($this->blackJack);
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
