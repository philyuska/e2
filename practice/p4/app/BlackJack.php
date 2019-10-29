<?php

class BlackJack
{
    private $maxSeats = 5;
    private $initalHandSize = 2;
    private $yahPooBonus = false;
    private $blackjack = false;


    public function __construct(int $seats=1)
    {
        $this->seats = (($seats > $this->maxSeats) ? $this->maxSeats : $seats);
        $this->deck = new ShoeOfCards(1);
    }

    public function getInitialHandSize()
    {
        return $this->initalHandSize;
    }


    public function dealHand($players, $dealer)
    {
        for ($i=0; $i< $this->getInitialHandSize(); $i++) {
            for ($x=1; $x<=count($players); $x++) {
                if (($players[$x] ->seat == $dealer->seat) && $i == ($this->getInitialHandSize() - 1)) {
                    $players[$x]->drawHoleCard($this->deck->dealHoleCard());
                } else {
                    $players[$x]->drawCard($this->deck->dealCard());
                }
            }
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

    public function applyRules($player, $dealer)
    {
        if (count($player->hand) < $this->getInitialHandSize()) {
            return;
        }

        if (count($player->hand) == $this->getInitialHandSize()) {
            if ($player->seat == $dealer->seat) {
                if (count($player->hole) > 0) {
                    if (
                        (($player->hand[0]['rank'] == 1) && ($player->hole[0]['value'] == 10)) ||
                        (($player->hand[0]['value'] == 10) && ($player->hole[0]['rank'] == 1))
                    ) {
                        $this->blackjack = true;
                        $player->blackjack = true;
                        $player->total = 21;
                    } else {
                        if ($player->hand[0]['rank'] == 1) {
                            $player->total = $player->total + 11;
                        } else {
                            $player->total = $player->total + $player->hand[0]['value'];
                        }
                    }

                    if ($player->hand[1]['value'] == 21) {
                        $this->yahPooBonus = true;
                    }
                } else {
                    foreach ($player->hand as $card) {
                        if ($card['rank'] <> 1) {
                            $player->total = $player->total + $card['value'];
                        }
    
                        if ($card['rank'] == 1) {
                            if (($player->total + 11) > 21) {
                                $player->total = $player->total + 1;
                            } else {
                                $player->total = $player->total + 11;
                            }
                        }
                    }
                }
            } else {
                if ($this->yahPooBonus) {
                    $player->total = 21;
                } else {
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
                }
            }
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
            if ($this->yahPooBonus) {
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

    public function debug()
    {
        print "<pre>";
        print_r($this);
        print "</pre>";
    }
}
