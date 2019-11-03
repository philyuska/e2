<?php

class BlackJack
{
    private $maxSeats = 5;
    private $initalHandSize = 2;
    private $bonusWin = false;
    private $blackJack = false;
    private $gameOver = false;
    private $roundOver = false;

    private $shoeSize = 6;
    private $shoeReshuffle = .25;

    private $currentRound = 0;


    public function __construct(int $seats=null)
    {
        $this->seats = ($seats ? $seats : $this->maxSeats);
        $this->seatsAvailable = range(0, $this->seats);
        $this->dealer = new BlackJackDealer($name="Dealer");
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

    public function setRoundOver()
    {
        $this->roundOver = true;
    }

    public function setBlackJack()
    {
        $this->blackJack = true;
    }

    public function yahPooBonusWin()
    {
        return $this->bonusWin;
    }

    public function newRound()
    {
        $this->currentRound++;
        $this->bonusWin = false;
        $this->blackJack = false;
        $this->dealer->newHand();

        if ($this->deck->getCardsRemaining() < ($this->shoeSize * 52) * $this->shoeReshuffle) {
            $this->deck = new ShoeOfCards($this->shoeSize);
        }

        $this->players[1]->button = true;
        foreach ($this->players as $player) {
            $player->newHand();
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

            if ($i == ($this->getInitialHandSize())) {
                $this->dealer->drawHoleCard($this->deck->dealHoleCard(), $i);
            } else {
                $this->dealer->drawCard($this->deck->dealCard(), $i);
            }
        }

        //$this->handPeek();

        // if ($this->handPeek()) {
        //     $this->dealer->showHand();
        //     return;
        // }
    }
 
    public function handPeek()
    {
        if ($this->dealer->hand[2]['value'] == 21) {
            $this->setBonusWin();
            $this->setRoundOver();
        } elseif (
            (($this->dealer->hand[1]['rank'] == 1) && ($this->dealer->hole[2]['value'] == 10)) ||
            (($this->dealer->hand[1]['value'] == 10) && ($this->dealer->hole[2]['rank'] == 1))
        ) {
            $this->dealer->handTotal = 21;
            $this->setBlackJack();
            $this->setRoundOver();
        }

        return ($this->determinePeekOutcome());
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

    public function applyRules($player, $dealer)
    {
        if (count($player->hand) < $this->getInitialHandSize()) {
            return;
        }

        if (count($player->hand) > $this->getInitialHandSize()) {
            foreach ($player->hand as $card) {
                if ($card['rank'] <> 1) {
                    $player->total = $player->total + $card['value'];
                }
                
                if ($player->total > 21) {
                    $player->outcome = "Busted";
                }
            }
    
            foreach ($player->hand as $card) {
                if ($card['rank'] == 1) {
                    if (($player->total + 11) > 21) {
                        $player->total = $player->total + 1;
                    } else {
                        $player->total = $player->total + 11;
                    }
                    
                    if ($player->total > 21) {
                        $player->outcome = "Busted";
                    }
                }
            }
        }
    }

    public function determinePeekOutcome()
    {
        if ($this->bonusWin) {
            foreach ($this->players as $player) {
                $player->handOutcome['bonusWin'] = true;
                $player->outcome = "Win, yahPoo bonus";
            }

            return 1;
        } elseif ($this->blackJack) {
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
            return 1;
        }
    }

    public function determineOutcome()
    {
        if ($this->dealer->handTotal() > 21) {
            foreach ($this->players as $player) {
                if ($player->handTotal() > 21) {
                    $player->handOutcome['playerLoss'] = true;
                    $player->outcome = "Bust";
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

    public function registerPlayer(BlackJackPlayer $player, int $seat=null)
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

        //$player->button = (($player->seat==1) ? true : false);
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
