<?php
namespace App\CpegObjects;

class CnoteWar
{
    public $id = "CnoteWar";
    public $seats = 5;
    private $initalHandSize = 1;
    private $cnoteWar = false;
    private $gameOver = false;
    private $roundOver = false;
    
    private $shoeSize = 6;
    private $shoeReshuffle = .25;

    private $currentRound = 0;
    private $continueRound = false;

    public function __construct($gameSession = null)
    {
        /*
            make sure the gameSession id is for this game
            otherwise initialize a new game
        */
        if ($gameSession) {
            if ($gameSession['id'] <> $this->id) {
                $gameSession = null;
            }
        }

        if ($gameSession) {
            $this->dealer = new CnoteWarPlayer($playerData = $gameSession['dealer']);
            $this->deck = new ShoeOfCards($deckProps = $gameSession['deck']);
            foreach ($gameSession['players'] as $seat => $playerData) {
                $this->players[$seat] = new CnoteWarPlayer($playerData = $playerData);
            }
        } else {
            $this->seatsAvailable = range(0, $this->seats);
            $this->dealer = new CnoteWarPlayer($playerData = null, $patron=null, $name="Dealer");
            $this->players = array();
            $this->deck = new ShoeOfCards($deckProps = null, $this->shoeSize);
        }
    }

    public function gameId()
    {
        return $this->id;
    }

    public function getSeatsAvailable()
    {
        return ($this->seatsAvailable ? count($this->seatsAvailable) - 1 : 0);
    }

    public function getInitialHandSize()
    {
        return $this->initalHandSize;
    }

    public function setBonusWin()
    {
        $this->bonusWin = true;
    }

    public function getBonusWin()
    {
        return ($this->bonusWin);
    }

    public function setRoundOver()
    {
        $this->roundOver = true;
    }

    public function setCnoteWar()
    {
        $this->cnoteWar = true;
    }

    public function getCnoteWar()
    {
        return $this->cnoteWar;
    }

    public function continueRound()
    {
        return $this->continueRound;
    }

    public function newRound()
    {
        $handId = uniqid();
        $this->continueRound = false;
        $this->bonusWin = false;
        $this->war = false;
        $this->dealer->newRound($gameId = $this->gameId(), $handId = $handId);

        if ($this->deck->getCardsRemaining() < ($this->shoeSize * 52) * $this->shoeReshuffle) {
            $this->deck = new ShoeOfCards($deckProps=null, $this->shoeSize);
        }

        $this->players[1]->button = true;
        foreach ($this->players as $player) {
            $player->newRound($gameId = $this->gameId(), $handId = $handId);
        }
    }

    public function dealHand()
    {
        for ($i=1; $i<=$this->getInitialHandSize(); $i++) {
            for ($x=1; $x<=count($this->players); $x++) {
                $this->players[$x]->drawCard($this->deck->dealCard(), $i);
            }

            $this->dealer->drawCard($this->deck->dealCard(), $i);
        }

        foreach ($this->players as $player) {
            if ($player->isPatron()) {
                $player->appendHandDetail($key = 'turn', $value = 'Deal ' . $player->handSummary() . " Rank " . $player->handTotal());
            }
        }
        $this->dealer->appendHandDetail($key = 'turn', $value = 'Deal ' .$this->dealer->handSummary() . " Rank " . $this->dealer->handTotal());

        // $this->determineOutcome();
        // $this->gotoWar();
    }

    public function gotoWar()
    {
        foreach ($this->players as $player) {
            if ($player->gotoWar()) {
                // as per the game rules burn three cards from the deck
                for ($i=1; $i<=3; $i++) {
                    $this->deck->dealCard();
                }

                $player->drawWarCard($this->deck->dealCard());
                $this->dealer->drawWarCard($this->deck->dealCard(), $player->seat);

                if ($player->isPatron()) {
                    $player->appendHandDetail($key = 'turn', $value = 'War ' . $player->warHandSummary() . " Rank " . $player->warHandTotal());
                    $this->dealer->appendHandDetail($key = 'turn', $value = 'War ' . $this->dealer->warHandSummary() . " Rank " . $this->dealer->warHandTotal($player->seat));
                }
            }
        }
        $this->determineWarOutcome();
    }


