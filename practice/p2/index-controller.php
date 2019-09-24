<?php

$draw_size = 2;
$max_players = 2;
$dealer = $max_players + 1;

$winner = '&#x1F4B0;';
$loser = '&#x3000;';

$players = array();

$suits = array
(
	'spades' => '1F0A',
	'hearts' => '1F0B',
	'diamonds' => '1F0C',
	'clubs' => '1F0D',
);


$card_props = array
(
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

foreach ( $suits as $suit => $ublock_prefix )
{
	foreach ( $card_props as $rank => $card )
	{
		$_card = array
		(
			'name' => $card['name'] . " of " . ucfirst( $suit ),
			'value' => $card['value'],
			'suit' => $suit,
			'rank' => $rank,
			'glyph' => '&#x' . $ublock_prefix . $card['UByte'] . ';',
		);

		$deck[] = $_card;
	}
}

shuffle( $deck );

for ( $i = 0; $i < $draw_size; $i++ )
{
	for ( $x = 1; $x <= $max_players + 1; $x++ )
	{
		if (! array_key_exists( $x, $players ) )
		{ 
			$players[ $x ]['name'] = ( $x == $dealer ? "Dealer" : "Player " . $x );
			$players[ $x ]['total'] = 0;
			$players[ $x ]['blackjack'] = false;			
			$players[ $x ]['digest'] = array();
			$players[ $x ]['outcome'] = ( $x == $dealer ? "Winner" : "Loser" );
		}

		$draw_card = array_shift( $deck );
		$players[ $x ]['hand'][] = $draw_card;
		$players[ $x ]['total'] = $players[ $x ]['total'] + $draw_card['value'];
		
		
		unset ( $draw_card );
	}
}	

ksort( $players );

print "<pre>";

$active_players = $max_players;

for ( $x = 1; $x <= $max_players; $x++ )
{
	while ( ( $players[ $x ]['total'] < 17 ) && ( $players[ $x ]['total'] <= 21 ) )
	{
		$players[ $x ]['digest'][] = "total is {$players[ $x ]['total']}, took hit";
		draw( $x );
	}

	if ( $players[ $x ]['total'] > 21 )
	{
		$players[ $x ]['digest'][] = "total is {$players[ $x ]['total']}, busted";
		$active_players--;
	}
	else
	{
		$players[ $x ]['digest'][] = "total is {$players[ $x ]['total']}, stayed";		
	}
}

if ( $active_players )
{
	if ( $players[ $dealer ]['total'] < 17 )
	{
		while ( ( $players[ $dealer ]['total'] < 17 ) && ( $players[ $dealer ]['total'] <= 21 ) )
		{
			$players[ $dealer ]['digest'][] = "total is {$players[ $dealer ]['total']}, took hit";
			draw( $dealer );
			
		}
	}
	if ( $players[ $dealer ]['total'] > 21 )
	{
		$players[ $dealer ]['digest'][] = "total is {$players[ $dealer ]['total']}, busted";				
	}

	else
	{
		$players[ $dealer ]['digest'][] = "total is {$players[ $dealer ]['total']}, stayed";		
	}
}
else
{
	$players[ $dealer ]['digest'][] = "House wins, all players busted";
}	

// print_r( $players );

print "</pre>";	


for ( $x = 1; $x <= $max_players; $x++ )
{
	if ( $players[ $x ]['total'] > 21 )
	{
		$players[ $x ]['outcome'] = "Busted";
	}
	
	if ( $players[ $x ]['total'] <= 21 )
	{
		if ( $players[ $dealer ]['total'] > 21 )
		{
			$players[ $x ]['outcome'] = "Winner";
			$players[ $dealer ]['outcome'] = "Busted";
		}
		
		if ( $players[ $x ]['total'] > $players[ $dealer ]['total'] )
		{
			$players[ $x ]['outcome'] = "Winner";
			$players[ $dealer ]['outcome'] = "Loser";
		}
		
		if ( $players[ $x ]['total'] == $players[ $dealer ]['total'] )
		{
			$players[ $x ]['outcome'] = "Push";
			$players[ $dealer ]['outcome'] = "Push";
		}		
	}
}


function draw( $x )
{
	global $deck, $players;
	
	$draw_card = array_shift( $deck );
	$players[ $x ]['hand'][] = $draw_card;
		
	$players[ $x ]['total'] = $players[ $x ]['total'] + $draw_card['value'];
	unset ( $draw_card );		
}

