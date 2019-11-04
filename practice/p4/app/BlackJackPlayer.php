<?php

class BlackJackPlayer
{
    public $hand;
    public $handTotal;
    public $blackJack;
    public $handOutcome = array('bonusWin' => false,'playerWin' => false, 'playerLoss' => false, 'playerPush' => false);
    public $outcome;
    public $button;
    public $seat;
    private $patron;
    private $name;
    public $ante;

    public function __construct(Patron $patron=null, $playerName="Anonymous")
    {
        $this->seat;
        $this->button = false;
        $this->hand = array();
        $this->handTotal;
        $this->blackJack = false;
        $this->handOutcome;
        $this->outcome;
        $this->patron = $patron;
        $this->name = ($patron ? null : $playerName);
        $this->ante = 0;
    }

    public function newHand()
    {
        $this->hand = array();
        $this->handTotal = 0;
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
        if ($this->patron) {
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

    public function getTokens()
    {
        return $this->patron->getTokens();
    }

    public function collectAnte(int $tokens = 1)
    {
        if ($tokens < $this->patron->getTokens()) {
            $this->patron->subTokens($tokens);
            $this->ante = $tokens;
        }
    }


    public function winner(int $tokens=null)
    {
        if ($tokens) {
            $this->patron->addTokens($tokens);
        }
    }

    public function loser(int $tokens=null)
    {
        if ($tokens) {
            $this->patron->subTokens($tokens);
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
