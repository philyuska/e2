<?php
namespace App\CpegObjects;

class BlackJack
{
    public $id = "BlackJack";
    public $seats = 5;
    private $seatsAvailable;
    private $initalHandSize = 2;
    
    private $shoeSize = 6;
    private $shoeReshuffle = .25;

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
            $this->dealer = new BlackJackDealer($playerData = $gameSession['dealer']);
            $this->deck = new ShoeOfCards($deckProps = $gameSession['deck']);
            foreach ($gameSession['players'] as $seat => $playerData) {
                $this->players[$seat] = new BlackJackPlayer($playerData = $playerData);
            }
        } else {
            $this->seatsAvailable = range(0, $this->seats);
            $this->dealer = new BlackJackDealer($playerData = null, $name="Dealer");
            $this->players = array();
            $this->deck = new ShoeOfCards($deckProps=null, $shoeSize=$this->shoeSize);
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

    public function getBonusWin()
    {
        return ($this->dealer->getBonusWin());
    }

    public function getBlackJack()
    {
        return $this->dealer->getBlackJack();
    }

    public function newRound()
    {
        $handId = uniqid();
        $this->continueRound = false;
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
            foreach ($this->players as $player) {
                $player->drawCard($this->deck->dealCard(), $i);
            }

            if ($i == ($this->getInitialHandSize())) {
                $this->dealer->drawHoleCard($this->deck->dealHoleCard(), $i);
            } else {
                $this->dealer->drawCard($this->deck->dealCard(), $i);
            }
        }

        $this->dealer->appendHandDetail($key = 'turn', $value = "Deal " . $this->dealer->handSummary() . " Total " . $this->dealer->handTotal());

        foreach ($this->players as $player) {
            if ($player->isPatron()) {
                $player->appendHandDetail($key = 'turn', $value = "Deal " . $player->handSummary() . " Total " . $player->handTotal());
            }
        }
    }
 
    public function peekHand()
    {
        if ($this->dealer->hand[2]['value'] == 21) {
            $this->dealer->setBonusWin();
        } elseif (
            (($this->dealer->hand[1]['rank'] == 1) && ($this->dealer->hole[2]['value'] == 10)) ||
            (($this->dealer->hand[1]['value'] == 10) && ($this->dealer->hole[2]['rank'] == 1))
        ) {
            $this->dealer->handTotal = 21;
            $this->dealer->setBlackJack();
        }
        $this->dealer->appendHandDetail($key = 'peek', $value = "Deal " . $this->dealer->handSummary() . " Total " . $this->dealer->handTotal());
        return ($this->peekHandOutcome());
    }

    public function continueRound()
    {
        return $this->continueRound;
    }

    public function peekHandOutcome()
    {
        foreach ($this->players as $player) {
            if ($player->handTotal() == 21) {
                $player->blackJack = true;
            }
        }

        if ($this->getBonusWin()) {
            $this->continueRound = false;
            foreach ($this->players as $player) {
                $player->handOutcome['bonusWin'] = true;
                $player->outcome = "Win, yahPoo bonus";
            }
        } elseif ($this->getBlackJack()) {
            $this->continueRound = false;
            foreach ($this->players as $player) {
                if ($player->handTotal() == 21) {
                    $player->blackJack = true;
                    $player->handOutcome['playerPush'] = true;
                    $player->outcome = "Push";
                } else {
                    $player->handOutcome['playerLoss'] = true;
                    $player->outcome = "Lost, dealer blackjack";
                }
            }
        } else {
            $this->continueRound = true;
        }
    }

    public function determineOutcome()
    {
        if ($this->dealer->handTotal() > 21) {
            foreach ($this->players as $player) {
                if ($player->handTotal() > 21) {
                    $player->handOutcome['playerLoss'] = true;
                    $player->outcome = "Bust";
                    $player->blackJack = false;
                } elseif ($player->handTotal() <= 21) {
                    $player->handOutcome['playerWin'] = true;
                    $player->outcome = "Win, dealer bust";
                } else {
                    $player->handOutcome['playerLoss'] = true;
                    $player->outcome = "Lost";
                }
            }
        } else {
            foreach ($this->players as $player) {
                if ($player->handTotal() > 21) {
                    $player->handOutcome['playerLoss'] = true;
                    $player->outcome = "Bust";
                    $player->blackJack = false;
                } elseif ($player->hasBlackJack()) {
                    $player->handOutcome['playerWin'] = true;
                    $player->outcome = "Win, BlackJack";
                } elseif ($player->handTotal() == $this->dealer->handTotal()) {
                    $player->handOutcome['playerPush'] = true;
                    $player->outcome = "Push";
                } elseif ($player->handTotal() > $this->dealer->handTotal()) {
                    $player->handOutcome['playerWin'] = true;
                    $player->outcome = "Win";
                } else {
                    $player->handOutcome['playerLoss'] = true;
                    $player->outcome = "Lost";
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
                    $this->dealer->appendHandDetail($key = 'turn', $value = "Bonus Payout " . $player->getPayout());
                    $player->appendHandDetail($key = 'turn', $value = "Outcome " . $player->outcome);
                }
                if ($player->handOutcome['playerWin']) {
                    if ($player->hasBlackJack()) {
                        $player->payout($playerWager + ($playerWager * 2));
                        $this->dealer->appendHandDetail($key = 'turn', $value = "BlackJack Payout " . $player->getPayout());
                        $player->appendHandDetail($key = 'turn', $value = "Outcome " . $player->outcome);
                    } else {
                        $player->payout($playerWager + ($playerWager * $payout));
                        $this->dealer->appendHandDetail($key = 'turn', $value = "Payout " . $player->getPayout());
                        $player->appendHandDetail($key = 'turn', $value = "Outcome " . $player->outcome);
                    }
                }
                if ($player->handOutcome['playerPush']) {
                    $player->payout($playerWager);
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

    public function seatThisPlayer(BlackJackPlayer $player, int $seat=null)
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
            ($this->players[$seat+1] ? $this->players[$seat+1]->button = true : "");
        }
    }

    public function shouldHit(BlackJackPlayer $player)
    {
        $strategy = array(
            'hard' => array(
                2	=> array( 1,2,3,4,5,6,7,8,9,10 ),
                3	=> array( 1,2,3,4,5,6,7,8,9,10 ),
                4	=> array( 1,2,3,4,5,6,7,8,9,10 ),
                5	=> array( 1,2,3,4,5,6,7,8,9,10 ),
                6	=> array( 1,2,3,4,5,6,7,8,9,10 ),
                7	=> array( 1,2,3,4,5,6,7,8,9,10 ),
                8	=> array( 1,2,3,4,5,6,7,8,9,10 ),
                9	=> array( 1,2,3,4,5,6,7,8,9,10 ),
                10	=> array( 1,2,3,4,5,6,7,8,9,10 ),
                11	=> array( 1,2,3,4,5,6,7,8,9,10 ),
                12	=> array( 1,2,3,7,8,9,10 ),
                13	=> array( 1,7,8,9,10 ),
                14	=> array( 1,7,8,9,10 ),
                15	=> array( 1,7,8,9,10 ),
                16	=> array( 1,7,8,9,10 ),
                17	=> array(),
                18	=> array(),
                19	=> array(),
                20	=> array(),
                21	=> array(),
            ),
            'soft' => array(
                13	=> array( 1,2,3,4,5,6,7,8,9,10 ),
                14	=> array( 1,2,3,4,5,6,7,8,9,10 ),
                15	=> array( 2,3,4,5,6,7,8,9,10 ),
                16	=> array( 1,2,3,4,5,6,7,8,9,10 ),
                17	=> array( 1,2,3,4,5,6,7,8,9,10 ),
                18	=> array( 1,9,10 ),
                19	=> array(),
                20	=> array(),
                21	=> array(),
            ),
        );
    
        if ($player->hand[1]['value'] == 1) {
            if ($player->handTotal() > 12) {
                if (in_array($player->hand[1]['value'], $strategy['soft'][ $player->handTotal() ])) {
                    return true;
                }
            }
        }
        
        if ($player->handTotal < 17) {
            if (in_array($player->hand[1]['value'], $strategy['hard'][ $player->handTotal() ])) {
                return true;
            }
        }
    
        return false;
    }
}
