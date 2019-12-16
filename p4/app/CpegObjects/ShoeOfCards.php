<?php
namespace App\CpegObjects;

class ShoeOfCards
{
    public $deck = array();

    public function __construct(array $deckProps = null, int $shoeSize=1)
    {
        if ($deckProps) {
            $this->deck = $deckProps['deck'];
        } else {
            $this->deck = $this->getDeck($shoeSize);
            shuffle($this->deck);
        }
    }

    public function getDeck($shoeSize)
    {
        $card_suits = array(
            'spades' => array( 'UBlock' => '1F0A', 'emoji' => '2660' ),
            'hearts' => array( 'UBlock' => '1F0B', 'emoji' => '2665' ),
            'diamonds' => array( 'UBlock' => '1F0C', 'emoji' => '2666' ),
            'clubs' => array( 'UBlock' => '1F0D', 'emoji' => '2663' ),
        );
    
        $card_ranks = array(
            1 => array( 'name' => "Ace", 'value' => 1, 'UByte' => '1', 'nickname' => "A" ),
            2 => array( 'name' => "2", 'value' => 2, 'UByte' => '2', 'nickname' => "2" ),
            3 => array( 'name' => "3", 'value' => 3, 'UByte' => '3', 'nickname' => "3" ),
            4 => array( 'name' => "4", 'value' => 4, 'UByte' => '4', 'nickname' => "4" ),
            5 => array( 'name' => "5", 'value' => 5, 'UByte' => '5', 'nickname' => "5" ),
            6 => array( 'name' => "6", 'value' => 6, 'UByte' => '6', 'nickname' => "6" ),
            7 => array( 'name' => "7", 'value' => 7, 'UByte' => '7', 'nickname' => "7" ),
            8 => array( 'name' => "8", 'value' => 8, 'UByte' => '8', 'nickname' => "8" ),
            9 => array( 'name' => "9", 'value' => 9, 'UByte' => '9', 'nickname' => "9" ),
            10 => array( 'name' => "10", 'value' => 10, 'UByte' => 'A', 'nickname' => "10" ),
            11 => array( 'name' => "Jack", 'value' => 10, 'UByte' => 'B', 'nickname' => "J" ),
            12 => array( 'name' => "Queen", 'value' => 10, 'UByte' => 'D', 'nickname' => "Q" ),
            13 => array( 'name' => "King", 'value' => 10, 'UByte' => 'E', 'nickname' => "K" ),
        );
      
        for ($i=1; $i<=$shoeSize; $i++) {
            foreach ($card_suits as $suit => $suit_props) {
                foreach ($card_ranks as $rank => $card) {
                    $card = array(
                        'name' => $card['name'] . " of " . ucfirst($suit),
                        'value' => $card['value'],
                        'suit' => $suit,
                        'rank' => $rank,
                        'glyph' => '&#x' . $suit_props['UBlock'] . $card['UByte'] . ';',
                        'emoji' => $card['nickname'] . '&#x' . $suit_props['emoji'] . ';&#xFE0F;',
                    );
    
                    $this->deck[] = $card;
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
        $value = 0;
        $name = 'Bummer';
        $glyph = '&#x' . dechex(rand(hexdec("1f600"), hexdec("1f64f"))) . ';';
        $emoji = null;
    
        $ur_thinking = rand(1, 10);
        $im_thinking = rand(1, 10);
    
        if ($ur_thinking == $im_thinking) {
            $name = 'yahPoo!';
            $value = 21;
            $glyph = '&#x1f4a9';
        }
        
        $holecard = array(
            'name' => $name,
            'value' => $value,
            'suit' => "",
            'rank' => "",
            'glyph' => $glyph,
            'emoji' => $emoji,
        );

        $card = array_shift($this->deck);
    
        return array($holecard, $card);
    }

    public function getCardsRemaining()
    {
        return count($this->deck);
    }

    public function getCardBack()
    {
        $cardback = array(
            'name' => 'cardback',
            'value' => "",
            'suit' => "",
            'rank' => "",
            'glyph' => '&#x1f0a0',
            'emoji' => "",
        );
        return $cardback['glyph'];
    }

    public function debug()
    {
        print "<pre>";
        print_r(count($this->deck));
        print "</pre>";
    }
}
