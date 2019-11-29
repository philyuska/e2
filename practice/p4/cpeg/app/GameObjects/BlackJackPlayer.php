<?php
namespace App\GameObjects;

use App\GameObjects\Patron;

class BlackJackPlayer
{
    public $hand;
    public $handTotal;
    public $blackJack;
    public $handOutcome;
    public $outcome;
    public $button;
    public $seat;
    public $name;
    public $patron;
    public $ante;
    public $payout;

    public function __construct(array $playerProps = null, Patron $patron=null, $playerName="Anonymous")
    {
        if ($playerProps) {
            $this->seat = $playerProps['seat'];
            $this->button = $playerProps['button'];
            $this->hand = $playerProps['hand'];
            $this->handTotal = $playerProps['handTotal'];
            $this->blackJack = $playerProps['blackJack'];
            $this->handOutcome =  $playerProps['handOutcome'];
            $this->outcome = $playerProps['outcome'];
            $this->name = $playerProps['name'];
            $this->ante = $playerProps['ante'];
            $this->payout = $playerProps['payout'];
            $this->patron = ($playerProps['patron'] ? new Patron() : null);
        } else {
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
    }

    public function newRound()
    {
        $this->hand = array();
        $this->handTotal = 0;
        $this->blackJack = false;
        $this->outcome = null;
        // $this->ante = 0;
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

    public function history()
    {
        $this->patron->setHistory($this->handSummary());
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
