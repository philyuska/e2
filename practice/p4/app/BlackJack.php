<?php

class BlackJack
{
    private $maxSeats = 5;
    private $initalHandSize = 2;
    private $bonusWin = false;
    private $blackjack = false;
    private $gameOver = false;
    private $roundOver = false;

    private $currentRound = 0;


    public function __construct(int $seats=1)
    {
        //$this->seats = (($seats > $this->maxSeats) ? $this->maxSeats : $seats);
        $this->dealer = new Player($playerName="Dealer", $seat=0);
        $this->deck = new ShoeOfCards(1);
        $this->seats = array();
    }

    public function getInitialHandSize()
    {
        return $this->initalHandSize;
    }

    public function yahPooBonusWin()
    {
        return $this->bonusWin;
    }

    public function newRound()
    {
        $this->currentRound++;
    }

    public function getCurrentRound()
    {
        return $this->currentRound;
    }



    public function dealHand($players)
    {
        $cardDigest = array();

        for ($i=1; $i<=$this->getInitialHandSize(); $i++) {
            for ($x=1; $x<=count($players); $x++) {
                $cardDigest[$x][] =  $players[$x]->drawCard($this->deck->dealCard());
            }

            if ($i == ($this->getInitialHandSize())) {
                $this->dealer->drawHoleCard($this->deck->dealHoleCard());
            } else {
                $this->dealer->drawCard($this->deck->dealCard());
            }
        }

        $this->applyIntialGameRules($players);

        for ($x=1; $x<=count($players); $x++) {
            $players[$x]->digest[$this->getCurrentRound()]['Deal'] = join(',', $cardDigest[$x]);
        }
    }
 
    public function shouldHit($player)
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
    
        if ($player->hand[0]['value'] == 1) {
            if ($player->total > 12) {
                if (in_array($player->hand[0]['value'], $strategy['soft'][ $player->total ])) {
                    return true;
                }
            }
        }
        
        if ($player->total < 17) {
            if (in_array($player->hand[0]['value'], $strategy['hard'][ $player->total ])) {
                return true;
            }
        }
    
        return false;
    }

    public function applyIntialGameRules($players)
    {
        if (count($this->dealer->hand) < $this->getInitialHandSize()) {
            return;
        }

        if (count($this->dealer->hand) == $this->getInitialHandSize()) {
            if ($this->dealer->hand[1]['value'] == 21) {
                $this->bonusWin = true;
                $this->roundOver = true;
            }

            if (
                (($this->dealer->hand[0]['rank'] == 1) && ($this->dealer->hole[0]['value'] == 10)) ||
                (($this->dealer->hand[0]['value'] == 10) && ($this->dealer->hole[0]['rank'] == 1))
            ) {
                $this->blackjack = true;
                $this->dealer->blackjack = true;
                $this->dealer->total = 21;
                $this->roundOver = true;
            } else {
                if ($this->dealer->hand[0]['rank'] == 1) {
                    $this->dealer->total = $this->dealer->total + 11;
                } else {
                    $this->dealer->total = $this->dealer->total + $this->dealer->hand[0]['value'];
                }
            }
        }

        for ($x = 1; $x <= count($players); $x++) {
            $this->calcPlayerHandTotal($players[$x]);
            if ($players[$x]->total == 21) {
                $players[$x]->blackjack = true;
            }
        }
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

    public function calcPlayerHandTotal($player)
    {
        $player->total = 0;

        foreach ($player->hand as $card) {
            if ($card['rank'] <> 1) {
                $player->total = $player->total + $card['value'];
            }
        }

        foreach ($player->hand as $card) {
            if ($card['rank'] == 1) {
                if ($player->total == 10) {
                    $player->total = $player->total + 11;
                } else {
                    if (($player->total + 11) > 21) {
                        $player->total = $player->total + 1;
                    } else {
                        $player->total = $player->total + 11;
                    }
                }
            }
        }
    }


    public function getHandTotal($player)
    {
        $player->total = 0;

        foreach ($player->hand as $card) {
            if ($card['rank'] <> 1) {
                $player->total = $player->total + $card['value'];
            }
        }

        foreach ($player->hand as $card) {
            if ($card['rank'] == 1) {
                if ($player->total == 10) {
                    $player->blackjack = true;
                    $player->total = $player->total + 11;
                } else {
                    if (($player->total + 11) > 21) {
                        $player->total = $player->total + 1;
                    } else {
                        $player->total = $player->total + 11;
                    }
                }
            }
        }
        return $player->total;
    }

    public function determineOutcome($players, $dealer)
    {
        for ($x = 1; $x <= count($players) -1; $x++) {
            if ($this->bonusWin) {
                $players[$x]->outcome = "yahPoo Bonus Win!!";
            } else {
                if ($players[ $x ]->total > 21) {
                    $players[ $x ]->outcome = "Busted";
                } else {
                    if ($dealer->total <= 21) {
                        if ($players[ $x ]->total < $dealer->total) {
                            $players[ $x ]->outcome = "Loser";
                        }
                        
                        if ($players[ $x ]->total == $dealer->total) {
                            $players[ $x ]->outcome = "Push";
                        }
                        
                        if ($players[ $x ]->total > $dealer->total) {
                            $players[ $x ]->outcome = "Winner";
                        }
                    }
                    
                    if ($dealer->total > 21) {
                        $dealer->outcome = "Busted";
                        
                        if ($players[ $x ]->total <= 21) {
                            $players[ $x ]->outcome = "Winner";
                        } else {
                            $players[ $x ]->outcome = "Busted";
                        }
                    }
                }
            }
        }
    }

    public function registerPlayer(Player $player, $seat)
    {
        $this->seats[$seat]['player'] = $player;
        $this->seats[$seat]['total'] = 0;
        $this->seats[$seat]['blackjack'] = false;
        $this->seats[$seat]['button'] = ($seat == 1 ? true : false);
    }


    public function debug()
    {
        print "<pre>";
        print_r($this);
        print "</pre>";
    }
}
