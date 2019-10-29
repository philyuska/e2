<?php

class ShoeOfCards
{
    public $deck = array();

    public function __construct(int $shoeSize=1)
    {
        $this->deck = $this->getDeck($shoeSize);
        shuffle($this->deck);
    }

    public function getDeck($shoeSize)
    {
        $card_suits = array(
            'spades' => array( 'UBlock' => '1F0A' ),
            'hearts' => array( 'UBlock' => '1F0B' ),
            'diamonds' => array( 'UBlock' => '1F0C' ),
            'clubs' => array( 'UBlock' => '1F0D' ),
        );
    
        $card_ranks = array(
            1 => array( 'name' => "Ace", 'value' => 1, 'UByte' => '1' ),
            2 => array( 'name' => "2", 'value' => 2, 'UByte' => '2' ),
            3 => array( 'name' => "3", 'value' => 3, 'UByte' => '3' ),
            4 => array( 'name' => "4", 'value' => 4, 'UByte' => '4' ),
            5 => array( 'name' => "5", 'value' => 5, 'UByte' => '5' ),
            6 => array( 'name' => "6", 'value' => 6, 'UByte' => '6' ),
            7 => array( 'name' => "7", 'value' => 7, 'UByte' => '7' ),
            8 => array( 'name' => "8", 'value' => 8, 'UByte' => '8' ),
            9 => array( 'name' => "9", 'value' => 9, 'UByte' => '9' ),
            10 => array( 'name' => "10", 'value' => 10, 'UByte' => 'A' ),
            11 => array( 'name' => "Jack", 'value' => 10, 'UByte' => 'B' ),
            12 => array( 'name' => "Queen", 'value' => 10, 'UByte' => 'D' ),
            13 => array( 'name' => "King", 'value' => 10, 'UByte' => 'E' ),
        );
      
        for ($i=1; $i<=$shoeSize; $i++) {
            foreach ($card_suits as $suit => $suit_props) {
                foreach ($card_ranks as $rank => $card) {
                    $_card = array(
                        'name' => $card['name'] . " of " . ucfirst($suit),
                        'value' => $card['value'],
                        'suit' => $suit,
                        'rank' => $rank,
                        'glyph' => '&#x' . $suit_props['UBlock'] . $card['UByte'] . ';',
                    );
    
                    $this->deck[] = $_card;
                }
            }
        }

        return($this->deck);
    }

    public function dealCard()
    {
        $card = array_shift($this->deck);
        return ($card);
    }
    public function dealHoleCard()
    {
        $_value = 0;
        $_name = 'Bummer';
        $_glyph = '&#x' . dechex(rand(hexdec("1f600"), hexdec("1f64f"))) . ';';
    
        $ur_thinking = rand(1, 10);
        $im_thinking = rand(1, 10);
    
        if ($ur_thinking == $im_thinking) {
            $_name = 'yahPoo!';
            $_value = 21;
            $_glyph = '&#x1f4a9';
        }
        
        $holecard = array(
            'name' => $_name,
            'value' => $_value,
            'suit' => "",
            'rank' => "",
            'glyph' => $_glyph,
        );

        $card = array_shift($this->deck);
    
        return array($holecard, $card );
    }

    public function debug()
    {
        print "<pre>";
        print_r(count($this->deck));
        print "</pre>";
    }
}
