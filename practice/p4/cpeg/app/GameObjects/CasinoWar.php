<?php
namespace App\GameObjects;

class CasinoWar
{
    private $maxSeats = 5;
    private $initalHandSize = 1;
    private $casinoWar = false;
    private $gameOver = false;
    private $roundOver = false;
    
    private $shoeSize = 6;
    private $shoeReshuffle = .25;

    private $currentRound = 0;
    private $continueRound = false;

    public function __construct(int $seats=null)
    {
        $this->seats = ($seats ? $seats : $this->maxSeats);
        $this->seatsAvailable = range(0, $this->seats);
        $this->dealer = new CasinoWarPlayer($patron=null, $name="Dealer");
        $this->players = array();
        $this->deck = new ShoeOfCards($this->shoeSize);
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

    public function setCasinoWar()
    {
        $this->casinoWar = true;
    }

    public function getCasinoWar()
    {
        return $this->casinoWar;
    }

    public function continueRound()
    {
        return $this->continueRound;
    }

    public function newRound()
    {
        $this->currentRound++;
        $this->continueRound = false;
        $this->bonusWin = false;
        $this->war = false;
        $this->dealer->newRound();

        if ($this->deck->getCardsRemaining() < ($this->shoeSize * 52) * $this->shoeReshuffle) {
            $this->deck = new ShoeOfCards($this->shoeSize);
        }

        $this->players[1]->button = true;
        foreach ($this->players as $player) {
            $player->newRound();
        }
    }

    public function getCurrentRound()
    {
        return $this->currentRound;
    }

    public function dealHand()
    {
        for ($i=1; $i<=$this->getInitialHandSize(); $i++) {
            for ($x=1; $x<=count($this->players); $x++) {
                $this->players[$x]->drawCard($this->deck->dealCard(), $i);
            }

            $this->dealer->drawCard($this->deck->dealCard(), $i);
        }
        $this->determineOutcome();
        $this->gotoWar();
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
                $this->dealer->drawWarCard($this->deck->dealCard());

                $this->determineWarOutcome();
            }
        }
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
            $player->setGotoWar(false);
            if ($player->warHandTotal() > $this->dealer->warHandTotal()) {
                $player->handOutcome['playerWin'] = true;
                $player->outcome = "Win";
            } elseif ($player->warHandTotal() < $this->dealer->warHandTotal()) {
                $player->handOutcome['playerLoss'] = true;
                $player->outcome = "Lost";
            } elseif ($player->warHandTotal() == $this->dealer->warHandTotal()) {
                $player->outcome = "Push";
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
                $playerAnte = $player->getAnte();

                if ($player->handOutcome['bonusWin']) {
                    $player->payout($playerAnte + $playerAnte * $bonusPayout);
                }
                if ($player->handOutcome['playerWin']) {
                    if ($player->hasBlackJack()) {
                        $player->payout($playerAnte + ($playerAnte * 2));
                    } else {
                        $player->payout($playerAnte + ($playerAnte * $payout));
                    }
                }
                if ($player->handOutcome['playerPush']) {
                    $player->payout($playerAnte);
                }
            }
        }
    }

    public function seatThisPlayer(CasinoWarPlayer $player, int $seat=null)
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

    public function debug()
    {
        print "<pre>";
        print_r($this);
        print "</pre>";
    }
}
