<?php
namespace App\CpegObjects;

class CnoteWarPlayer
{
    public $hand;
    public $handHistory;
    public $warHand;
    public $handTotal;
    public $handOutcome;
    public $outcome;
    public $button;
    public $seat;
    public $patron;
    public $name;
    public $wager;
    public $payout;
    public $cnoteWar;

    public function __construct(array $playerProps = null, Patron $patron=null, $playerName="Anonymous")
    {
        if ($playerProps) {
            $this->seat = $playerProps['seat'];
            $this->button = $playerProps['button'];
            $this->hand = $playerProps['hand'];
            $this->handHistory = $playerProps['handHistory'];
            $this->warHand = $playerProps['warHand'];
            $this->handTotal = $playerProps['handTotal'];
            $this->cnoteWar = $playerProps['cnoteWar'];
            $this->handOutcome =  $playerProps['handOutcome'];
            $this->outcome = $playerProps['outcome'];
            $this->name = $playerProps['name'];
            $this->wager = $playerProps['wager'];
            $this->payout = $playerProps['payout'];
            $this->patron = ($playerProps['patron'] ? new Patron() : null);
        } else {
            $this->seat = null;
            $this->button = false;
            $this->hand = array();
            $this->handHistory = array();
            $this->warHand = array();
            $this->wager = 0;
            $this->handTotal = 0;
            $this->handOutcome =  array('bonusWin' => false,'playerWin' => false, 'playerLoss' => false, 'playerPush' => false );
            $this->outcome = null;
            $this->payout = null;
            $this->cnoteWar = false;
            $this->patron = $patron;
            $this->name = ($patron ? null : $playerName);
        }
    }

    public function newRound(string $gameId, string $handId)
    {
        $this->hand = array();
        $this->handHistory = array();
        $this->warHand = array();
        $this->handTotal = 0;
        $this->cnoteWar = false;
        $this->outcome = null;
        $this->payout = null;
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
        
        if ($this->isPatron()) {
            $this->flushHandHistory();
        }
    }

    public function getName()
    {
        if ($this->name) {
            return $this->name;
        }
        return $this->patron->getName();
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
        return ($this->cnoteWar);
    }

    public function goneToWar()
    {
        return((count($this->warHand) > 0) ? true : false);
    }

    public function setGotoWar(bool $flag=false)
    {
        $this->cnoteWar = $flag;
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

    public function warHandTotal(int $key = null)
    {
        $this->handTotal = 0;

        if ($key) {
            // if (array_key_exists($key, $this->warHand)) {
            $lastCard = $this->warHand[$key];
        // } else {
            //     dump('key ' . $key . ' does not exist in hand');

            // }
        } else {
            $lastCard = end($this->warHand);
        }
        
        if ($lastCard['rank'] == 1) {
            $this->handTotal = 14;
        } else {
            $this->handTotal = $lastCard['rank'];
        }

        return $this->handTotal;
    }

    public function collectWager(int $tokens = 1)
    {
        if ($this->isPatron()) {
            $this->patron->subTokens($tokens);
            $this->wager = $tokens;
        }
    }

    public function getWager()
    {
        if ($this->isPatron()) {
            return $this->wager;
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
    }

    public function warHandSummary(int $key=null)
    {
        if ($key) {
            $lastCard = $this->warHand[$key];
        } else {
            $lastCard = end($this->warHand);
        }

        return $lastCard['emoji'];
    }

    public function setHandHistory(string $entry)
    {
        if ($this->isPatron()) {
            $this->patron->setHistory($entry);
        }
    }

    public function setHandDetail(string $key, string $value)
    {
        $this->handHistory[$key] = $value;
    }

    public function appendHandDetail(string $key, string $value)
    {
        $this->handHistory[$key][] = $value;
    }

    public function flushHandHistory()
    {
        if ($this->isPatron()) {
            $gamesRec['game'] = $this->handHistory['gameId'];
            $gamesRec['hand_id'] = $this->handHistory['handId'];
            $gamesRec['start_time'] = date("Y-m-d h:i:s", $this->handHistory['startTime']);
            $gamesRec['end_time'] = date("Y-m-d h:i:s", $this->handHistory['endTime']);
            $gamesRec['patron_id'] = $this->patron->getId();
            $gamesRec['seat'] = $this->seat;
            $gamesRec['wager'] = $this->wager;
            $gamesRec['hand_summary'] = ($this->gotoWar() ? $this->warHandSummary() : $this->handSummary());
            $gamesRec['outcome'] = $this->outcome;
            $gamesRec['token_win'] = ($this->payout ? $this->getPayout() : null);
            $gamesRec['token_loss'] = (! $this->payout ? $this->wager : null);

            foreach ($this->handHistory['turn'] as $hand) {
                $gameRec = array();
                $gameRec['hand_id'] = $this->handHistory['handId'];
                $gameRec['patron_id'] = $this->patron->getId();
                $gameRec['turn'] = $hand;

                $gameRecs[] = $gameRec;
            }

            $this->patron->setGameHistory($gamesRec = $gamesRec, $gameRecs=$gameRecs);
        }
    }
}
