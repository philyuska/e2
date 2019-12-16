<?php
namespace App\CpegObjects;

class BlackJackPlayer
{
    public $hand;
    public $handHistory;
    public $handTotal;
    public $blackJack;
    public $handOutcome;
    public $outcome;
    public $button;
    public $seat;
    public $name;
    public $patron;
    public $wager;
    public $payout;


    public function __construct(array $playerProps = null, Patron $patron=null, $playerName="Anonymous")
    {
        if ($playerProps) {
            $this->seat = $playerProps['seat'];
            $this->button = $playerProps['button'];
            $this->hand = $playerProps['hand'];
            $this->handHistory = $playerProps['handHistory'];
            $this->handTotal = $playerProps['handTotal'];
            $this->blackJack = $playerProps['blackJack'];
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
            $this->wager = 0;
            $this->handTotal = 0;
            $this->blackJack = false;
            $this->handOutcome =  array('bonusWin' => false,'playerWin' => false, 'playerLoss' => false, 'playerPush' => false);
            $this->outcome = null;
            $this->payout = null;
            $this->patron = $patron;
            $this->name = ($patron ? null : $playerName);
        }
    }

    public function newRound(string $gameId, string $handId)
    {
        $this->hand = array();
        $this->handHistory = array();
        $this->handTotal = 0;
        $this->blackJack = false;
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

    public function collectWager(int $tokens)
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
            $gamesRec['hand_summary'] = $this->handSummary() . " Total: " . $this->handTotal();
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