    public function determineOutcome()
    {
        foreach ($this->players as $player) {
            $player->setGotoWar(false);
            if ($player->handTotal() > $this->dealer->handTotal()) {
                $player->handOutcome['playerWin'] = true;
                $player->outcome = "Win";
            } elseif ($player->handTotal() < $this->dealer->handTotal()) {
                $player->handOutcome['playerLoss'] = true;
                $player->outcome = "Lost";
            } elseif ($player->handTotal() == $this->dealer->handTotal()) {
                $player->setGotoWar(true);
            }
        }
    }

    public function determineWarOutcome()
    {
        foreach ($this->players as $player) {
            if ($player->gotoWar()) {
                if ($player->warHandTotal() > $this->dealer->warHandTotal($player->seat)) {
                    $player->handOutcome['playerWin'] = true;
                    $player->outcome = "Won, War";
                } elseif ($player->warHandTotal() < $this->dealer->warHandTotal($player->seat)) {
                    $player->handOutcome['playerLoss'] = true;
                    $player->outcome = "Lost, War";
                } elseif ($player->warHandTotal() == $this->dealer->warHandTotal($player->seat)) {
                    $player->handOutcome['playerPush'] = true;
                    $player->outcome = "Push";
                }
            }
        }
    }

    public function payoutPlayers()
    {
        $payout = 1;
        
        if ($this->getBonusWin()) {
            $bonusPayout = (($this->dealer->hole[2]['value'] == 1) ? 11 : $this->dealer->hole[2]['value']);
        }

        foreach ($this->players as $player) {
            if ($player->isPatron()) {
                $playerWager = $player->getWager();

                if ($player->handOutcome['bonusWin']) {
                    $player->payout($playerWager + $playerWager * $bonusPayout);
                }
                if ($player->handOutcome['playerWin']) {
                    if ($player->cnoteWar) {
                        $player->payout($playerWager + ($playerWager * 2));
                        $this->dealer->appendHandDetail($key = 'turn', $value = "War Payout " . $player->getPayout());
                        $player->appendHandDetail($key = 'turn', $value = "Outcome " . $player->outcome);
                    } else {
                        $player->payout($playerWager + ($playerWager * $payout));
                        $this->dealer->appendHandDetail($key = 'turn', $value = "Payout " . $player->getPayout());
                        $player->appendHandDetail($key = 'turn', $value = "Outcome " . $player->outcome);
                    }
                }
                if ($player->handOutcome['playerPush']) {
                    $player->payout($playerWager + 100);
                    $this->dealer->appendHandDetail($key = 'turn', $value = "Push");
                    $this->dealer->appendHandDetail($key = 'turn', $value = "Push Payout " . $player->getPayout());
                    $player->appendHandDetail($key = 'turn', $value = "Outcome " . $player->outcome);
                }
                if ($player->handOutcome['playerLoss']) {
                    $this->dealer->appendHandDetail($key = 'turn', $value = "Patron Lost");
                    $this->dealer->appendHandDetail($key = 'turn', $value = "Collected " . $playerWager);
                    $player->appendHandDetail($key = 'turn', $value = "Outcome " . $player->outcome);
                }
            }
        }
    }

    public function endRound()
    {
        foreach ($this->players as $player) {
            $player->endRound();
        }
        
        $this->dealer->endRound();
    }

    public function seatThisPlayer(CnoteWarPlayer $player, int $seat=null)
    {
        unset($this->seatsAvailable[0]);
        $seatsAvailable = array_keys($this->seatsAvailable);

        if ($seat) {
            $player->seat = $this->seatsAvailable[$seat];
            unset($this->seatsAvailable[$player->seat]);
        } else {
            shuffle($seatsAvailable);
            $player->seat = array_shift($seatsAvailable);
            unset($this->seatsAvailable[$player->seat]);
        }

        $this->players[$player->seat] = $player;

        ksort($this->players);
    }

    public function passButton(int $seat)
    {
        if ($seat == $this->seats) {
            $this->players[$seat]->button = false;
        } else {
            $this->players[$seat]->button = false;
            $this->players[$seat+1]->button = true;
        }
    }
}
